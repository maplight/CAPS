DROP TABLE IF EXISTS ca_search.contributions_search;

CREATE TABLE ca_search.contributions_search (
  id BIGINT NOT NULL PRIMARY KEY,
  AlliedCommittee ENUM('Y','N') NOT NULL,
  TransactionDateStart DATE NOT NULL,
  TransactionDateEnd DATE NOT NULL,
  TransactionAmount DOUBLE NOT NULL,
  ElectionCycle SMALLINT NOT NULL,
  CandidateContribution ENUM('Y','N') NOT NULL,
  BallotMeasureContribution ENUM('Y','N') NOT NULL,
  IsEmployee ENUM('Y','N') NOT NULL,
  MapLightCandidateNameID BIGINT NOT NULL,
  MapLightCandidateOfficeID BIGINT NOT NULL,
  MapLightCommitteeID BIGINT NOT NULL,
  PropositionID BIGINT NOT NULL,
  PositionID BIGINT NOT NULL,
  ContributionID BIGINT NOT NULL,
  KEY AlliedCommittee(AlliedCommittee),
  KEY TransactionDateStart(TransactionDateStart),
  KEY TransactionDateEnd(TransactionDateEnd),
  KEY ElectionCycle(ElectionCycle),
  KEY CandidateContribution(CandidateContribution),
  KEY BallotMeasureContribution(BallotMeasureContribution),
  KEY IsEmployee(IsEmployee),
  KEY MapLightCandidateNameID(MapLightCandidateNameID),
  KEY MapLightCandidateOfficeID(MapLightCandidateOfficeID),
  KEY MapLightCommitteeID(MapLightCommitteeID),
  KEY PropositionID(PropositionID),
  KEY PositionID(PositionID),
  KEY ContributionID(ContributionID)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.contributions_search_donors;

CREATE TABLE ca_search.contributions_search_donors (
  id BIGINT NOT NULL PRIMARY KEY,
  DonorState CHAR(2) NOT NULL,
  DonorCommitteeID BIGINT NULL,
  DonorWords VARCHAR(250) NOT NULL,
  KEY DonorState(DonorState),
  KEY DonorCommitteeID(DonorCommitteeID),
  FULLTEXT DonorWords(DonorWords)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.contributions_grouped;

CREATE TABLE ca_search.contributions_grouped (
  id BIGINT NOT NULL,
  ContributionID BIGINT NOT NULL,
  ballot_measures LONGTEXT NOT NULL,
  KEY id(id),
  KEY ContributionID(ContributionID),
  KEY ballot_measures(ballot_measures(20))
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_candidates;

CREATE TABLE ca_search.smry_candidates (
  MapLightCandidateNameID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  LastCycle SMALLINT NOT NULL,
  CandidateWords VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(20)),
  KEY LastCycle(LastCycle),
  FULLTEXT CandidateWords(CandidateWords)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_committees;

CREATE TABLE ca_search.smry_committees (
  MapLightCommitteeID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCommitteeNameNormalized VARCHAR(200) NOT NULL,
  RecipientCommitteeID BIGINT NULL,
  CommitteeWords VARCHAR(250) NOT NULL,
  KEY RecipientCommitteeNameNormalized(RecipientCommitteeNameNormalized(20)),
  KEY RecipientCommitteeID(RecipientCommitteeID),
  KEY MultiIndex1(RecipientCommitteeID,RecipientCommitteeNameNormalized(20)),
  FULLTEXT CommitteeWords(CommitteeWords)
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

DROP TABLE IF EXISTS ca_search.smry_offices;

CREATE TABLE ca_search.smry_offices (
  MapLightCandidateOfficeID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  KEY RecipientCandidateOffice(RecipientCandidateOffice(20))
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ca_search.smry_propositions;

CREATE TABLE ca_search.smry_propositions (
  PropositionID BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  Election DATE NOT NULL,
  Target VARCHAR(250) NOT NULL,
  PropositionWords VARCHAR(250) NOT NULL,
  KEY Election (Election),
  KEY Target (Target(20)),
  KEY MultiIndex1(Election,Target(20)),
  FULLTEXT PropositionWords(PropositionWords)
) ENGINE=MyISAM;
