
















DROP TABLE IF EXISTS contributions_search;
CREATE TABLE contributions_search ENGINE=MYISAM
  SELECT
   id,
   DonorNameNormalized,
   DonorEmployerNormalized,
   DonorOrganization,
   DonorState,
   RecipientCandidateNameNormalized,
   RecipientCandidateOffice,
   Election,
   Target,
   Position,
   AlliedCommittee,
   RecipientCommitteeNameNormalized,
   TransactionDate,
   TransactionAmount,
   ElectionCycle,
   CandidateContribution,
   BallotMeasureContribution
FROM contributions;

ALTER TABLE contributions_search
  ADD FULLTEXT DonorSearch(DonorNameNormalized, DonorEmployerNormalized, DonorOrganization),
  ADD INDEX DonorState(DonorState),
  ADD FULLTEXT RecipientCandidateNameNormalized_fulltext(RecipientCandidateNameNormalized),
  ADD INDEX RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10)),
  ADD INDEX RecipientCandidateOffice(RecipientCandidateOffice(10)),
  ADD INDEX Election(Election),
  ADD FULLTEXT Target_fulltext(Target),
  ADD INDEX Target(Target(10)),
  ADD INDEX `Position`(`Position`(10)),
  ADD INDEX AlliedCommittee(AlliedCommittee),
  ADD FULLTEXT RecipientCommitteeNameNormalized(RecipientCommitteeNameNormalized),
  ADD INDEX TransactionDate(TransactionDate),
  ADD INDEX ElectionCycle(ElectionCycle),
  ADD INDEX CandidateContribution(CandidateContribution),
  ADD INDEX BallotMeasureContribution(BallotMeasureContribution);


DROP TABLE IF EXISTS smry_candidates;
CREATE TABLE smry_candidates (
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10))
) ENGINE=MYISAM;
INSERT INTO smry_candidates SELECT DISTINCT RecipientCandidateNameNormalized FROM contributions WHERE RecipientCandidateNameNormalized <> '' AND CandidateContribution = 'Y';


DROP TABLE IF EXISTS smry_offices;
CREATE TABLE smry_offices (
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  RecipientCandidateDistrict VARCHAR(50) NOT NULL,
  KEY RecipientCandidateOffice(RecipientCandidateOffice(10))
) ENGINE=MYISAM;
INSERT INTO smry_offices SELECT DISTINCT RecipientCandidateOffice, RecipientCandidateDistrict FROM contributions WHERE RecipientCandidateOffice <> '';


DROP TABLE IF EXISTS smry_propositions;
CREATE TABLE smry_propositions (
  Election DATE NOT NULL,
  Target VARCHAR(250) NOT NULL,
  KEY Election(Election),
  KEY Target(Target(10))
) ENGINE=MYISAM;
INSERT INTO smry_propositions SELECT DISTINCT Election, Target FROM contributions WHERE Target <> '' AND Election <> '0000-00-00';


DROP TABLE IF EXISTS smry_cycles;
CREATE TABLE smry_cycles (
  ElectionCycle SMALLINT NOT NULL,
  KEY ElectionCycle(ElectionCycle)
) ENGINE=MYISAM;
INSERT INTO smry_cycles SELECT DISTINCT ElectionCycle FROM contributions;

