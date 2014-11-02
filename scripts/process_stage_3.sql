DROP TABLE IF EXISTS contributions_search_tmp;
CREATE TABLE contributions_search_tmp ENGINE=MYISAM
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

ALTER TABLE contributions_search_tmp
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

DROP TABLE IF EXISTS smry_candidates_tmp;
CREATE TABLE smry_candidates_tmp (
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10))
) ENGINE=MYISAM;
INSERT INTO smry_candidates_tmp SELECT DISTINCT RecipientCandidateNameNormalized FROM contributions WHERE RecipientCandidateNameNormalized <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS smry_offices_tmp;
CREATE TABLE smry_offices_tmp (
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  RecipientCandidateDistrict VARCHAR(50) NOT NULL,
  KEY RecipientCandidateOffice(RecipientCandidateOffice(10))
) ENGINE=MYISAM;
INSERT INTO smry_offices_tmp SELECT DISTINCT RecipientCandidateOffice, RecipientCandidateDistrict FROM contributions WHERE RecipientCandidateOffice <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS smry_propositions_tmp;
CREATE TABLE smry_propositions_tmp (
  Election DATE NOT NULL,
  Target VARCHAR(250) NOT NULL,
  KEY Election(Election),
  KEY Target(Target(10))
) ENGINE=MYISAM;
INSERT INTO smry_propositions_tmp SELECT DISTINCT Election, Target FROM contributions WHERE Target <> '' AND Election <> '0000-00-00' AND BallotMeasureContribution = 'Y';

DROP TABLE IF EXISTS smry_cycles_tmp;
CREATE TABLE smry_cycles_tmp (
  ElectionCycle SMALLINT NOT NULL,
  KEY ElectionCycle(ElectionCycle)
) ENGINE=MYISAM;
INSERT INTO smry_cycles_tmp SELECT DISTINCT ElectionCycle FROM contributions;


DROP TABLE IF EXISTS contributions_search;
RENAME TABLE contributions_search_tmp TO contributions_search; 

DROP TABLE IF EXISTS smry_candidates;
RENAME TABLE smry_candidates_tmp TO smry_candidates;

DROP TABLE IF EXISTS smry_offices;
RENAME TABLE smry_offices_tmp TO smry_offices;

DROP TABLE IF EXISTS smry_propositions;
RENAME TABLE smry_propositions_tmp TO smry_propositions;

DROP TABLE IF EXISTS smry_cycles;
RENAME TABLE smry_cycles_tmp TO smry_cycles;
