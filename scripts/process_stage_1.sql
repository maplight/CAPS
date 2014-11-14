truncate table filer_ids;insert filer_ids (filer_id, max_rpt_end)
select filer_id
  , max(str_to_date(
      case
        when (form_id = 'F460' and rpt_end <> '' and str_to_date(rpt_end,'%m/%d/%Y') <= curdate()) then rpt_end
        else null
        end
    ,'%m/%d/%Y'
    )) 'max_rpt_end'
from ftp_filer_filings 
group by filer_id
;

truncate table table_filing_ids;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'rcpt', filing_id, max(amend_id) 
from ftp_rcpt 
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'loan', filing_id, max(amend_id) 
from ftp_loan 
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 's497', filing_id, max(amend_id) 
from ftp_s497 
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'smry', filing_id, max(amend_id) as amend_id_to_use
from ftp_smry
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'cvr', filing_id, max(amend_id) as amend_id_to_use
from ftp_cvr_campaign_disclosure
group by filing_id
;

truncate table filing_ids;
insert filing_ids (
    filing_id
  , amend_id_to_use
  , rcpt_amend_id
  , loan_amend_id
  , s497_amend_id
  , smry_amend_id
  , cvr_amend_id
  )
select 
    filing_id
  , max(amend_id)
  , max(if(OriginTable='rcpt',amend_id,null))
  , max(if(OriginTable='loan',amend_id,null))
  , max(if(OriginTable='s497',amend_id,null))
  , max(if(OriginTable='smry',amend_id,null))
  , max(if(OriginTable='cvr',amend_id,null))
from table_filing_ids 
group by filing_id
;

/*-- FILER_XREF: This table maps legacy filer identification numbers to the systems filer identification numbers. -from CalAccessTablesWeb.pdf */
truncate table disclosure_filer_ids;
insert disclosure_filer_ids (disclosure_filer_id, filer_id)
select
    ftp_cvr_campaign_disclosure.filer_id as disclosure_filer_id
  , max(ifnull(ftp_filer_xref.filer_id, ftp_cvr_campaign_disclosure.filer_id)) as filer_id
from 
  ftp_cvr_campaign_disclosure
  left join ftp_filer_xref 
    on ftp_cvr_campaign_disclosure.filer_id = ftp_filer_xref.xref_id 
    and ftp_filer_xref.xref_id <> 0
group by ftp_cvr_campaign_disclosure.filer_id
;

truncate table f501_502_cleaned;
insert f501_502_cleaned (
    filing_id
  , amend_id
  , form_type
  , filer_id
  , yr_of_elec
  , session
  , rpt_date
  , office_cd
  , offic_dscr
  , cand_namf
  , cand_naml
  )
select
    filing_id
  , amend_id
  , form_type
  , filer_id
  , yr_of_elec
  , if(cast(yr_of_elec as decimal)%2=0,cast(yr_of_elec as decimal)-1,cast(yr_of_elec as decimal)) as session
  , str_to_date(left(rpt_date,locate(' ',rpt_date)-1),'%m/%d/%Y') as rpt_date
  , office_cd
  , offic_dscr
  , cand_namf
  , cand_naml
from ftp_f501_502
;
set @CurrentYear = year(current_date);
delete from f501_502_cleaned
where
  form_type <> 'F501'
  or session < 2000 
  or session > @CurrentYear + 10
  or filer_id <= 0
;
update f501_502_cleaned
set rpt_date = '1950-01-01'
where rpt_date is null
;

truncate table candidate_sessions;
insert candidate_sessions (
    candidate_id
  , session
  , office_501_code
  , office_501_custom
  , candidate_name
  )
select
    aa.filer_id
  , aa.session
  , aa.office_cd
  , max(aa.offic_dscr) as offic_dscr
  , max(concat(aa.cand_naml,', ',aa.cand_namf)) as candidate_name
from 
  f501_502_cleaned aa
  join (
    select 
        a.filer_id
      , a.session
      , a.rpt_date
      , min(a.office_cd) as office_cd
    from 
      f501_502_cleaned a
      join (
        select 
            filer_id
          , session
          , max(rpt_date) as rpt_date
        from f501_502_cleaned
        group by 
            filer_id
          , session
        ) b using (filer_id, session, rpt_date)
    group by 
        a.filer_id
      , a.session
      , a.rpt_date
    ) bb using (filer_id, session, rpt_date, office_cd)
group by
    aa.filer_id
  , aa.session
  , aa.office_cd
;

/*-- get candidates from scraped table*/
truncate table candidate_ids;
insert candidate_ids (candidate_id, number_of_names, last_session)
select 
    id as candidate_id
  , count(distinct name) as number_of_names
  , max(session) as last_session
from cal_access_candidates
group by id
;

/*-- updated those candidates with their name from their most recent session*/
update 
  candidate_ids a
  join cal_access_candidates b on a.candidate_id = b.id and a.last_session = b.session
set a.candidate_name = b.name
;

/*-- append candidate_ids for committees */
update
  filer_ids a
  join (
    select filer_id, max(id) 'id'
    from cal_access_candidates_committees
    group by filer_id
    ) b using (filer_id)
set a.candidate_id = b.id
;

/*-- updated candidate name for committees*/
update
  filer_ids a
  join candidate_ids b using (candidate_id)
set a.candidate_name = b.candidate_name
;

/*-- set up the table holding the candidate name possibilities for every filing/amendment */
truncate table filing_amends;
insert into filing_amends (
    filing_id
  , amend_id
  , cand_naml
  , cand_namf
  , cand_namt
  , cand_nams
  , candidate_id
  , candidate_name
  )
select
    ftp_cvr_campaign_disclosure.filing_id
  , ftp_cvr_campaign_disclosure.amend_id
  , ftp_cvr_campaign_disclosure.cand_naml
  , ftp_cvr_campaign_disclosure.cand_namf
  , ftp_cvr_campaign_disclosure.cand_namt
  , ftp_cvr_campaign_disclosure.cand_nams
  , filer_ids.candidate_id
  , ifnull(filer_ids.candidate_name,'')
from 
  ftp_cvr_campaign_disclosure
  join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  left join ftp_filer_filings 
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id
    and ftp_cvr_campaign_disclosure.filing_id = ftp_filer_filings.filing_id
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id
    and ftp_cvr_campaign_disclosure.amend_id = ftp_filer_filings.filing_sequence
  left join filer_ids on ftp_filer_filings.filer_id = filer_ids.filer_id
;

