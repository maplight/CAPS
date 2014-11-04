DROP TABLE IF EXISTS contributions_search_temp;
CREATE TABLE contributions_search_temp ENGINE=MYISAM
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
   TransactionDateStart,
   TransactionDateEnd,
   TransactionAmount,
   ElectionCycle,
   CandidateContribution,
   BallotMeasureContribution,
   0 AS RecipientCandidateNameID,
   0 AS RecipientCandidateOfficeID,
   0 AS TargetID,
   0 AS PositionID
FROM contributions_temp;

ALTER TABLE contributions_search_temp
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
  ADD INDEX TransactionDateStart(TransactionDateStart),
  ADD INDEX TransactionDateEnd(TransactionDateEnd),
  ADD INDEX ElectionCycle(ElectionCycle),
  ADD INDEX CandidateContribution(CandidateContribution),
  ADD INDEX BallotMeasureContribution(BallotMeasureContribution),
  ADD INDEX RecipientCandidateNameID(RecipientCandidateNameID),
  ADD INDEX RecipientCandidateOfficeID(RecipientCandidateOfficeID),
  ADD INDEX TargetID(TargetID),
  ADD INDEX PositionID(PositionID);


DROP TABLE IF EXISTS smry_candidates_temp;
CREATE TABLE smry_candidates_temp (
  RecipientCandidateNameID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10))
) ENGINE=MYISAM;
INSERT INTO smry_candidates_temp (RecipientCandidateNameNormalized) SELECT DISTINCT RecipientCandidateNameNormalized FROM contributions_temp WHERE RecipientCandidateNameNormalized <> '' AND CandidateContribution = 'Y';


DROP TABLE IF EXISTS smry_offices_temp;
CREATE TABLE smry_offices_temp (
  RecipientCandidateOfficeID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  KEY RecipientCandidateOffice(RecipientCandidateOffice(10))
) ENGINE=MYISAM;
INSERT INTO smry_offices_temp (RecipientCandidateOffice) SELECT DISTINCT RecipientCandidateOffice FROM contributions_temp WHERE RecipientCandidateOffice <> '' AND CandidateContribution = 'Y';


DROP TABLE IF EXISTS smry_propositions_temp;
CREATE TABLE smry_propositions_temp (
  TargetID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  Election DATE NOT NULL,
  Target VARCHAR(250) NOT NULL,
  KEY Election(Election),
  KEY Target(Target(10))
) ENGINE=MYISAM;
INSERT INTO smry_propositions_temp (Election, Target) SELECT DISTINCT Election, Target FROM contributions_temp WHERE Target <> '' AND Election <> '0000-00-00' AND BallotMeasureContribution = 'Y';


DROP TABLE IF EXISTS smry_cycles_temp;
CREATE TABLE smry_cycles_temp (
  ElectionCycle SMALLINT NOT NULL,
  KEY ElectionCycle(ElectionCycle)
) ENGINE=MYISAM;
INSERT INTO smry_cycles_temp SELECT DISTINCT ElectionCycle FROM contributions_temp;


UPDATE contributions_search_temp INNER JOIN smry_candidates_temp USING (RecipientCandidateNameNormalized) SET contributions_search_temp.RecipientCandidateNameID = smry_candidates_temp.RecipientCandidateNameID;
UPDATE contributions_search_temp INNER JOIN smry_offices_temp USING (RecipientCandidateOffice) SET contributions_search_temp.RecipientCandidateOfficeID = smry_offices_temp.RecipientCandidateOfficeID;
UPDATE contributions_search_temp INNER JOIN smry_propositions_temp ON (contributions_search_temp.Election = smry_propositions_temp.Election AND contributions_search_temp.Target = smry_propositions_temp.Target) SET contributions_search_temp.TargetID = smry_propositions_temp.TargetID;
UPDATE contributions_search_temp SET PositionID = 1 WHERE Position = 'SUPPORT'; 
UPDATE contributions_search_temp SET PositionID = 2 WHERE Position = 'OPPOSE'; 


DROP TABLE IF EXISTS smry_cycles;
RENAME TABLE smry_cycles_temp TO smry_cycles;

DROP TABLE IF EXISTS smry_propositions;
RENAME TABLE smry_propositions_temp TO smry_propositions;

DROP TABLE IF EXISTS smry_offices;
RENAME TABLE smry_offices_temp TO smry_offices;

DROP TABLE IF EXISTS smry_candidates;
RENAME TABLE smry_candidates_temp TO smry_candidates;

DROP TABLE IF EXISTS contributions_search;
RENAME TABLE contributions_search_temp TO contributions_search; 

DROP TABLE IF EXISTS contributions;
RENAME TABLE contributions_temp TO contributions;

