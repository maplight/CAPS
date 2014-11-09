DROP TABLE IF EXISTS smry_candidates_temp;
CREATE TABLE smry_candidates_temp LIKE smry_candidates;
INSERT INTO smry_candidates_temp (RecipientCandidateNameNormalized) SELECT DISTINCT RecipientCandidateNameNormalized FROM contributions_temp WHERE RecipientCandidateNameNormalized <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS smry_offices_temp;
CREATE TABLE smry_offices_temp LIKE smry_offices;
INSERT INTO smry_offices_temp (RecipientCandidateOffice) SELECT DISTINCT RecipientCandidateOffice FROM contributions_temp WHERE RecipientCandidateOffice <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS smry_committees_temp;
CREATE TABLE smry_committees_temp LIKE smry_committees;
INSERT INTO smry_committees_temp (RecipientCommitteeNameNormalized) SELECT DISTINCT RecipientCommitteeNameNormalized FROM contributions_temp WHERE RecipientCommitteeNameNormalized <> '';

DROP TABLE IF EXISTS smry_cycles_temp;
CREATE TABLE smry_cycles_temp LIKE smry_cycles;
INSERT INTO smry_cycles_temp SELECT DISTINCT ElectionCycle FROM contributions_temp;

DROP TABLE IF EXISTS smry_propositions_temp;
CREATE TABLE smry_propositions_temp LIKE smry_propositions;
INSERT INTO smry_propositions_temp (Election, Target) SELECT DISTINCT election_date, name FROM cal_access_propositions;

DROP TABLE IF EXISTS contributions_search_temp;
CREATE TABLE contributions_search_temp LIKE contributions_search;
INSERT INTO contributions_search_temp
  SELECT
   id,
   DonorState,
   AlliedCommittee,
   TransactionDateStart,
   TransactionDateEnd,
   TransactionAmount,
   ElectionCycle,
   CandidateContribution,
   BallotMeasureContribution,
   0,
   0,
   0,
   0,
   CASE Position
     WHEN 'SUPPORT' THEN 1
     WHEN 'OPPOSE' THEN 2
     ELSE 0
   END,
   ''
FROM contributions_temp;

UPDATE contributions_temp
  JOIN contributions_search_temp USING (id)
  JOIN smry_propositions_temp ON (contributions_temp.Election = smry_propositions_temp.Election AND contributions_temp.Target = smry_propositions_temp.Target)
  SET contributions_search_temp.PropositionID = smry_propositions_temp.PropositionID;

UPDATE contributions_temp
  JOIN contributions_search_temp USING (id)
  JOIN smry_committees_temp USING (RecipientCommitteeNameNormalized) 
  SET contributions_search_temp.RecipientCommitteeID = smry_committees_temp.RecipientCommitteeID;

UPDATE contributions_temp
  JOIN contributions_search_temp USING (id)
  JOIN smry_offices_temp USING (RecipientCandidateOffice)
  SET contributions_search_temp.RecipientCandidateOfficeID = smry_offices_temp.RecipientCandidateOfficeID;

UPDATE contributions_temp
  JOIN contributions_search_temp USING (id)
  JOIN smry_candidates_temp USING (RecipientCandidateNameNormalized)
  SET contributions_search_temp.RecipientCandidateNameID = smry_candidates_temp.RecipientCandidateNameID;
