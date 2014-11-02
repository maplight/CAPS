
-- ------------------------------------------------------------------------------------------
-- create tables

drop table if exists table_filing_ids;
create table table_filing_ids (
    OriginTable varchar(20) not null
  , filing_id bigint(20) not null
  , amend_id int(11) not null
  , primary key OriginTable_filing_id (OriginTable, filing_id)
);

drop table if exists filing_ids;
create table filing_ids (
    filing_id bigint(20) not null primary key
  , amend_id_to_use int(11) not null
  , rcpt_amend_id int(11) null
  , loan_amend_id int(11) null
  , s497_amend_id int(11) null
  , smry_amend_id int(11) null
  , cvr_amend_id int(11) null
);

drop table if exists filer_ids;
create table filer_ids (
    filer_id bigint(20) primary key
  , max_rpt_end date null
  , candidate_id bigint(20) null
  , candidate_name varchar(100) null
  , key max_rpt_end (max_rpt_end)
);

-- formerly grp_ftp_disclosure_filer_id
drop table if exists disclosure_filer_ids;
create table disclosure_filer_ids (
    disclosure_filer_id bigint(20) not null primary key comment 'links to ftp_cvr_campaign_disclosure.filer_id'
  , filer_id bigint(20) default null comment 'links to ftp_filer_filings.filer_id'
  , key filer_id (filer_id)
);

drop table if exists candidate_ids;
create table candidate_ids (
    candidate_id bigint(20) not null primary key
  , number_of_names smallint(6) not null
  , last_session smallint(6) not null
  , candidate_name varchar(100) null
);

-- formerly grp_ftp_disclosure_candidate_name
drop table if exists filing_amends;
create table filing_amends (
    filing_id bigint(20) not null
  , amend_id int(11) not null
  , cand_naml varchar(255) not null default ''
  , cand_namf varchar(255) not null default ''
  , cand_namt varchar(255) not null default ''
  , cand_nams varchar(255) not null default ''
  , candidate_id bigint(20) null
  , candidate_name varchar(100) not null default ''
  , first_name varchar(50) not null default ''
  , middle_name varchar(50) not null default ''
  , last_name varchar(50) not null default ''
  , name_suffix varchar(50) not null default ''
  , name_prefix varchar(50) not null default ''
  , nick_name varchar(50) not null default ''
  , gender char(1) not null default ''
  , display_name varchar(200) not null default ''
  , primary key filing_id_amend_id (filing_id, amend_id)
); 

-- formerly grp_ftp_cal_access_filer_session_name_types
drop table if exists prop_filer_session_name_forms;
create table prop_filer_session_name_forms (
    filer_id bigint(20) not null
  , session_id smallint(6) not null
  , filer_naml varchar(255) null
  , form_type varchar(10) null
  , positions text
  , last_filing datetime default null
  , key filer_id_session_id (filer_id, session_id)
);

-- formerly grp_ftp_filer_sessions
drop table if exists prop_filer_sessions;
create table prop_filer_sessions (
    filer_id bigint(20) not null
  , session_id smallint(6) not null
  , committee_name_to_use varchar(255) null
  , primary key filer_id_session_id (filer_id, session_id)
);

drop table if exists contributions_full;
create table contributions_full (
    TransactionType varchar(100) not null default ''
  , Form varchar(10) not null default ''
  , Schedule varchar(10) not null default ''
  , ElectionCycle smallint(6) not null default 0
  , ElectionCvr date default null
  , ElectionProp date default null
  , Election date default null
  , PrimaryGeneralIndicator char(1) not null
  , FilingID bigint(20) not null
  , AmendID int(11) not null
  , TransactionID varchar(32) not null default ''
  , LineItem int(11) not null
  , MemoRefNo varchar(25) not null default ''
  , TransactionDateStart date not null
  , TransactionDateEnd date not null
  , TransactionAmount double not null default 0
  , LoanPreExistingBalance double not null default 0
  , FiledDate datetime default null
  , RecipientCommitteeID bigint(20) default null
  , RecipientCommitteeEntity varchar(5) default null
  , RecipientCommitteeNameNormalized varchar(200) not null
  , RecipientCommitteeTypeOriginal varchar(1) not null default ''
  , RecipientCommitteeType varchar(1) not null default ''
  , RecipientCommitteeTypeDescription varchar(50) not null default ''
  , HasCandidateName enum('Y','N') default 'N'
  , RecipientCandidateID bigint(20) default null
  , RecipientCandidateNameNormalizedOriginal varchar(250) not null default ''
  , RecipientCandidateNameNormalized varchar(250) not null default ''
  , RecipientCandidateParty varchar(50) not null default ''
  , RecipientCandidateICO varchar(10) not null default ''
  , RecipientCandidateStatus varchar(40) not null default ''
  , RecipientCandidateOfficeCode varchar(3) not null default ''
  , RecipientCandidateOfficeCustom varchar(50) not null default ''
  , RecipientCandidateOfficeOriginal varchar(50) not null default ''
  , RecipientCandidateOffice varchar(50) not null default ''
  , RecipientCandidateDistrict varchar(50) not null default ''
  , HasProposition enum('Y','N') default 'N'
  , Target varchar(250) not null default ''
  , `Position` varchar(100) not null default ''
  , DonorNameNormalized varchar(200) not null
  , DonorCity varchar(30) not null default ''
  , DonorState char(2) not null default ''
  , DonorZipCode varchar(10) not null default ''
  , DonorEmployerNormalized varchar(200) not null default ''
  , DonorOccupationNormalized varchar(200) not null default ''
  , DonorOrganization varchar(200) not null default ''
  , DonorIndustry varchar(100) not null default ''
  , DonorCommitteeID bigint(20) not null default 0
  , DonorCommitteeEntity varchar(5) not null default ''
  , DonorCommitteeNameNormalized varchar(100) not null default ''
  , DonorCommitteeType varchar(1) not null default ''
  , OriginTable varchar(20) not null default ''
  , Unitemized enum('Y','N') default 'N'
  , IncludedSchedule enum('Y','N') default 'Y'
  , AlliedCommittee enum('Y','N') default 'N'
  , OfficeHolderCommittee enum('Y','N') default 'N'
  , LegalDefenseCommittee enum('Y','N') default 'N'
  , BallotMeasureCommittee enum('Y','N') default 'N'
  , CandidateControlledCommittee enum('Y','N') default 'Y'
  , CandidateElectionCommittee enum('Y','N') default 'Y'
  , StateOffice enum('Y','N') default 'N'
  , LocalOffice enum('Y','N') default 'N'
  , ForgivenLoan enum('Y','N') default 'N'
  , NoNewLoanAmount enum('Y','N') default 'N'
  , LateContributionCoveredByRegularFiling enum('Y','N') default 'N'
  , BadElectionCycle enum('Y','N') default 'N'
  , CandidateContribution enum('Y','N') default 'N'
  , BallotMeasureContribution enum('Y','N') default 'N'
  , id bigint(20) not null auto_increment
  , primary key (id)
  , key RecipientCommitteeNameNormalized (RecipientCommitteeNameNormalized(10))
  , key RecipientCandidateOffice (RecipientCandidateOffice(10))
  , key FilingIDAmendID (FilingID, AmendID)
  , key Form (Form)
  , key Schedule (Schedule)
);

drop table if exists contributions;
create table contributions (
    TransactionType varchar(100) not null
  , ElectionCycle smallint(6) not null default 0
  , Election date default null
  , TransactionDateStart date not null
  , TransactionDateEnd date not null
  , TransactionAmount double not null
  , RecipientCommitteeNameNormalized varchar(200) not null
  , RecipientCandidateNameNormalized varchar(250) not null default ''
  , RecipientCandidateOffice varchar(50) not null default ''
  , RecipientCandidateDistrict varchar(50) not null default ''
  , Target varchar(250) not null default ''
  , `Position` varchar(100) not null default ''
  , DonorNameNormalized varchar(200) not null
  , DonorCity varchar(30) not null
  , DonorState char(2) not null
  , DonorZipCode varchar(10) not null
  , DonorEmployerNormalized varchar(200) not null
  , DonorOccupationNormalized varchar(200) not null
  , DonorOrganization varchar(200) not null
  , Unitemized enum('Y','N') default 'N'
  , AlliedCommittee enum('Y','N') default 'N' comment 'formerly Flag'
  , CandidateContribution enum('Y','N') default 'N'
  , BallotMeasureContribution enum('Y','N') default 'N'
  , id bigint(20) not null
  , primary key (id)
);

/*  The values in this table come from:
    http://www.sos.ca.gov/prd/cal-access/
    Cal-Access Documentation --> CalFormat --> cal_format_201.pdf --> page 12 (Office Codes)
*/
drop table if exists california_data_office_codes;
create table california_data_office_codes (
    office_code_id bigint(20) not null primary key auto_increment
  , office_cd char(3) not null
  , description varchar(200) not null
  , region varchar(50) not null
  , key office_cd (office_cd)
);
insert california_data_office_codes (office_cd, description, region)
select 'GOV', 'Governor', 'Statewide' union
select 'LTG', 'Lieutenant Governor', 'Statewide' union
select 'SOS', 'Secretary of State', 'Statewide' union
select 'CON', 'State Controller', 'Statewide' union
select 'ATT', 'Attorney General', 'Statewide' union
select 'TRE', 'State Treasurer', 'Statewide' union
select 'INS', 'Insurance Commissioner', 'Statewide' union
select 'SUP', 'Superintendent of Public Instruction', 'Statewide' union
select 'SPM', 'Supreme Court Justice', 'Statewide' union
select 'SEN', 'State Senate', 'State District' union
select 'ASM', 'State Assembly', 'State District' union
select 'BOE', 'Board of Equalization', 'State District' union
select 'PER', 'Public Employees Retirement System', 'State District' union
select 'APP', 'State Appellate Court Justice', 'State District' union
select 'ASR', 'Assessor', 'City/County/Local' union
select 'BED', 'Board of Education', 'City/County/Local' union
select 'BSU', 'Board of Supervisors', 'City/County/Local' union
select 'CAT', 'City Attorney', 'City/County/Local' union
select 'CCB', 'Community College Board', 'City/County/Local' union
select 'CCM', 'City Council Member', 'City/County/Local' union
select 'COU', 'County Counsel', 'City/County/Local' union
select 'CSU', 'County Supervisor', 'City/County/Local' union
select 'CTR', 'Local Controller', 'City/County/Local' union
select 'DAT', 'District Attorney', 'City/County/Local' union
select 'MAY', 'Mayor', 'City/County/Local' union
select 'PDR', 'Public Defender', 'City/County/Local' union
select 'PLN', 'Planning Commissioner', 'City/County/Local' union
select 'SHC', 'Sheriff-Coroner', 'City/County/Local' union
select 'SCJ', 'Superior Court Judge', 'City/County/Local' union
select 'TRS', 'Local Treasurer', 'City/County/Local' union
select 'OTH', 'Other', 'Miscellaneous/Other'
;

