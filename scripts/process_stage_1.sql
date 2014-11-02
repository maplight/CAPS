
-- ------------------------------------------------------------------------------------------
-- populate tables (before name cleaning)

truncate table filer_ids;
insert filer_ids (filer_id, max_rpt_end)
select filer_id
  , max(str_to_date(
      case
        when (form_id = 'F460' and rpt_end <> '' and str_to_date(rpt_end,'%m/%d/%Y') <= curdate()) then rpt_end
        else null
        end
    ,'%m/%d/%Y'
    )) 'max_rpt_end'
from california_data.ftp_filer_filings 
group by filer_id
;

truncate table table_filing_ids;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'rcpt', filing_id, max(amend_id) 
from california_data.ftp_rcpt 
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'loan', filing_id, max(amend_id) 
from california_data.ftp_loan 
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 's497', filing_id, max(amend_id) 
from california_data.ftp_s497 
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'smry', filing_id, max(amend_id) as amend_id_to_use
from california_data.ftp_smry
group by filing_id
;
insert table_filing_ids (OriginTable, filing_id, amend_id)
select 'cvr', filing_id, max(amend_id) as amend_id_to_use
from california_data.ftp_cvr_campaign_disclosure
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

-- FILER_XREF: This table maps legacy filer identification numbers to the systems filer identification numbers. -from CalAccessTablesWeb.pdf
truncate table disclosure_filer_ids;
insert disclosure_filer_ids (disclosure_filer_id, filer_id)
select
    ftp_cvr_campaign_disclosure.filer_id as disclosure_filer_id
  , max(ifnull(ftp_filer_xref.filer_id, ftp_cvr_campaign_disclosure.filer_id)) as filer_id
from 
  california_data.ftp_cvr_campaign_disclosure
  left join california_data.ftp_filer_xref 
    on ftp_cvr_campaign_disclosure.filer_id = ftp_filer_xref.xref_id 
    and ftp_filer_xref.xref_id <> 0
group by ftp_cvr_campaign_disclosure.filer_id
;

truncate table candidate_ids;
insert candidate_ids (candidate_id, number_of_names, last_session)
select 
    id
  , count(distinct name) 'number_of_names'
  , max(session)
from california_data.cal_access_candidates
group by id
;

update 
  candidate_ids a
  join california_data.cal_access_candidates b on a.candidate_id = b.id and a.last_session = b.session
set a.candidate_name = b.name
;

update
  filer_ids a
  join (
    select filer_id, max(id) 'id'
    from california_data.cal_access_candidates_committees
    group by filer_id
    ) b using (filer_id)
set a.candidate_id = b.id
;

update
  filer_ids a
  join candidate_ids b using (candidate_id)
set a.candidate_name = b.candidate_name
;

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
  california_data.ftp_cvr_campaign_disclosure
  join disclosure_filer_ids on ftp_cvr_campaign_disclosure.filer_id = disclosure_filer_ids.disclosure_filer_id
  left join california_data.ftp_filer_filings 
    on disclosure_filer_ids.filer_id = ftp_filer_filings.filer_id
    and ftp_cvr_campaign_disclosure.filing_id = ftp_filer_filings.filing_id
    and ftp_cvr_campaign_disclosure.form_type = ftp_filer_filings.form_id
    and ftp_cvr_campaign_disclosure.amend_id = ftp_filer_filings.filing_sequence
  left join filer_ids on ftp_filer_filings.filer_id = filer_ids.filer_id
;

