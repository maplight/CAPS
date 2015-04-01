DROP TABLE IF EXISTS california_data_office_codes;
CREATE TABLE california_data_office_codes (
  office_code_id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  office_cd_cvr CHAR(3) NULL,
  office_cd_501 CHAR(5) NULL,
  description VARCHAR(200) NOT NULL,
  region VARCHAR(50) NOT NULL DEFAULT '',
  UNIQUE KEY office_cd_cvr (office_cd_cvr),
  UNIQUE KEY office_cd_501 (office_cd_501),
  KEY description (description)
);

DROP TABLE IF EXISTS candidate_ids;
CREATE TABLE candidate_ids (
  candidate_id BIGINT NOT NULL PRIMARY KEY,
  number_of_names SMALLINT NOT NULL,
  last_session SMALLINT NOT NULL,
  candidate_name VARCHAR(100) NULL
);

DROP TABLE IF EXISTS candidate_sessions;
CREATE TABLE candidate_sessions (
  candidate_id BIGINT NOT NULL,
  session SMALLINT NOT NULL,
  office_501_code VARCHAR(5) NOT NULL DEFAULT '',
  office_501_custom VARCHAR(50) NOT NULL DEFAULT '',
  candidate_name VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY candidate_session (candidate_id, session)
);

DROP TABLE IF EXISTS contribution_ids;
CREATE TABLE contribution_ids (
  ContributionID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  FilingID BIGINT NOT NULL,
  AmendID INTEGER NOT NULL,
  LineItem INTEGER NOT NULL,
  RecType VARCHAR(5) NOT NULL DEFAULT '',
  Schedule VARCHAR(10) NOT NULL DEFAULT '',
  KEY FilingIDAmendIDLineItemRecTypeSchedule (FilingID, AmendID, LineItem, RecType, Schedule)
);

DROP TABLE IF EXISTS contributions_full;
CREATE TABLE contributions_full (
  TransactionType VARCHAR(100) NOT NULL DEFAULT '',
  Form VARCHAR(10) NOT NULL DEFAULT '',
  Schedule VARCHAR(10) NOT NULL DEFAULT '',
  ElectionCycle SMALLINT NOT NULL DEFAULT 0,
  ElectionCvr DATE DEFAULT NULL,
  ElectionProp DATE DEFAULT NULL,
  Election DATE DEFAULT NULL,
  PrimaryGeneralIndicator CHAR(1) NOT NULL,
  FilingID BIGINT NOT NULL,
  AmendID INTEGER NOT NULL,
  TransactionID VARCHAR(32) NOT NULL DEFAULT '',
  LineItem INTEGER NOT NULL,
  RecType VARCHAR(5) NOT NULL DEFAULT '',
  TranType VARCHAR(1) NOT NULL DEFAULT '',
  MemoRefNo VARCHAR(25) NOT NULL DEFAULT '',
  TransactionDateStart DATE NOT NULL,
  TransactionDateEnd DATE NOT NULL,
  DateThru DATE DEFAULT NULL,
  TransactionAmount DOUBLE NOT NULL DEFAULT 0,
  LoanPreExistingBalance DOUBLE NOT NULL DEFAULT 0,
  FiledDate DATETIME DEFAULT NULL,
  RecipientCommitteeID BIGINT NOT NULL DEFAULT 0,
  RecipientCommitteeEntity VARCHAR(5) DEFAULT NULL,
  RecipientCommitteeNameNormalized VARCHAR(200) NOT NULL,
  RecipientCommitteeTypeOriginal VARCHAR(1) NOT NULL DEFAULT '',
  RecipientCommitteeType VARCHAR(1) NOT NULL DEFAULT '',
  RecipientCommitteeTypeDescription VARCHAR(50) NOT NULL DEFAULT '',
  HasCandidateName ENUM('Y','N') DEFAULT 'N',
  RecipientCandidateID BIGINT NOT NULL DEFAULT 0,
  RecipientCandidateNameNormalizedOriginal VARCHAR(250) NOT NULL DEFAULT '',
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL DEFAULT '',
  RecipientCandidateParty VARCHAR(50) NOT NULL DEFAULT '',
  RecipientCandidateICO VARCHAR(10) NOT NULL DEFAULT '',
  RecipientCandidateStatus VARCHAR(40) NOT NULL DEFAULT '',
  RecipientCandidateOfficeCvrCode VARCHAR(3) NOT NULL DEFAULT '',
  RecipientCandidateOfficeCvrCustom VARCHAR(50) NOT NULL DEFAULT '',
  RecipientCandidateOfficeCvrSoughtOrHeld VARCHAR(1) NOT NULL DEFAULT '',
  RecipientCandidateOffice501Code VARCHAR(5) NOT NULL DEFAULT '',
  RecipientCandidateOffice501Custom VARCHAR(50) NOT NULL DEFAULT '',
  RecipientCandidateOfficeNeedsCleanup ENUM('Y','N') DEFAULT 'N',
  RecipientCandidateOffice VARCHAR(50) NOT NULL DEFAULT '',
  RecipientCandidateDistrict VARCHAR(50) NOT NULL DEFAULT '',
  HasProposition ENUM('Y','N') DEFAULT 'N',
  Target VARCHAR(250) NOT NULL DEFAULT '',
  Position VARCHAR(100) NOT NULL DEFAULT '',
  DonorNameNormalized VARCHAR(200) NOT NULL,
  DonorCity VARCHAR(30) NOT NULL DEFAULT '',
  DonorState CHAR(2) NOT NULL DEFAULT '',
  DonorZipCode VARCHAR(10) NOT NULL DEFAULT '',
  DonorEmployerNormalized VARCHAR(200) NOT NULL DEFAULT '',
  DonorOccupationNormalized VARCHAR(200) NOT NULL DEFAULT '',
  DonorOrganization VARCHAR(200) NOT NULL DEFAULT '',
  DonorIndustry VARCHAR(100) NOT NULL DEFAULT '',
  DonorCommitteeID BIGINT NOT NULL DEFAULT 0,
  DonorCommitteeEntity VARCHAR(5) NOT NULL DEFAULT '',
  DonorCommitteeNameNormalized VARCHAR(100) NOT NULL DEFAULT '',
  DonorCommitteeType VARCHAR(1) NOT NULL DEFAULT '',
  IntermediaryCommitteeID BIGINT NOT NULL DEFAULT 0,
  OriginTable VARCHAR(20) NOT NULL DEFAULT '',
  Unitemized ENUM('Y','N') DEFAULT 'N',
  IsEmployee ENUM('Y','N') DEFAULT 'N',
  IncludedSchedule ENUM('Y','N') DEFAULT 'Y',
  AlliedCommittee ENUM('Y','N') DEFAULT 'N',
  OfficeHolderCommittee ENUM('Y','N') DEFAULT 'N',
  LegalDefenseCommittee ENUM('Y','N') DEFAULT 'N',
  BallotMeasureCommittee ENUM('Y','N') DEFAULT 'N',
  CandidateControlledCommittee ENUM('Y','N') DEFAULT 'Y',
  CandidateElectionCommittee ENUM('Y','N') DEFAULT 'Y',
  StateOffice ENUM('Y','N') DEFAULT 'N',
  LocalOffice ENUM('Y','N') DEFAULT 'N',
  ForgivenLoan ENUM('Y','N') DEFAULT 'N',
  NoNewLoanAmount ENUM('Y','N') DEFAULT 'N',
  LateContributionCoveredByRegularFiling ENUM('Y','N') DEFAULT 'N',
  BadElectionCycle ENUM('Y','N') DEFAULT 'N',
  TransferNotOriginal ENUM('Y','N') DEFAULT 'N',
  CandidateContribution ENUM('Y','N') DEFAULT 'N',
  BallotMeasureContribution ENUM('Y','N') DEFAULT 'N',
  ContributionID BIGINT NOT NULL DEFAULT 0,
  id BIGINT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id),
  KEY RecipientCommitteeNameNormalized (RecipientCommitteeNameNormalized(10)),
  KEY RecipientCandidateOffice (RecipientCandidateOffice(10)),
  KEY FilingIDAmendID (FilingID, AmendID),
  KEY Form (Form),
  KEY Schedule (Schedule),
  KEY FiledDate (FiledDate)
);

DROP TABLE IF EXISTS disclosure_filer_ids;
CREATE TABLE disclosure_filer_ids (
  disclosure_filer_id BIGINT NOT NULL PRIMARY KEY COMMENT 'links to ftp_cvr_campaign_disclosure.filer_id',
  filer_id BIGINT DEFAULT NULL COMMENT 'links to ftp_filer_filings.filer_id',
  KEY filer_id (filer_id)
);

DROP TABLE IF EXISTS f501_502_cleaned;
CREATE TABLE f501_502_cleaned (
  filing_id BIGINT NOT NULL,
  amend_id INTEGER NOT NULL,
  form_type VARCHAR(4) NOT NULL,
  filer_id BIGINT NOT NULL,
  yr_of_elec SMALLINT NOT NULL,
  session SMALLINT NOT NULL,
  rpt_date DATETIME NOT NULL,
  office_cd VARCHAR(5) NOT NULL DEFAULT '',
  offic_dscr VARCHAR(50) NOT NULL DEFAULT '',
  cand_namf VARCHAR(50) NOT NULL DEFAULT '',
  cand_naml VARCHAR(50) NOT NULL DEFAULT '',
  PRIMARY KEY filing_amend (filing_id, amend_id),
  KEY filer_session_rptdate_filing_amend (filer_id, session, rpt_date, filing_id, amend_id)
);

DROP TABLE IF EXISTS filer_ids;
CREATE TABLE filer_ids (
  filer_id BIGINT NOT NULL PRIMARY KEY,
  max_rpt_end DATE NULL,
  candidate_id BIGINT NULL,
  candidate_name VARCHAR(100) NULL,
  KEY max_rpt_end (max_rpt_end)
);

DROP TABLE IF EXISTS filing_amends;
CREATE TABLE filing_amends (
  filing_id BIGINT NOT NULL,
  amend_id INTEGER NOT NULL,
  cand_naml VARCHAR(255) NOT NULL DEFAULT '',
  cand_namf VARCHAR(255) NOT NULL DEFAULT '',
  cand_namt VARCHAR(255) NOT NULL DEFAULT '',
  cand_nams VARCHAR(255) NOT NULL DEFAULT '',
  candidate_id BIGINT NULL,
  candidate_name VARCHAR(100) NOT NULL DEFAULT '',
  first_name VARCHAR(50) NOT NULL DEFAULT '',
  middle_name VARCHAR(50) NOT NULL DEFAULT '',
  last_name VARCHAR(50) NOT NULL DEFAULT '',
  name_suffix VARCHAR(50) NOT NULL DEFAULT '',
  name_prefix VARCHAR(50) NOT NULL DEFAULT '',
  nick_name VARCHAR(50) NOT NULL DEFAULT '',
  gender CHAR(1) NOT NULL DEFAULT '',
  display_name VARCHAR(200) NOT NULL DEFAULT '',
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  UNIQUE KEY filing_id_amend_id (filing_id, amend_id)
);

DROP TABLE IF EXISTS filing_ids;
CREATE TABLE filing_ids (
  filing_id BIGINT NOT NULL PRIMARY KEY,
  amend_id_to_use INTEGER NOT NULL,
  rcpt_amend_id INTEGER NULL,
  loan_amend_id INTEGER NULL,
  s497_amend_id INTEGER NULL,
  smry_amend_id INTEGER NULL,
  cvr_amend_id INTEGER NULL,
  loan_total_from_itemized DOUBLE NULL,
  loan_total_from_summary DOUBLE NULL
);

DROP TABLE IF EXISTS prop_filer_session_name_forms;
CREATE TABLE prop_filer_session_name_forms (
  filer_id BIGINT NOT NULL,
  session_id SMALLINT NOT NULL,
  filer_naml VARCHAR(255) NULL,
  form_type VARCHAR(10) NULL,
  positions TEXT,
  last_filing DATETIME DEFAULT NULL,
  KEY filer_id_session_id (filer_id, session_id)
);

DROP TABLE IF EXISTS prop_filer_sessions;
CREATE TABLE prop_filer_sessions (
  filer_id BIGINT NOT NULL,
  session_id SMALLINT NOT NULL,
  committee_name_to_use VARCHAR(255) NULL,
  PRIMARY KEY filer_id_session_id (filer_id, session_id)
);

DROP TABLE IF EXISTS table_filing_ids;
CREATE TABLE table_filing_ids (
  OriginTable VARCHAR(20) NOT NULL,
  filing_id BIGINT NOT NULL,
  amend_id INTEGER NOT NULL,
  PRIMARY KEY OriginTable_filing_id (OriginTable, filing_id)
);

DROP TABLE IF EXISTS ca_search.contributions;
CREATE TABLE ca_search.contributions (
  TransactionType VARCHAR(100) NOT NULL,
  ElectionCycle SMALLINT NOT NULL,
  Election DATE NOT NULL,
  TransactionDateStart DATE NOT NULL,
  TransactionDateEnd DATE NOT NULL,
  TransactionAmount DOUBLE NOT NULL,
  RecipientCommitteeID BIGINT NOT NULL,
  RecipientCommitteeNameNormalized VARCHAR(200) NOT NULL,
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  RecipientCandidateDistrict VARCHAR(50) NOT NULL,
  Target CHAR(250) NOT NULL,
  `Position` VARCHAR(100) NOT NULL,
  DonorNameNormalized VARCHAR(200) NOT NULL,
  DonorCity VARCHAR(30) NOT NULL,
  DonorState CHAR(2) NOT NULL,
  DonorZipCode VARCHAR(10) NOT NULL,
  DonorEmployerNormalized VARCHAR(200) NOT NULL,
  DonorOccupationNormalized VARCHAR(200) NOT NULL,
  DonorOrganization VARCHAR(200) NOT NULL,
  DonorCommitteeID BIGINT NOT NULL,
  Unitemized ENUM('Y','N') DEFAULT 'N',
  IsEmployee ENUM('Y','N') DEFAULT 'N',
  AlliedCommittee ENUM('Y','N') DEFAULT 'N' COMMENT 'formerly Flag',
  CandidateContribution ENUM('Y','N') DEFAULT 'N',
  BallotMeasureContribution ENUM('Y','N') DEFAULT 'N',
  ContributionID BIGINT NOT NULL,
  id BIGINT NOT NULL,
  PRIMARY KEY (id),
  KEY DonorNameNormalized (DonorNameNormalized(20)),
  KEY DonorEmployerNormalized (DonorEmployerNormalized(20)),
  KEY DonorOccupationNormalized (DonorOccupationNormalized(20)),
  KEY RecipientCandidateOffice (RecipientCandidateOffice(20)),
  KEY RecipientCandidateDistrict (RecipientCandidateDistrict(20)),
  KEY Election (Election),
  KEY Target (Target(20)),
  KEY RecipientCommitteeNameNormalized (RecipientCommitteeNameNormalized(20)),
  KEY RecipientCandidateNameNormalized (RecipientCandidateNameNormalized(20)),
  KEY ContributionID(ContributionID),
  KEY CandidateContribution(CandidateContribution),
  KEY ElectionCycle(ElectionCycle),
  KEY MultiIndex1(Election,Target(20)),
  KEY MultiIndex2(RecipientCommitteeID,RecipientCommitteeNameNormalized(20))
) ENGINE = MyISAM;

