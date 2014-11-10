DROP TABLE IF EXISTS ca_search.ca_search.smry_candidates_temp;
CREATE TABLE ca_search.ca_search.smry_candidates_temp LIKE ca_search.smry_candidates;
INSERT INTO ca_search.smry_candidates_temp (RecipientCandidateNameNormalized) SELECT DISTINCT RecipientCandidateNameNormalized FROM contributions_temp WHERE RecipientCandidateNameNormalized <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS ca_search.ca_search.smry_offices_temp;
CREATE TABLE ca_search.smry_offices_temp LIKE ca_search.smry_offices;
INSERT INTO ca_search.smry_offices_temp (RecipientCandidateOffice) SELECT DISTINCT RecipientCandidateOffice FROM contributions_temp WHERE RecipientCandidateOffice <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS ca_search.smry_committees_temp;
CREATE TABLE ca_search.smry_committees_temp LIKE ca_search.smry_committees;
INSERT INTO ca_search.smry_committees_temp (RecipientCommitteeNameNormalized) SELECT DISTINCT RecipientCommitteeNameNormalized FROM contributions_temp WHERE RecipientCommitteeNameNormalized <> '';

DROP TABLE IF EXISTS ca_search.smry_cycles_temp;
CREATE TABLE ca_search.smry_cycles_temp LIKE ca_search.smry_cycles;
INSERT INTO ca_search.smry_cycles_temp SELECT DISTINCT ElectionCycle FROM contributions_temp;

DROP TABLE IF EXISTS ca_search.smry_propositions_temp;
CREATE TABLE ca_search.smry_propositions_temp LIKE ca_search.smry_propositions;
INSERT INTO ca_search.smry_propositions_temp (Election, Target) SELECT DISTINCT election_date, name FROM cal_access_propositions;

DROP TABLE IF EXISTS ca_search.contributions_search_temp;
CREATE TABLE ca_search.contributions_search_temp LIKE ca_search.contributions_search;
INSERT INTO ca_search.contributions_search_temp
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
FROM ca_search.contributions_temp;

UPDATE ca_search.contributions_temp
  JOIN ca_search.contributions_search_temp USING (id)
  JOIN ca_search.smry_propositions_temp ON (contributions_temp.Election = smry_propositions_temp.Election AND contributions_temp.Target = smry_propositions_temp.Target)
  SET contributions_search_temp.PropositionID = smry_propositions_temp.PropositionID;

UPDATE ca_search.contributions_temp
  JOIN ca_search.contributions_search_temp USING (id)
  JOIN ca_search.smry_committees_temp USING (RecipientCommitteeNameNormalized) 
  SET contributions_search_temp.RecipientCommitteeID = smry_committees_temp.RecipientCommitteeID;

UPDATE ca_search.contributions_temp
  JOIN ca_search.contributions_search_temp USING (id)
  JOIN ca_search.smry_offices_temp USING (RecipientCandidateOffice)
  SET contributions_search_temp.RecipientCandidateOfficeID = smry_offices_temp.RecipientCandidateOfficeID;

UPDATE ca_search.contributions_temp
  JOIN ca_search.contributions_search_temp USING (id)
  JOIN ca_search.smry_candidates_temp USING (RecipientCandidateNameNormalized)
  SET contributions_search_temp.RecipientCandidateNameID = smry_candidates_temp.RecipientCandidateNameID;
