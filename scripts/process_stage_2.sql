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
  , RecType
  , MemoRefNo
  , TransactionID
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateID
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCvrCode
  , RecipientCandidateOfficeCvrSoughtOrHeld
  , RecipientCandidateOfficeCvrCustom
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
  , contributions.line_item as LineItem
  , contributions.rec_type as RecType
  , contributions.memo_refno as MemoRefNo
  , concat(contributions.filing_id, ' - ', contributions.tran_id) as TransactionID
  , str_to_date(left(contributions.rcpt_date,locate(' ',contributions.rcpt_date)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(contributions.rcpt_date,locate(' ',contributions.rcpt_date)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.amount as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.candidate_id,'') as RecipientCandidateID
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCvrCode
  , ftp_cvr_campaign_disclosure.off_s_h_cd as RecipientCandidateOfficeCvrSoughtOrHeld
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCvrCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , concat(
      contributions.ctrib_naml
    , if(contributions.ctrib_nams = '', '', concat(', ',contributions.ctrib_nams)) 
    , if(contributions.ctrib_namt = '', '', concat(', ',contributions.ctrib_namt)) 
    , case
        when contributions.ctrib_namf = '' then ''
        when contributions.ctrib_namf <> '' and contributions.ctrib_namt <> '' then concat(' ',contributions.ctrib_namf)
        else concat(', ',contributions.ctrib_namf)
        end
    ) as DonorNameNormalized
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
  inner join filing_ids
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
;

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
  , RecType
  , MemoRefNo
  , TransactionID
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , LoanPreExistingBalance
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateID
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCvrCode
  , RecipientCandidateOfficeCvrSoughtOrHeld
  , RecipientCandidateOfficeCvrCustom
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
  , contributions.line_item as LineItem
  , contributions.rec_type as RecType
  , contributions.memo_refno as MemoRefNo
  , concat(contributions.filing_id, ' - ', contributions.tran_id) as TransactionID
  , str_to_date(left(contributions.loan_date1,locate(' ',contributions.loan_date1)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(contributions.loan_date1,locate(' ',contributions.loan_date1)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.loan_amt1 as TransactionAmount
  , contributions.loan_amt4 as LoanPreExistingBalance
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.candidate_id,'') as RecipientCandidateID
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCvrCode
  , ftp_cvr_campaign_disclosure.off_s_h_cd as RecipientCandidateOfficeCvrSoughtOrHeld
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCvrCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , concat(
      contributions.lndr_naml
    , if(contributions.lndr_nams = '', '', concat(', ',contributions.lndr_nams)) 
    , if(contributions.lndr_namt = '', '', concat(', ',contributions.lndr_namt)) 
    , case
        when contributions.lndr_namf = '' then ''
        when contributions.lndr_namf <> '' and contributions.lndr_namt <> '' then concat(' ',contributions.lndr_namf)
        else concat(', ',contributions.lndr_namf)
        end
    ) as DonorNameNormalized
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
  inner join filing_ids
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
;

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
  , RecType
  , MemoRefNo
  , TransactionID
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateID
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCvrCode
  , RecipientCandidateOfficeCvrSoughtOrHeld
  , RecipientCandidateOfficeCvrCustom
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
  , contributions.line_item as LineItem
  , contributions.rec_type as RecType
  , contributions.memo_refno as MemoRefNo
  , concat(contributions.filing_id, ' - ', contributions.tran_id) as TransactionID
  , str_to_date(left(contributions.ctrib_date,locate(' ',contributions.ctrib_date)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(contributions.ctrib_date,locate(' ',contributions.ctrib_date)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.amount as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.candidate_id,'') as RecipientCandidateID
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCvrCode
  , ftp_cvr_campaign_disclosure.off_s_h_cd as RecipientCandidateOfficeCvrSoughtOrHeld
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCvrCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , concat(
      contributions.enty_naml
    , if(contributions.enty_nams = '', '', concat(', ',contributions.enty_nams)) 
    , if(contributions.enty_namt = '', '', concat(', ',contributions.enty_namt)) 
    , case
        when contributions.enty_namf = '' then ''
        when contributions.enty_namf <> '' and contributions.enty_namt <> '' then concat(' ',contributions.enty_namf)
        else concat(', ',contributions.enty_namf)
        end
    ) as DonorNameNormalized
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
  inner join filing_ids
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
;

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
  , RecType
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateID
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCvrCode
  , RecipientCandidateOfficeCvrSoughtOrHeld
  , RecipientCandidateOfficeCvrCustom
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
  , contributions.rec_type as RecType
  , str_to_date(left(ftp_filer_filings.rpt_start,locate(' ',ftp_filer_filings.rpt_start)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(ftp_filer_filings.rpt_end,locate(' ',ftp_filer_filings.rpt_end)-1),'%m/%d/%Y') as TransactionDateEnd
  , contributions.amount_a as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.candidate_id,'') as RecipientCandidateID
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCvrCode
  , ftp_cvr_campaign_disclosure.off_s_h_cd as RecipientCandidateOfficeCvrSoughtOrHeld
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCvrCustom
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
  inner join filing_ids
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
  contributions.form_type in ('A','C') 
  and contributions.line_item = 2
;

update 
  filing_ids a
  join (
    select FilingID, AmendID, Target, sum(TransactionAmount) 'Amount'
    from contributions_full_temp
    where 
      Form = 'F460' 
      and Schedule = 'B1'
    group by FilingID, AmendID, Target
    ) b 
      on a.filing_id = b.FilingID
      and a.amend_id_to_use = b.AmendID
set a.loan_total_from_itemized = b.Amount
;
update 
  filing_ids a
  join ftp_smry b 
    on a.filing_id = b.filing_id
    and a.amend_id_to_use = b.amend_id
set a.loan_total_from_summary = b.amount_a
where
  b.form_type = 'B1'
  and b.line_item = '1'
;

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
  , RecType
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , FiledDate
  , RecipientCommitteeNameNormalized
  , HasCandidateName
  , RecipientCandidateID
  , RecipientCandidateNameNormalized
  , RecipientCandidateOfficeCvrCode
  , RecipientCandidateOfficeCvrSoughtOrHeld
  , RecipientCandidateOfficeCvrCustom
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
  , contributions.rec_type as RecType
  , str_to_date(left(ftp_filer_filings.rpt_start,locate(' ',ftp_filer_filings.rpt_start)-1),'%m/%d/%Y') as TransactionDateStart
  , str_to_date(left(ftp_filer_filings.rpt_end,locate(' ',ftp_filer_filings.rpt_end)-1),'%m/%d/%Y') as TransactionDateEnd
  , round(ifnull(filing_ids.loan_total_from_summary,0) - ifnull(filing_ids.loan_total_from_itemized,0),2) as TransactionAmount
  , str_to_date(ftp_filer_filings.filing_date,'%m/%d/%Y %h:%i:%s %p') as FiledDate
  , ifnull(prop_filer_sessions.committee_name_to_use, ftp_cvr_campaign_disclosure.filer_naml) as RecipientCommitteeNameNormalized
  , if(isnull(filing_amends.filing_id),'N','Y') as HasCandidateName
  , ifnull(filing_amends.candidate_id,'') as RecipientCandidateID
  , ifnull(filing_amends.display_name,'') as RecipientCandidateNameNormalized
  , ftp_cvr_campaign_disclosure.office_cd as RecipientCandidateOfficeCvrCode
  , ftp_cvr_campaign_disclosure.off_s_h_cd as RecipientCandidateOfficeCvrSoughtOrHeld
  , ftp_cvr_campaign_disclosure.offic_dscr as RecipientCandidateOfficeCvrCustom
  , ftp_cvr_campaign_disclosure.dist_no as RecipientCandidateDistrict
  , if(isnull(cal_access_propositions_committees.filer_id),'N','Y') as HasProposition
  , ifnull(cal_access_propositions.name,'') as Target
  , ifnull(cal_access_propositions_committees.`position`,'') as `Position`
  , 'Unitemized Loans' as DonorNameNormalized
  , ftp_filer_filings.filer_id as RecipientCommitteeID
  , ftp_cvr_campaign_disclosure.entity_cd as RecipientCommitteeEntity
  , 'Y' as Unitemized
  , 'smry' as OriginTable
from
  ftp_smry as contributions
  inner join filing_ids
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
  contributions.form_type = 'B1' 
  and contributions.line_item = 1
  and round(ifnull(filing_ids.loan_total_from_summary,0)) - round(ifnull(filing_ids.loan_total_from_itemized,0)) > 0
  and ifnull(filing_ids.loan_total_from_summary,0) <> 0
;
  
update 
  contributions_full_temp a
  join candidate_sessions b
    on a.RecipientCandidateID = b.candidate_id
    and a.ElectionCycle = b.session
set
    a.RecipientCandidateOffice501Code = b.office_501_code
  , a.RecipientCandidateOffice501Custom = b.office_501_custom
;

update contributions_full_temp
set IsEmployee = 'Y'
where
  Unitemized = 'Y'
  or DonorCommitteeEntity = 'IND'
;

truncate table contribution_ids;
insert contribution_ids (
    FilingID
  , AmendID
  , LineItem
  , RecType
  , Schedule
  )
select distinct
    FilingID
  , AmendID
  , LineItem
  , RecType
  , Schedule
from contributions_full_temp
;

update
  contributions_full_temp a
  join contribution_ids b using (FilingID, AmendID, LineItem, RecType, Schedule)
set a.ContributionID = b.ContributionID
;

set @CurrentYear = year(current_date);
update contributions_full_temp
set BadElectionCycle = 'Y'
where 
  ElectionCycle < 2000
  or ElectionCycle > @CurrentYear + 10
;

update contributions_full_temp
set
    TransactionType = CASE
      WHEN Form = 'F460' AND Schedule = 'A' THEN 'Monetary Contribution'
      WHEN Form = 'F460' AND Schedule = 'C' THEN 'Non-Monetary Contribution'
      WHEN Form = 'F460' AND Schedule = 'B1' THEN 'Loan'
      WHEN Form = 'F497' THEN 'Late Contribution'
      ELSE 'Other'
      END
  , RecipientCommitteeTypeOriginal = RecipientCommitteeType
  , RecipientCandidateNameNormalizedOriginal = RecipientCandidateNameNormalized
  , Election = ifnull(ElectionProp,ElectionCvr)
;

update
  contributions_full_temp a
  join california_data_office_codes b on a.RecipientCandidateOfficeCvrCode = b.office_cd_cvr
set a.RecipientCandidateOffice = b.description
where 
  a.RecipientCandidateOffice = ''
  and a.RecipientCandidateOfficeCvrCode <> 'OTH'
  and (
    a.RecipientCandidateOfficeCvrSoughtOrHeld <> 'H'
    or a.RecipientCommitteeNameNormalized like '%reelect%'
    or a.RecipientCommitteeNameNormalized like '%re_elect%'
    or a.RecipientCommitteeNameNormalized like '%retain%'
    )
;

update contributions_full_temp
set 
    RecipientCandidateOffice = RecipientCandidateOfficeCvrCustom
  , RecipientCandidateOfficeNeedsCleanup = 'Y'
where
  RecipientCandidateOffice = ''
  and RecipientCandidateOfficeCvrCustom <> ''
  and (
    RecipientCandidateOfficeCvrSoughtOrHeld <> 'H'
    or RecipientCommitteeNameNormalized like '%reelect%'
    or RecipientCommitteeNameNormalized like '%re_elect%'
    or RecipientCommitteeNameNormalized like '%retain%'
    )
;

update contributions_full_temp
set 
    RecipientCandidateOfficeNeedsCleanup = 'N'
  , RecipientCandidateOffice = case
      when RecipientCandidateOffice like '%office held%' then ''
      when RecipientCandidateOffice like '%Govern_r%' and RecipientCandidateOffice not like '%Lieuten_nt%' then 'Governor' 
      when RecipientCandidateOffice like '%Lieuten_nt%Govern_r%' then 'Lieutenant Governor' 
      when RecipientCandidateOffice like '%Secretary%' then 'Secretary of State' 
      when RecipientCandidateOffice like '%state%Controll_r%' then 'State Controller' 
      when RecipientCandidateOffice like '%Attorn%y gen%' then 'Attorney General' 
      when RecipientCandidateOffice like '%state%Treasur_r%' then 'State Treasurer' 
      when RecipientCandidateOffice like '%Insur_nce Com%' then 'Insurance Commissioner' 
      when RecipientCandidateOffice like '%Superintend_nt%' then 'Superintendent of Public Instruction' 
      when RecipientCandidateOffice like '%supreme court%' or RecipientCandidateOffice like '%supreme%justice%' then 'Supreme Court Justice' 
      when RecipientCandidateOffice like '%Senate%' or RecipientCandidateOffice like '%Senat_r%' then 'State Senate' 
      when RecipientCandidateOffice like '%Assem%b%y%' or RecipientCandidateOffice like '%ASM%' then 'State Assembly' 
      when RecipientCandidateOffice like '%board of eq%' or RecipientCandidateOffice like '%boe%' then 'Board of Equalization' 
      when RecipientCandidateOffice like '%public%emp%ret%sys%' or RecipientCandidateOffice like 'pers%' or RecipientCandidateOffice like '%calpers%' then 'Public Employees Retirement System' 
      when RecipientCandidateOffice like '%appel%te%court%' or RecipientCandidateOffice like '%appel%te%justice%' then 'State Appellate Court Justice' 
      when RecipientCandidateOffice like '%asses%r%' then 'Assessor' 
      when RecipientCandidateOffice like '%board of ed%' then 'Board of Education' 
      when RecipientCandidateOffice like '%board of super%' or RecipientCandidateOffice like '%supervis_r%' then 'Board of Supervisors' 
      when RecipientCandidateOffice like '%city atto%rn%y%' then 'City Attorney' 
      when RecipientCandidateOffice like '%com%college%' then 'Community College Board' 
      when RecipientCandidateOffice like '%city%council%' or RecipientCandidateOffice like '%council%mem%' then 'City Council Member' 
      when RecipientCandidateOffice like '%county coun__l%' then 'County Counsel' 
      when RecipientCandidateOffice like '%county supervis_r%' then 'County Supervisor' 
      when RecipientCandidateOffice like '%local%cont%' then 'Local Controller' 
      when RecipientCandidateOffice like '%dist% atto%rn%y%' then 'District Attorney' 
      when RecipientCandidateOffice like '%mayor%' then 'Mayor' 
      when RecipientCandidateOffice like '%public%def%' then 'Public Defender' 
      when RecipientCandidateOffice like '%planning%com%' then 'Planning Commissioner' 
      when RecipientCandidateOffice like '%sher%iff%' then 'Sheriff-Coroner' 
      when RecipientCandidateOffice like '%superior court%' or RecipientCandidateOffice like '%judge%' then 'Superior Court Judge' 
      when RecipientCandidateOffice like '%Treasur_r%' and RecipientCandidateOffice not like '%state Treasur_r%' then 'Local Treasurer'
      else ''
      end
where RecipientCandidateOfficeNeedsCleanup = 'Y'
;

update
  contributions_full_temp a
  join california_data_office_codes b on a.RecipientCandidateOffice501Code = b.office_cd_501
set a.RecipientCandidateOffice = b.description
where
  a.RecipientCandidateOffice = ''
  and b.office_cd_cvr <> 'OTH'
;

update contributions_full_temp
set 
    RecipientCandidateOffice = RecipientCandidateOffice501Custom
  , RecipientCandidateOfficeNeedsCleanup = 'Y'
where 
  RecipientCandidateOffice = ''
  and RecipientCandidateOffice501Custom <> ''
;

update contributions_full_temp
set 
    RecipientCandidateOfficeNeedsCleanup = 'N'
  , RecipientCandidateOffice = case
      when RecipientCandidateOffice like '%office held%' then ''
      when RecipientCandidateOffice like '%Govern_r%' and RecipientCandidateOffice not like '%Lieuten_nt%' then 'Governor' 
      when RecipientCandidateOffice like '%Lieuten_nt%Govern_r%' then 'Lieutenant Governor' 
      when RecipientCandidateOffice like '%Secretary%' then 'Secretary of State' 
      when RecipientCandidateOffice like '%state%Controll_r%' then 'State Controller' 
      when RecipientCandidateOffice like '%Attorn%y gen%' then 'Attorney General' 
      when RecipientCandidateOffice like '%state%Treasur_r%' then 'State Treasurer' 
      when RecipientCandidateOffice like '%Insur_nce Com%' then 'Insurance Commissioner' 
      when RecipientCandidateOffice like '%Superintend_nt%' then 'Superintendent of Public Instruction' 
      when RecipientCandidateOffice like '%supreme court%' or RecipientCandidateOffice like '%supreme%justice%' then 'Supreme Court Justice' 
      when RecipientCandidateOffice like '%Senate%' or RecipientCandidateOffice like '%Senat_r%' then 'State Senate' 
      when RecipientCandidateOffice like '%Assem%b%y%' or RecipientCandidateOffice like '%ASM%' then 'State Assembly' 
      when RecipientCandidateOffice like '%board of eq%' or RecipientCandidateOffice like '%boe%' then 'Board of Equalization' 
      when RecipientCandidateOffice like '%public%emp%ret%sys%' or RecipientCandidateOffice like 'pers%' or RecipientCandidateOffice like '%calpers%' then 'Public Employees Retirement System' 
      when RecipientCandidateOffice like '%appel%te%court%' or RecipientCandidateOffice like '%appel%te%justice%' then 'State Appellate Court Justice' 
      when RecipientCandidateOffice like '%asses%r%' then 'Assessor' 
      when RecipientCandidateOffice like '%board of ed%' then 'Board of Education' 
      when RecipientCandidateOffice like '%board of super%' or RecipientCandidateOffice like '%supervis_r%' then 'Board of Supervisors' 
      when RecipientCandidateOffice like '%city atto%rn%y%' then 'City Attorney' 
      when RecipientCandidateOffice like '%com%college%' then 'Community College Board' 
      when RecipientCandidateOffice like '%city%council%' or RecipientCandidateOffice like '%council%mem%' then 'City Council Member' 
      when RecipientCandidateOffice like '%county coun__l%' then 'County Counsel' 
      when RecipientCandidateOffice like '%county supervis_r%' then 'County Supervisor' 
      when RecipientCandidateOffice like '%local%cont%' then 'Local Controller' 
      when RecipientCandidateOffice like '%dist% atto%rn%y%' then 'District Attorney' 
      when RecipientCandidateOffice like '%mayor%' then 'Mayor' 
      when RecipientCandidateOffice like '%public%def%' then 'Public Defender' 
      when RecipientCandidateOffice like '%planning%com%' then 'Planning Commissioner' 
      when RecipientCandidateOffice like '%sher%iff%' then 'Sheriff-Coroner' 
      when RecipientCandidateOffice like '%superior court%' or RecipientCandidateOffice like '%judge%' then 'Superior Court Judge' 
      when RecipientCandidateOffice like '%Treasur_r%' and RecipientCandidateOffice not like '%state Treasur_r%' then 'Local Treasurer'
      else ''
      end
where RecipientCandidateOfficeNeedsCleanup = 'Y'
;

update
  contributions_full_temp a
  join california_data_office_codes b on a.RecipientCandidateOfficeCvrCode = b.office_cd_cvr
set a.RecipientCandidateOffice = b.description
where 
  a.RecipientCandidateOffice = ''
  and a.RecipientCandidateOfficeCvrSoughtOrHeld = 'H'
  and a.RecipientCandidateOfficeCvrCode <> 'OTH'
;

update contributions_full_temp
set 
    RecipientCandidateOffice = RecipientCandidateOfficeCvrCustom
  , RecipientCandidateOfficeNeedsCleanup = 'Y'
where
  RecipientCandidateOffice = ''
  and RecipientCandidateOfficeCvrSoughtOrHeld = 'H'
  and RecipientCandidateOfficeCvrCustom <> ''
;

update contributions_full_temp
set 
    RecipientCandidateOfficeNeedsCleanup = 'N'
  , RecipientCandidateOffice = case
      when RecipientCandidateOffice like '%office held%' then ''
      when RecipientCandidateOffice like '%Govern_r%' and RecipientCandidateOffice not like '%Lieuten_nt%' then 'Governor' 
      when RecipientCandidateOffice like '%Lieuten_nt%Govern_r%' then 'Lieutenant Governor' 
      when RecipientCandidateOffice like '%Secretary%' then 'Secretary of State' 
      when RecipientCandidateOffice like '%state%Controll_r%' then 'State Controller' 
      when RecipientCandidateOffice like '%Attorn%y gen%' then 'Attorney General' 
      when RecipientCandidateOffice like '%state%Treasur_r%' then 'State Treasurer' 
      when RecipientCandidateOffice like '%Insur_nce Com%' then 'Insurance Commissioner' 
      when RecipientCandidateOffice like '%Superintend_nt%' then 'Superintendent of Public Instruction' 
      when RecipientCandidateOffice like '%supreme court%' or RecipientCandidateOffice like '%supreme%justice%' then 'Supreme Court Justice' 
      when RecipientCandidateOffice like '%Senate%' or RecipientCandidateOffice like '%Senat_r%' then 'State Senate' 
      when RecipientCandidateOffice like '%Assem%b%y%' or RecipientCandidateOffice like '%ASM%' then 'State Assembly' 
      when RecipientCandidateOffice like '%board of eq%' or RecipientCandidateOffice like '%boe%' then 'Board of Equalization' 
      when RecipientCandidateOffice like '%public%emp%ret%sys%' or RecipientCandidateOffice like 'pers%' or RecipientCandidateOffice like '%calpers%' then 'Public Employees Retirement System' 
      when RecipientCandidateOffice like '%appel%te%court%' or RecipientCandidateOffice like '%appel%te%justice%' then 'State Appellate Court Justice' 
      when RecipientCandidateOffice like '%asses%r%' then 'Assessor' 
      when RecipientCandidateOffice like '%board of ed%' then 'Board of Education' 
      when RecipientCandidateOffice like '%board of super%' or RecipientCandidateOffice like '%supervis_r%' then 'Board of Supervisors' 
      when RecipientCandidateOffice like '%city atto%rn%y%' then 'City Attorney' 
      when RecipientCandidateOffice like '%com%college%' then 'Community College Board' 
      when RecipientCandidateOffice like '%city%council%' or RecipientCandidateOffice like '%council%mem%' then 'City Council Member' 
      when RecipientCandidateOffice like '%county coun__l%' then 'County Counsel' 
      when RecipientCandidateOffice like '%county supervis_r%' then 'County Supervisor' 
      when RecipientCandidateOffice like '%local%cont%' then 'Local Controller' 
      when RecipientCandidateOffice like '%dist% atto%rn%y%' then 'District Attorney' 
      when RecipientCandidateOffice like '%mayor%' then 'Mayor' 
      when RecipientCandidateOffice like '%public%def%' then 'Public Defender' 
      when RecipientCandidateOffice like '%planning%com%' then 'Planning Commissioner' 
      when RecipientCandidateOffice like '%sher%iff%' then 'Sheriff-Coroner' 
      when RecipientCandidateOffice like '%superior court%' or RecipientCandidateOffice like '%judge%' then 'Superior Court Judge' 
      when RecipientCandidateOffice like '%Treasur_r%' and RecipientCandidateOffice not like '%state Treasur_r%' then 'Local Treasurer'
      else ''
      end
where RecipientCandidateOfficeNeedsCleanup = 'Y'
;

drop table if exists tmp_committees_with_multiple_types;
create table tmp_committees_with_multiple_types
select 
    RecipientCommitteeID
  , group_concat(distinct RecipientCommitteeType order by RecipientCommitteeType separator ', ') 'RecipientCommitteeTypes'
  , group_concat(distinct ElectionCycle order by ElectionCycle separator ', ') 'ElectionCycles'
  , sum(TransactionAmount) 'Amount'
from contributions_full_temp
group by RecipientCommitteeID
having RecipientCommitteeTypes like '%,%'
;

alter table tmp_committees_with_multiple_types
  add column BiggestNonBlankType char(1) not null default ''
, add primary key (RecipientCommitteeID)
;

drop table if exists tmp_committee_types_with_multiple_types;
create table tmp_committee_types_with_multiple_types
select 
    a.RecipientCommitteeID
  , b.Amount 'TotalAmount'
  , a.RecipientCommitteeType
  , group_concat(distinct a.ElectionCycle order by a.ElectionCycle separator ', ') 'ElectionCycles'
  , sum(a.TransactionAmount) 'Amount'
from 
  contributions_full_temp a
  join tmp_committees_with_multiple_types b using (RecipientCommitteeID)
group by
    a.RecipientCommitteeID
  , b.Amount
  , a.RecipientCommitteeType
;

alter table tmp_committee_types_with_multiple_types
add primary key (RecipientCommitteeID, RecipientCommitteeType)
;

update tmp_committees_with_multiple_types a
set a.BiggestNonBlankType = (
  select b.RecipientCommitteeType
  from tmp_committee_types_with_multiple_types b
  where 
    a.RecipientCommitteeID = b.RecipientCommitteeID
    and b.RecipientCommitteeType <> ''
  order by b.Amount desc, b.RecipientCommitteeType
  limit 1
  )
;

update
  contributions_full_temp a
  join tmp_committees_with_multiple_types b using (RecipientCommitteeID)
set a.RecipientCommitteeType = b.BiggestNonBlankType
where a.RecipientCommitteeType = ''
;

update tmp_committees_with_multiple_types
set RecipientCommitteeTypes = mid(RecipientCommitteeTypes,3,99)
where left(RecipientCommitteeTypes,2) = ', '
;

delete from tmp_committees_with_multiple_types
where RecipientCommitteeTypes not like '%,%'
;

update
  contributions_full_temp a
  join tmp_committees_with_multiple_types b using (RecipientCommitteeID)
set a.RecipientCommitteeType = case
  when b.RecipientCommitteeTypes like '%B%C%' then 'B' 
  when (b.RecipientCommitteeTypes like '%C%G%' and a.RecipientCommitteeType = 'C') then 'G' 
  when b.RecipientCommitteeTypes = 'C, P' then b.BiggestNonBlankType 
  when b.RecipientCommitteeTypes = 'G, P' then a.RecipientCommitteeType 
  else a.RecipientCommitteeType
  end
;

drop table if exists tmp_committees_with_multiple_types;
drop table if exists tmp_committee_types_with_multiple_types;

update contributions_full_temp
set BallotMeasureCommittee = 'Y'
where
  RecipientCommitteeNameNormalized like '%ballot%'
  or RecipientCommitteeType = 'B'
;

update contributions_full_temp
set OfficeHolderCommittee = 'Y'
where
  RecipientCommitteeNameNormalized like '%office%holder%'
  or RecipientCommitteeNameNormalized like '% oh com%'
  or RecipientCommitteeNameNormalized like '% oh account%'
;

update contributions_full_temp
set LegalDefenseCommittee = 'Y'
where
  RecipientCommitteeNameNormalized like '%legal def%'
;

update contributions_full_temp
set
    RecipientCandidateNameNormalized = ''
  , RecipientCandidateOffice = ''
where HasProposition = 'Y'
;

update contributions_full_temp
set CandidateControlledCommittee = 'N' 
where RecipientCommitteeType in ('P','G','B')
;

update contributions_full_temp
set CandidateElectionCommittee = 'N' 
where
  LegalDefenseCommittee = 'Y'
  or OfficeHolderCommittee = 'Y'
  or BallotMeasureCommittee = 'Y'
;

update contributions_full_temp
set IncludedSchedule = 'N'
where
  not (
    OriginTable = 'rcpt' 
    and Form = 'F460' 
    and Schedule in ('A','A-1','C')

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
    and Schedule in ('A','B1','C') 
    )
;

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

update contributions_full_temp
set NoNewLoanAmount = 'Y'
where
  OriginTable = 'loan' 
  and Form = 'F460' 
  and Schedule = 'B1'
  and TransactionAmount = 0
  and LoanPreExistingBalance > 0
;

update contributions_full_temp
set CandidateContribution = 'Y'
where
  IncludedSchedule = 'Y'
  and HasCandidateName = 'Y'
  and HasProposition = 'N'
  and CandidateControlledCommittee = 'Y'
  and CandidateElectionCommittee = 'Y'
  and RecipientCommitteeEntity not in ('BMC', 'MDI', 'SMO')
  and (RecipientCommitteeEntity in ('CAO', 'CTL') or RecipientCandidateNameNormalized <> '')
;

update contributions_full_temp
set BallotMeasureContribution = 'Y'
where 
  IncludedSchedule = 'Y'
  and HasProposition = 'Y'
;

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

update contributions_full_temp
set StateOffice = 'Y'
where 
  CandidateContribution = 'Y'
  and (
       RecipientCandidateOffice like '%Assembl%y%' 
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
    )
;

update contributions_full_temp
set LocalOffice = 'Y'
where
  CandidateContribution = 'Y'
  and (
       RecipientCandidateOffice like '%mayor%'
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
    )
;

update contributions_full_temp
set StateOffice = 'Y'
where 
  CandidateContribution = 'Y'
  and StateOffice = 'N'
  and LocalOffice = 'N'
  and (
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
    )
;

update contributions_full_temp
set LocalOffice = 'Y'
where
  CandidateContribution = 'Y'
  and StateOffice = 'N'
  and LocalOffice = 'N'
  and (
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
    )
;

drop table if exists contributions_full;
rename table contributions_full_temp to contributions_full;

drop table if exists ca_search.contributions_temp;
create table ca_search.contributions_temp like ca_search.contributions;
insert ca_search.contributions_temp (
    TransactionType
  , ElectionCycle
  , Election
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , RecipientCommitteeID
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
  , DonorCommitteeID
  , Unitemized
  , IsEmployee
  , AlliedCommittee
  , CandidateContribution
  , BallotMeasureContribution
  , ContributionID
  , id
)
select
    TransactionType
  , ElectionCycle
  , Election
  , TransactionDateStart
  , TransactionDateEnd
  , TransactionAmount
  , RecipientCommitteeID
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
  , DonorCommitteeID
  , Unitemized
  , IsEmployee
  , AlliedCommittee
  , CandidateContribution
  , BallotMeasureContribution
  , ContributionID
  , id
from contributions_full
where
  IncludedSchedule = 'Y'
  and ForgivenLoan = 'N'
  and NoNewLoanAmount = 'N'
  and BadElectionCycle = 'N'
  and LateContributionCoveredByRegularFiling = 'N'
  and (StateOffice = 'Y' or LocalOffice = 'N')
  and not (Unitemized = 'Y' and TransactionAmount = 0)
;

