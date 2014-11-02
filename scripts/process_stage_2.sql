
-- ------------------------------------------------------------------------------------------
-- populate tables (after name cleaning)

truncate table prop_filer_session_name_forms;
insert into prop_filer_session_name_forms
select
    ftp_filer_filings.filer_id
  , ftp_filer_filings.session_id
  , ftp_cvr_campaign_disclosure.filer_naml
  , ftp_cvr_campaign_disclosure.form_type
  , group_concat( 
      distinct concat(cal_access_propositions_committees.position, ' ', left(cal_access_propositions.name, 20)) 
      order by concat(cal_access_propositions_committees.position, ' ', left(cal_access_propositions.name, 20)) 
      separator '; '
      ) as positions
  , max(str_to_date(ftp_filer_filings.filing_date, '%m/%d/%Y %h:%i:%s %p')) as last_filing
from 
  ftp_cvr_campaign_disclosure
  inner join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  inner join ftp_filer_filings 
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id
    and ftp_cvr_campaign_disclosure.filing_id = ftp_filer_filings.filing_id
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id
    and ftp_cvr_campaign_disclosure.amend_id = ftp_filer_filings.filing_sequence
  inner join cal_access_propositions_committees 
    on ftp_cvr_campaign_disclosure.filer_id = cal_access_propositions_committees.filer_id
    and ftp_filer_filings.session_id = cal_access_propositions_committees.session
  inner join cal_access_propositions 
    on cal_access_propositions.proposition_id = cal_access_propositions_committees.proposition_id
    and cal_access_propositions.session = cal_access_propositions_committees.session
  inner join filing_ids 
    on ftp_cvr_campaign_disclosure.filing_id = filing_ids.filing_id
    and ftp_cvr_campaign_disclosure.amend_id = filing_ids.amend_id_to_use
group by 
    ftp_filer_filings.filer_id
  , ftp_filer_filings.session_id
  , ftp_cvr_campaign_disclosure.filer_naml
  , ftp_cvr_campaign_disclosure.form_type
;

truncate table prop_filer_sessions;
insert into prop_filer_sessions
select
    prop_filer_session_name_forms.filer_id
  , prop_filer_session_name_forms.session_id
  , max(prop_filer_session_name_forms.filer_naml) as committee_name_to_use
from 
  prop_filer_session_name_forms
  inner join (
    select filer_id, session_id, max(last_filing) as last_filing
    from prop_filer_session_name_forms
    group by filer_id, session_id
    ) as max_last_filing using (filer_id, session_id, last_filing)
group by 
    prop_filer_session_name_forms.filer_id
  , prop_filer_session_name_forms.session_id
;

drop table if exists contributions_full_temp;
create table contributions_full_temp like contributions_full;

-- regular contributions
insert into contributions_full_temp (
    Form
  , RecipientCommitteeType
  , Schedule
  , ElectionCycle
  , ElectionCvr
  , ElectionProp
  , PrimaryGeneralIndicator
  , FilingID
  , AmendID
  , LineItem
  , MemoRefNo
  , TransactionID
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCode
  , RecipientCandidateOfficeCustom
  , RecipientCandidateDistrict
  , HasProposition
  , Target
  , `Position`
  , DonorNameNormalized
  , DonorCity
  , DonorState
  , DonorZipCode
  , DonorEmployerNormalized
  , DonorOccupationNormalized
  , DonorOrganization
  , DonorCommitteeEntity
  , DonorCommitteeID
  , RecipientCommitteeID
  , RecipientCommitteeEntity
  , OriginTable
  )  
select
    ftp_cvr_campaign_disclosure.form_type as Form
  , ftp_cvr_campaign_disclosure.cmtte_type as RecipientCommitteeType
  , contributions.form_type as Schedule
  , ftp_filer_filings.session_id as ElectionCycle
  , str_to_date(left(ftp_cvr_campaign_disclosure.elect_date,locate(' ',ftp_cvr_campaign_disclosure.elect_date)-1), '%m/%d/%Y') as ElectionCvr
  , cal_access_propositions.election_date as ElectionProp
  , '0' as PrimaryGeneralIndicator
  , contributions.filing_id as FilingID
  , contributions.amend_id as AmendID
  , contributions.line_item
  , contributions.memo_refno
  , concat(contributions.filing_id, ' - ', contributions.tran_id) as TransactionID
  , str_to_date(left(contributions.rcpt_date,locate(' ',contributions.rcpt_date)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(contributions.rcpt_date,locate(' ',contributions.rcpt_date)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.amount as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCode
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , if(contributions.ctrib_namf = '', contributions.ctrib_naml, concat(contributions.ctrib_naml, ', ', contributions.ctrib_namf)) as DonorNameNormalized
  , contributions.ctrib_city as DonorCity
  , contributions.ctrib_st as DonorState
  , contributions.ctrib_zip4 as DonorZipCode
  , contributions.ctrib_emp as DonorEmployerNormalized
  , contributions.ctrib_occ as DonorOccupationNormalized
  , if(contributions.entity_cd = 'IND', contributions.ctrib_emp, contributions.ctrib_naml) as DonorOrganization
  , contributions.entity_cd as DonorCommitteeEntity
  , contributions.cmte_id as DonorCommitteeID
  , ftp_filer_filings.filer_id as RecipientCommitteeID
  , ftp_cvr_campaign_disclosure.entity_cd as RecipientCommitteeEntity
  , 'rcpt' as OriginTable
from 
  ftp_rcpt as contributions
  inner join filing_ids -- include only the most recent filing
    on contributions.filing_id = filing_ids.filing_id
    and contributions.amend_id = filing_ids.amend_id_to_use
  inner join ftp_cvr_campaign_disclosure 
    on contributions.filing_id = ftp_cvr_campaign_disclosure.filing_id
    and contributions.amend_id = ftp_cvr_campaign_disclosure.amend_id
  inner join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  inner join ftp_filer_filings 
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id
    and contributions.filing_id = ftp_filer_filings.filing_id
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id
    and contributions.amend_id = ftp_filer_filings.filing_sequence
  left join filing_amends 
    on contributions.filing_id = filing_amends.filing_id 
    and contributions.amend_id = filing_amends.amend_id
  left join cal_access_propositions_committees
    on ftp_filer_filings.filer_id = cal_access_propositions_committees.filer_id
    and ftp_filer_filings.session_id = cal_access_propositions_committees.session
  left join cal_access_propositions
    on cal_access_propositions.proposition_id = cal_access_propositions_committees.proposition_id
    and cal_access_propositions.session = cal_access_propositions_committees.session
  left join prop_filer_sessions
    on ftp_filer_filings.filer_id = prop_filer_sessions.filer_id 
    and ftp_filer_filings.session_id = prop_filer_sessions.session_id
-- where ftp_filer_filings.session_id = 2013
;

-- loans
insert into contributions_full_temp (
    Form
  , RecipientCommitteeType
  , Schedule
  , ElectionCycle
  , ElectionCvr
  , ElectionProp
  , PrimaryGeneralIndicator
  , FilingID
  , AmendID
  , LineItem
  , MemoRefNo
  , TransactionID
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCode
  , RecipientCandidateOfficeCustom
  , RecipientCandidateDistrict
  , HasProposition
  , Target
  , `Position`
  , DonorNameNormalized
  , DonorCity
  , DonorState
  , DonorZipCode
  , DonorEmployerNormalized
  , DonorOccupationNormalized
  , DonorOrganization
  , DonorCommitteeEntity
  , DonorCommitteeID
  , RecipientCommitteeID
  , RecipientCommitteeEntity
  , OriginTable
  )
select
    ftp_cvr_campaign_disclosure.form_type as Form
  , ftp_cvr_campaign_disclosure.cmtte_type as RecipientCommitteeType
  , contributions.form_type as Schedule
  , ftp_filer_filings.session_id as ElectionCycle
  , str_to_date(left(ftp_cvr_campaign_disclosure.elect_date,locate(' ',ftp_cvr_campaign_disclosure.elect_date)-1), '%m/%d/%Y') as ElectionCvr
  , cal_access_propositions.election_date as ElectionProp
  , '0' as PrimaryGeneralIndicator
  , contributions.filing_id as FilingID
  , contributions.amend_id as AmendID
  , contributions.line_item
  , contributions.memo_refno
  , concat(contributions.filing_id, ' - ', contributions.tran_id) as TransactionID
  , str_to_date(left(contributions.loan_date1,locate(' ',contributions.loan_date1)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(contributions.loan_date1,locate(' ',contributions.loan_date1)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.loan_amt1 as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCode
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , if(contributions.lndr_namf = '', contributions.lndr_naml, concat(contributions.lndr_naml, ', ', contributions.lndr_namf)) as DonorNameNormalized
  , contributions.loan_city as DonorCity
  , contributions.loan_st as DonorState
  , contributions.loan_zip4 as DonorZipCode
  , contributions.loan_emp as DonorEmployerNormalized
  , contributions.loan_occ as DonorOccupationNormalized
  , if(contributions.entity_cd = 'IND', contributions.loan_emp, contributions.lndr_naml) as DonorOrganization
  , contributions.entity_cd as DonorCommitteeEntity
  , contributions.cmte_id as DonorCommitteeID
  , ftp_filer_filings.filer_id as RecipientCommitteeID
  , ftp_cvr_campaign_disclosure.entity_cd as RecipientCommitteeEntity
  , 'loan' as OriginTable
from 
  ftp_loan as contributions
  inner join filing_ids -- include only the most recent filing 
    on contributions.filing_id = filing_ids.filing_id 
    and contributions.amend_id = filing_ids.amend_id_to_use
  inner join ftp_cvr_campaign_disclosure 
    on contributions.filing_id = ftp_cvr_campaign_disclosure.filing_id 
    and contributions.amend_id = ftp_cvr_campaign_disclosure.amend_id
  inner join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  inner join ftp_filer_filings 
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id 
    and contributions.filing_id = ftp_filer_filings.filing_id 
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id 
    and contributions.amend_id = ftp_filer_filings.filing_sequence
  left join filing_amends 
    on contributions.filing_id = filing_amends.filing_id 
    and contributions.amend_id = filing_amends.amend_id
  left join cal_access_propositions_committees 
    on ftp_filer_filings.filer_id = cal_access_propositions_committees.filer_id 
    and ftp_filer_filings.session_id = cal_access_propositions_committees.session
  left join cal_access_propositions 
    on cal_access_propositions.proposition_id = cal_access_propositions_committees.proposition_id 
    and cal_access_propositions.session = cal_access_propositions_committees.session
  left join prop_filer_sessions 
    on ftp_filer_filings.filer_id = prop_filer_sessions.filer_id 
    and ftp_filer_filings.session_id = prop_filer_sessions.session_id
-- where ftp_filer_filings.session_id = 2013
;

-- late contributions
insert into contributions_full_temp (
    Form
  , RecipientCommitteeType
  , Schedule
  , ElectionCycle
  , ElectionCvr
  , ElectionProp
  , PrimaryGeneralIndicator
  , FilingID
  , AmendID
  , LineItem
  , MemoRefNo
  , TransactionID
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCode
  , RecipientCandidateOfficeCustom
  , RecipientCandidateDistrict
  , HasProposition
  , Target
  , `Position`
  , DonorNameNormalized
  , DonorCity
  , DonorState
  , DonorZipCode
  , DonorEmployerNormalized
  , DonorOccupationNormalized
  , DonorOrganization
  , DonorCommitteeEntity
  , DonorCommitteeID
  , RecipientCommitteeID
  , RecipientCommitteeEntity
  , OriginTable
  , LateContributionCoveredByRegularFiling
  )
select
    ftp_cvr_campaign_disclosure.form_type as Form
  , ftp_cvr_campaign_disclosure.cmtte_type as RecipientCommitteeType
  , contributions.form_type as Schedule
  , ftp_filer_filings.session_id as ElectionCycle
  , str_to_date(left(ftp_cvr_campaign_disclosure.elect_date,locate(' ',ftp_cvr_campaign_disclosure.elect_date)-1), '%m/%d/%Y') as ElectionCvr
  , cal_access_propositions.election_date as ElectionProp
  , '0' as PrimaryGeneralIndicator
  , contributions.filing_id as FilingID
  , contributions.amend_id as AmendID
  , contributions.line_item
  , contributions.memo_refno
  , concat(contributions.filing_id, ' - ', contributions.tran_id) as TransactionID
  , str_to_date(left(contributions.ctrib_date,locate(' ',contributions.ctrib_date)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(contributions.ctrib_date,locate(' ',contributions.ctrib_date)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.amount as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCode
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , if(contributions.enty_namf = '', contributions.enty_naml, concat(contributions.enty_naml, ', ', contributions.enty_namf)) as DonorNameNormalized
  , contributions.enty_city as DonorCity
  , contributions.enty_st as DonorState
  , contributions.enty_zip4 as DonorZipCode
  , contributions.ctrib_emp as DonorEmployerNormalized
  , contributions.ctrib_occ as DonorOccupationNormalized
  , if(contributions.entity_cd = 'IND', contributions.ctrib_emp, contributions.enty_naml) as DonorOrganization
  , contributions.entity_cd as DonorCommitteeEntity
  , contributions.cmte_id as DonorCommitteeID
  , ftp_filer_filings.filer_id as RecipientCommitteeID
  , ftp_cvr_campaign_disclosure.entity_cd as RecipientCommitteeEntity
  , 's497' as OriginTable
  , if(
        (
          ( 
            str_to_date(ftp_filer_filings.rpt_end,'%m/%d/%Y') > filer_ids.max_rpt_end
            AND str_to_date(contributions.ctrib_date, '%m/%d/%Y') > filer_ids.max_rpt_end
            )
          OR filer_ids.max_rpt_end is null
          )
      , 'N'
      , 'Y'
      ) as LateContributionCoveredByRegularFiling
FROM 
  ftp_s497 as contributions
  inner join filing_ids -- include only the most recent filing
    on contributions.filing_id = filing_ids.filing_id 
    and contributions.amend_id = filing_ids.amend_id_to_use
  inner join ftp_cvr_campaign_disclosure 
    on contributions.filing_id = ftp_cvr_campaign_disclosure.filing_id 
    and contributions.amend_id = ftp_cvr_campaign_disclosure.amend_id
  inner join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  inner join ftp_filer_filings 
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id 
    and contributions.filing_id = ftp_filer_filings.filing_id 
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id 
    and contributions.amend_id = ftp_filer_filings.filing_sequence
  left join filing_amends
    on contributions.filing_id = filing_amends.filing_id 
    and contributions.amend_id = filing_amends.amend_id
  left join cal_access_propositions_committees 
    on ftp_filer_filings.filer_id = cal_access_propositions_committees.filer_id 
    and ftp_filer_filings.session_id = cal_access_propositions_committees.session
  left join cal_access_propositions 
    on cal_access_propositions.proposition_id = cal_access_propositions_committees.proposition_id
    and cal_access_propositions.session = cal_access_propositions_committees.session
  left join prop_filer_sessions
    on ftp_filer_filings.filer_id = prop_filer_sessions.filer_id 
    and ftp_filer_filings.session_id = prop_filer_sessions.session_id
  left join filer_ids on ftp_filer_filings.filer_id = filer_ids.filer_id
-- where ftp_filer_filings.session_id = 2013
;

-- unitemized
insert into contributions_full_temp (
    Form
  , RecipientCommitteeType
  , Schedule
  , ElectionCycle
  , ElectionCvr
  , ElectionProp
  , PrimaryGeneralIndicator
  , FilingID
  , AmendID
  , LineItem
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCode
  , RecipientCandidateOfficeCustom
  , RecipientCandidateDistrict
  , HasProposition
  , Target
  , `Position`
  , DonorNameNormalized
  , RecipientCommitteeID
  , RecipientCommitteeEntity
  , Unitemized
  , OriginTable
  )
select
    ftp_cvr_campaign_disclosure.form_type as Form
  , ftp_cvr_campaign_disclosure.cmtte_type as RecipientCommitteeType
  , contributions.form_type as Schedule
  , ftp_filer_filings.session_id as ElectionCycle
  , str_to_date(left(ftp_cvr_campaign_disclosure.elect_date,locate(' ',ftp_cvr_campaign_disclosure.elect_date)-1), '%m/%d/%Y') as ElectionCvr
  , cal_access_propositions.election_date as ElectionProp
  , '0' as PrimaryGeneralIndicator
  , contributions.filing_id as FilingID
  , contributions.amend_id as AmendID
  , contributions.line_item as LineItem
  , str_to_date(left(ftp_filer_filings.rpt_start,locate(' ',ftp_filer_filings.rpt_start)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(ftp_filer_filings.rpt_end,locate(' ',ftp_filer_filings.rpt_end)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.amount_a as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCode
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , 'Unitemized Contributions' as DonorNameNormalized
  , ftp_filer_filings.filer_id as RecipientCommitteeID
  , ftp_cvr_campaign_disclosure.entity_cd as RecipientCommitteeEntity
  , 'Y' as Unitemized
  , 'smry' as OriginTable
from 
  ftp_smry as contributions
  inner join filing_ids -- include only the most recent filing
    on contributions.filing_id = filing_ids.filing_id 
    and contributions.amend_id = filing_ids.amend_id_to_use
  inner join ftp_cvr_campaign_disclosure
    on contributions.filing_id = ftp_cvr_campaign_disclosure.filing_id 
    and contributions.amend_id = ftp_cvr_campaign_disclosure.amend_id
  inner join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  inner join ftp_filer_filings
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id 
    and contributions.filing_id = ftp_filer_filings.filing_id 
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id 
    and contributions.amend_id = ftp_filer_filings.filing_sequence
  left join filing_amends
    on contributions.filing_id = filing_amends.filing_id 
    and contributions.amend_id = filing_amends.amend_id
  left join cal_access_propositions_committees
    on ftp_filer_filings.filer_id = cal_access_propositions_committees.filer_id 
    and ftp_filer_filings.session_id = cal_access_propositions_committees.session
  left join cal_access_propositions
    on cal_access_propositions.proposition_id = cal_access_propositions_committees.proposition_id
    and cal_access_propositions.session = cal_access_propositions_committees.session
  left join prop_filer_sessions
    on ftp_filer_filings.filer_id = prop_filer_sessions.filer_id 
    and ftp_filer_filings.session_id = prop_filer_sessions.session_id
where
  contributions.form_type = 'A' 
  and contributions.line_item = 2
;

-- flag bad election cycles
update contributions_full_temp
set BadElectionCycle = 'Y'
where 
  ElectionCycle < 2000
  or ElectionCycle > 2020
;

/*
-- add RecipientCandidateID
update
  contributions_full_temp a
  join filer_ids b on a.RecipientCommitteeID = b.filer_id
set a.RecipientCandidateID = b.candidate_id
where b.candidate_id is not null
;
*/

-- set labels and others
update contributions_full_temp
set
  /*
  -- add committee type labels
    RecipientCommitteeTypeDescription = case
      when RecipientCommitteeType = 'C' then 'Officeholder, Candidate Controlled Committee'
      when RecipientCommitteeType = 'G' then 'General Purpose Committee'
      when RecipientCommitteeType = 'B' then 'Primarily Formed Ballot Measure Committee'
      when RecipientCommitteeType = 'P' then 'Primarily Formed Candidate/Officeholder Committee'
      when RecipientCommitteeType = '' then 'Unknown'
      end
  */
  -- add transaction type labels
    TransactionType = CASE
      WHEN Form = 'F460' AND Schedule = 'A' THEN 'Monetary Contribution'
      WHEN Form = 'F460' AND Schedule = 'C' THEN 'Non-Monetary Contribution'
      WHEN Form = 'F460' AND Schedule = 'B1' THEN 'Loan'
      WHEN Form = 'F497' THEN 'Late Contribution'
      ELSE 'Other'
      END
  -- save original candidate office labels before standardizing
  , RecipientCandidateOfficeOriginal = RecipientCandidateOffice
  -- save original committee types before attempting to correct errors
  , RecipientCommitteeTypeOriginal = RecipientCommitteeType
  -- save original candidate name before erasing it for ballot measure contributions
  , RecipientCandidateNameNormalizedOriginal = RecipientCandidateNameNormalized
  -- for propositions, use election date of prop, for others, use election date entered on filing
  , Election = ifnull(ElectionProp,ElectionCvr)
;

-- stardardize custom offices
update 
  contributions_full_temp a
  left join california_data_office_codes b on a.RecipientCandidateOfficeCode = b.office_cd
set a.RecipientCandidateOffice = case
  when a.RecipientCandidateOfficeOriginal like '%office held%' then 'Other'
  when a.RecipientCandidateOfficeOriginal like '%Govern_r%' and a.RecipientCandidateOfficeOriginal not like '%Lieuten_nt%' then 'Governor' 
  when a.RecipientCandidateOfficeOriginal like '%Lieuten_nt%Govern_r%' then 'Lieutenant Governor' 
  when a.RecipientCandidateOfficeOriginal like '%Secretary%' then 'Secretary of State' 
  when a.RecipientCandidateOfficeOriginal like '%state%Controll_r%' then 'State Controller' 
  when a.RecipientCandidateOfficeOriginal like '%Attorn%y gen%' then 'Attorney General' 
  when a.RecipientCandidateOfficeOriginal like '%state%Treasur_r%' then 'State Treasurer' 
  when a.RecipientCandidateOfficeOriginal like '%Insur_nce Com%' then 'Insurance Commissioner' 
  when a.RecipientCandidateOfficeOriginal like '%Superintend_nt%' then 'Superintendent of Public Instruction' 
  when a.RecipientCandidateOfficeOriginal like '%supreme court%' or a.RecipientCandidateOfficeOriginal like '%supreme%justice%' then 'Supreme Court Justice' 
  when a.RecipientCandidateOfficeOriginal like '%Senate%' or a.RecipientCandidateOfficeOriginal like '%Senat_r%' then 'State Senate' 
  when a.RecipientCandidateOfficeOriginal like '%Assem%b%y%' or a.RecipientCandidateOfficeOriginal like '%ASM%' then 'State Assembly' 
  when a.RecipientCandidateOfficeOriginal like '%board of eq%' or a.RecipientCandidateOfficeOriginal like '%boe%' then 'Board of Equalization' 
  when a.RecipientCandidateOfficeOriginal like '%public%emp%ret%sys%' or a.RecipientCandidateOfficeOriginal like 'pers%' or a.RecipientCandidateOfficeOriginal like '%calpers%' then 'Public Employees Retirement System' 
  when a.RecipientCandidateOfficeOriginal like '%appel%te%court%' or a.RecipientCandidateOfficeOriginal like '%appel%te%justice%' then 'State Appellate Court Justice' 
  when a.RecipientCandidateOfficeOriginal like '%asses%r%' then 'Assessor' 
  when a.RecipientCandidateOfficeOriginal like '%board of ed%' then 'Board of Education' 
  when a.RecipientCandidateOfficeOriginal like '%board of super%' or a.RecipientCandidateOfficeOriginal like '%supervis_r%' then 'Board of Supervisors' 
  when a.RecipientCandidateOfficeOriginal like '%city atto%rn%y%' then 'City Attorney' 
  when a.RecipientCandidateOfficeOriginal like '%com%college%' then 'Community College Board' 
  when a.RecipientCandidateOfficeOriginal like '%city%council%' or a.RecipientCandidateOfficeOriginal like '%council%mem%' then 'City Council Member' 
  when a.RecipientCandidateOfficeOriginal like '%county coun__l%' then 'County Counsel' 
  when a.RecipientCandidateOfficeOriginal like '%county supervis_r%' then 'County Supervisor' 
  when a.RecipientCandidateOfficeOriginal like '%local%cont%' then 'Local Controller' 
  when a.RecipientCandidateOfficeOriginal like '%dist% atto%rn%y%' then 'District Attorney' 
  when a.RecipientCandidateOfficeOriginal like '%mayor%' then 'Mayor' 
  when a.RecipientCandidateOfficeOriginal like '%public%def%' then 'Public Defender' 
  when a.RecipientCandidateOfficeOriginal like '%planning%com%' then 'Planning Commissioner' 
  when a.RecipientCandidateOfficeOriginal like '%sher%iff%' then 'Sheriff-Coroner' 
  when a.RecipientCandidateOfficeOriginal like '%superior court%' or a.RecipientCandidateOfficeOriginal like '%judge%' then 'Superior Court Judge' 
  when a.RecipientCandidateOfficeOriginal like '%Treasur_r%' and a.RecipientCandidateOfficeOriginal not like '%state Treasur_r%' then 'Local Treasurer'
  else 'Other'
  end
where a.RecipientCandidateOfficeCode = 'OTH' or b.office_cd is null
;

-- identify committees with inconsistent committee types
drop table if exists tmp_committees_with_multiple_types;
create table tmp_committees_with_multiple_types
select 
    RecipientCommitteeID -- , RecipientCommitteeNameNormalized
  , group_concat(distinct RecipientCommitteeType order by RecipientCommitteeType separator ', ') 'RecipientCommitteeTypes'
  , group_concat(distinct ElectionCycle order by ElectionCycle separator ', ') 'ElectionCycles'
  , sum(TransactionAmount) 'Amount'
from contributions_full_temp
group by RecipientCommitteeID -- , RecipientCommitteeNameNormalized
having 
  RecipientCommitteeTypes like '%,%'
;
alter table tmp_committees_with_multiple_types
  add column BiggestNonBlankType char(1) not null default ''
, add primary key (RecipientCommitteeID -- , RecipientCommitteeNameNormalized
      )
-- , add key (RecipientCommitteeNameNormalized)
;
drop table if exists tmp_committee_types_with_multiple_types;
create table tmp_committee_types_with_multiple_types
select 
    a.RecipientCommitteeID
  -- , a.RecipientCommitteeNameNormalized
  , b.Amount 'TotalAmount'
  , a.RecipientCommitteeType
  , group_concat(distinct a.ElectionCycle order by a.ElectionCycle separator ', ') 'ElectionCycles'
  , sum(a.TransactionAmount) 'Amount'
from 
  contributions_full_temp a
  join tmp_committees_with_multiple_types b using (RecipientCommitteeID -- , RecipientCommitteeNameNormalized
    )
group by
    a.RecipientCommitteeID
  -- , a.RecipientCommitteeNameNormalized
  , b.Amount
  , a.RecipientCommitteeType
;
alter table tmp_committee_types_with_multiple_types
  add primary key (RecipientCommitteeID -- , RecipientCommitteeNameNormalized
    , RecipientCommitteeType)
;
update tmp_committees_with_multiple_types a
set a.BiggestNonBlankType = (
  select b.RecipientCommitteeType
  from tmp_committee_types_with_multiple_types b
  where 
    a.RecipientCommitteeID = b.RecipientCommitteeID
    -- and a.RecipientCommitteeNameNormalized = b.RecipientCommitteeNameNormalized
    and b.RecipientCommitteeType <> ''
  order by b.Amount desc, b.RecipientCommitteeType
  limit 1
  )
;

-- For blank-C/B/P/Gs, change blanks to C/B/P/Gs
update
  contributions_full_temp a
  join tmp_committees_with_multiple_types b using (
      RecipientCommitteeID
    -- , RecipientCommitteeNameNormalized
    )
set a.RecipientCommitteeType = b.BiggestNonBlankType
where a.RecipientCommitteeType = ''
;

-- update temp table
update tmp_committees_with_multiple_types
set RecipientCommitteeTypes = mid(RecipientCommitteeTypes,3,99)
where left(RecipientCommitteeTypes,2) = ', '
;
delete from tmp_committees_with_multiple_types
where RecipientCommitteeTypes not like '%,%'
;

-- standardize remaining conflicting committee types
update
  contributions_full_temp a
  join tmp_committees_with_multiple_types b using (RecipientCommitteeID)
set a.RecipientCommitteeType = case
  -- For C-Bs, if it's ever been a B, change Cs to Bs. Also change anything else to B.
  when b.RecipientCommitteeTypes like '%B%C%' then 'B' 
  -- For C-Gs, if it's ever been a G, change Cs to Gs. But don't change Ps to Gs.
  when (b.RecipientCommitteeTypes like '%C%G%' and a.RecipientCommitteeType = 'C') then 'G' 
  -- For C-Ps, change the type to the one with the highest contribution amount.
  when b.RecipientCommitteeTypes = 'C, P' then b.BiggestNonBlankType 
  -- Committees can change between Gs and Ps, so having both could be legit.
  when b.RecipientCommitteeTypes = 'G, P' then a.RecipientCommitteeType 
  else a.RecipientCommitteeType
  end
;

drop table if exists tmp_committees_with_multiple_types;
drop table if exists tmp_committee_types_with_multiple_types;

-- flag ballot measure committees
update contributions_full_temp
set BallotMeasureCommittee = 'Y'
where
  RecipientCommitteeNameNormalized like '%ballot%'
  or RecipientCommitteeType = 'B'
;

-- flag officeholder committees
update contributions_full_temp
set OfficeHolderCommittee = 'Y'
where
  RecipientCommitteeNameNormalized like '%office%holder%'
  or RecipientCommitteeNameNormalized like '% oh com%'
  or RecipientCommitteeNameNormalized like '% oh account%'
;

-- flag legal defense committees
update contributions_full_temp
set LegalDefenseCommittee = 'Y'
where
  RecipientCommitteeNameNormalized like '%legal def%'
;

-- flag state offices
update contributions_full_temp
set StateOffice = 'Y'
where 
  RecipientCommitteeNameNormalized like '%Assembl%y%' 
  or RecipientCommitteeNameNormalized like '%Senate%'
  or RecipientCommitteeNameNormalized like '%Govern_r%'
  or RecipientCommitteeNameNormalized like '%Controll_r%'
  or (
    RecipientCommitteeNameNormalized like '%Treasur_r%'
    and RecipientCommitteeNameNormalized not like '%local Treasur_r%'
    )
  or RecipientCommitteeNameNormalized like '%Insur_nce Com%'
  or RecipientCommitteeNameNormalized like '%Lieuten_nt%'
  or RecipientCommitteeNameNormalized like '%Secretary%'
  or RecipientCommitteeNameNormalized like '%Superintend_nt%' 
  or RecipientCommitteeNameNormalized like '%Attorn%y gen%'
  or RecipientCommitteeNameNormalized like '%board of eq%' 
  or RecipientCommitteeNameNormalized like '%boe%'
  or RecipientCandidateOffice like '%Assembl%y%' 
  or RecipientCandidateOffice like '%Senate%'
  or RecipientCandidateOffice like '%Govern_r%'
  or RecipientCandidateOffice like '%Controll_r%'
  or (
    RecipientCandidateOffice like '%Treasur_r%'
    and RecipientCandidateOffice not like '%local Treasur_r%'
    )
  or RecipientCandidateOffice like '%Insur_nce Com%'
  or RecipientCandidateOffice like '%Lieuten_nt%'
  or RecipientCandidateOffice like '%Secretary%'
  or RecipientCandidateOffice like '%Superintend%nt%' 
  or RecipientCandidateOffice like '%Attorney gen%'
  or RecipientCandidateOffice like '%board of eq%' 
  or RecipientCandidateOffice like '%boe%'
;

-- flag local offices
update contributions_full_temp
set LocalOffice = 'Y'
where
  RecipientCommitteeNameNormalized like '%mayor%'
  or RecipientCommitteeNameNormalized like '%judge%'
  or RecipientCommitteeNameNormalized like '%supervis_r%'
  or RecipientCommitteeNameNormalized like '%city council%'
  or RecipientCommitteeNameNormalized like '%superior court%'
  or RecipientCommitteeNameNormalized like '%asses%r%'
  or RecipientCommitteeNameNormalized like '%board of education%'
  or RecipientCommitteeNameNormalized like '%college board%'
  or RecipientCommitteeNameNormalized like '%school board%'
  or RecipientCommitteeNameNormalized like '%sher%iff%'
  or RecipientCommitteeNameNormalized like '%local treasur_r%'
  or RecipientCommitteeNameNormalized like '%city attorn%y%'
  or RecipientCommitteeNameNormalized like '%district attorn%y%'
  or RecipientCandidateOffice like '%mayor%'
  or RecipientCandidateOffice like '%judge%'
  or RecipientCandidateOffice like '%supervis_r%'
  or RecipientCandidateOffice like '%city council%'
  or RecipientCandidateOffice like '%superior court%'
  or RecipientCandidateOffice like '%asses%r%'
  or RecipientCandidateOffice like '%board of education%'
  or RecipientCandidateOffice like '%college board%'
  or RecipientCandidateOffice like '%school board%'
  or RecipientCandidateOffice like '%sher%iff%'
  or RecipientCandidateOffice like '%local treasur_r%'
  or RecipientCandidateOffice like '%city attorn%y%'
  or RecipientCandidateOffice like '%district attorn%y%'
;

-- flag non-candidate-controlled committees
-- non-candidate-controlled = committee types P and G (and B, which is a particular type of P or G)
update contributions_full_temp
set CandidateControlledCommittee = 'N' 
where RecipientCommitteeType in ('P','G','B')
;

-- flag non-election committees
-- non-candidate-election = legal defense, officeholder, and ballot measure
update contributions_full_temp
set CandidateElectionCommittee = 'N' 
where
  LegalDefenseCommittee = 'Y'
  or OfficeHolderCommittee = 'Y'
  or BallotMeasureCommittee = 'Y'
;

-- flag schedules to not include
update contributions_full_temp
set IncludedSchedule = 'N'
where
  not (
    OriginTable = 'rcpt' 
    and Form = 'F460' 
    and Schedule not in ('I')
    )
  and not (
    OriginTable = 'loan' 
    and Form = 'F460' 
    and Schedule = 'B1'
    )
  and not (
    OriginTable = 's497' 
    and Form = 'F497'
    and Schedule = 'F497P1' 
    )
  and not (
    OriginTable = 'smry' 
    and Form = 'F460'
    and Schedule = 'A' 
    )
;

-- flag forgiven loans
update 
  contributions_full_temp a
  join ftp_text_memo b
    on a.FilingID = b.filing_id
    and a.AmendID = b.amend_id
    and a.Schedule = b.form_type
    and a.MemoRefNo = b.ref_no
set a.ForgivenLoan = 'Y'
where
  a.Schedule in ('A','F497P1')
  and a.Unitemized = 'N'
  and (
       b.text4000 like '%forg_v%loan%'
    or b.text4000 like '%loan%forg_v%'
    )
;

-- flag candidate contributions
update contributions_full_temp
set CandidateContribution = 'Y'
where
  IncludedSchedule = 'Y'
  and ForgivenLoan = 'N'
  and HasCandidateName = 'Y'
  and HasProposition = 'N'
  and CandidateControlledCommittee = 'Y'
  and CandidateElectionCommittee = 'Y'
  and RecipientCommitteeEntity not in ('BMC', 'MDI', 'SMO')
  and (RecipientCommitteeEntity in ('CAO', 'CTL') or RecipientCandidateNameNormalized <> '')
;  

-- flag ballot measure contributions
update contributions_full_temp
set BallotMeasureContribution = 'Y'
where 
  IncludedSchedule = 'Y'
  and ForgivenLoan = 'N'
  and HasProposition = 'Y'
;

-- flag allied committees
drop table if exists tmp_prop_committees;
create table tmp_prop_committees
select distinct RecipientCommitteeID, ElectionCycle, Election, Target, `Position`
from contributions_full_temp
where BallotMeasureContribution = 'Y'
;
alter table tmp_prop_committees
add primary key (RecipientCommitteeID, ElectionCycle, Election, Target, `Position`)
;
update
  contributions_full_temp a 
  join tmp_prop_committees b
    on a.DonorCommitteeID = b.RecipientCommitteeID 
    and a.ElectionCycle = b.ElectionCycle
    and a.Election = b.Election
    and a.Target = b.Target
    and a.`Position` = b.`Position`
set a.AlliedCommittee = 'Y'
where a.BallotMeasureContribution = 'Y'
;
drop table if exists tmp_prop_committees;

-- Leave blank the 'Office' and 'Recipient Name' columns for Ballot Measure committees, otherwise it misleadingly implies that a Ballot Measure Committee has a Recipient Candidate, which is not the case.
update contributions_full_temp
set
    RecipientCandidateNameNormalized = ''
  , RecipientCandidateOffice = ''
where BallotMeasureContribution = 'Y'
;

drop table if exists contributions_full;
rename table contributions_full_temp to contributions_full;

-- populate contributions table
drop table if exists contributions_temp;
create table contributions_temp like contributions;
insert contributions_temp (
    TransactionType
  , ElectionCycle
  , Election
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , RecipientCommitteeNameNormalized
  , RecipientCandidateNameNormalized
  , RecipientCandidateOffice
  , RecipientCandidateDistrict
  , Target
  , `Position`
  , DonorNameNormalized
  , DonorCity
  , DonorState
  , DonorZipCode
  , DonorEmployerNormalized
  , DonorOccupationNormalized
  , DonorOrganization
  , Unitemized
  , AlliedCommittee
  , CandidateContribution
  , BallotMeasureContribution
  , id
)
select
    TransactionType
  , ElectionCycle
  , Election
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , RecipientCommitteeNameNormalized
  , RecipientCandidateNameNormalized
  , RecipientCandidateOffice
  , RecipientCandidateDistrict
  , Target
  , `Position`
  , DonorNameNormalized
  , DonorCity
  , DonorState
  , DonorZipCode
  , DonorEmployerNormalized
  , DonorOccupationNormalized
  , DonorOrganization
  , Unitemized
  , AlliedCommittee
  , CandidateContribution
  , BallotMeasureContribution
  , id
from contributions_full
where
  IncludedSchedule = 'Y'
  and ForgivenLoan = 'N'
  and BadElectionCycle = 'N'
  and LateContributionCoveredByRegularFiling = 'N'
  and (StateOffice = 'Y' or LocalOffice = 'N')
;

drop table if exists contributions;
rename table contributions_temp to contributions;



