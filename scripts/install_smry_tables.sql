DROP TABLE IF EXISTS ca_search.contributions_search;
CREATE TABLE ca_search.contributions_search (
  id BIGINT NOT NULL PRIMARY KEY,
  DonorState CHAR(2) NOT NULL,
  AlliedCommittee ENUM('Y','N'),
  TransactionDateStart DATE NOT NULL,
  TransactionDateEnd DATE NOT NULL,
  TransactionAmount DOUBLE NOT NULL,
  ElectionCycle SMALLINT NOT NULL,
  CandidateContribution ENUM('Y','N'),
  BallotMeasureContribution ENUM('Y','N'),
  RecipientCandidateNameID BIGINT NOT NULL,
  RecipientCandidateOfficeID BIGINT NOT NULL,
  RecipientCommitteeID BIGINT NOT NULL,
  PropositionID BIGINT NOT NULL,
  PositionID BIGINT NOT NULL,
  DonorWords VARCHAR(250) NOT NULL,
  KEY DonorState(DonorState),
  KEY AlliedCommittee(AlliedCommittee),
  KEY TransactionDateStart(TransactionDateStart),
  KEY TransactionDateEnd(TransactionDateEnd),
  KEY ElectionCycle(ElectionCycle),
  KEY CandidateContribution(CandidateContribution),
  KEY BallotMeasureContribution(BallotMeasureContribution),
  KEY RecipientCandidateNameID(RecipientCandidateNameID),
  KEY RecipientCandidateOfficeID(RecipientCandidateOfficeID),
  KEY RecipientCommitteeID(RecipientCommitteeID),
  KEY PropositionID(PropositionID),
  KEY PositionID(PositionID),
  FULLTEXT DonorWords(DonorWords)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_candidates;
CREATE TABLE ca_search.smry_candidates (
  RecipientCandidateNameID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  LastCycle SMALLINT NOT NULL,
  CandidateWords VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10)),
  KEY LastCycle(LastCycle),
  FULLTEXT CandidateWords(CandidateWords)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_propositions;
CREATE TABLE ca_search.smry_propositions (
  PropositionID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  Election DATE NOT NULL,
  Target VARCHAR(250) NOT NULL,
  PropositionWords VARCHAR(250) NOT NULL,
  KEY Election (Election),
  KEY Target (Target(10)),
  FULLTEXT PropositionWords(PropositionWords)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_committees;
CREATE TABLE ca_search.smry_committees (
  RecipientCommitteeID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCommitteeNameNormalized VARCHAR(200) NOT NULL,
  CommitteeWords VARCHAR(200) NOT NULL,
  KEY RecipientCommitteeNameNormalized(RecipientCommitteeNameNormalized(10)),
  FULLTEXT CommitteeWords(CommitteeWords)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_offices;
CREATE TABLE ca_search.smry_offices (
  RecipientCandidateOfficeID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  KEY RecipientCandidateOffice(RecipientCandidateOffice(10))
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_cycles;
CREATE TABLE ca_search.smry_cycles (
  ElectionCycle SMALLINT(6) NOT NULL,
  KEY ElectionCycle(ElectionCycle)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_last_update;
CREATE TABLE ca_search.smry_last_update (
  LastUpdate DATETIME NOT NULL
) ENGINE=MyISAM;

