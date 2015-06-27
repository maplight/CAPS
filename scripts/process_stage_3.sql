DROP TABLE IF EXISTS ca_search.smry_candidates_temp;

CREATE TABLE ca_search.smry_candidates_temp LIKE ca_search.smry_candidates;

INSERT INTO ca_search.smry_candidates_temp (RecipientCandidateNameNormalized, LastCycle) SELECT RecipientCandidateNameNormalized, MAX(ElectionCycle) AS LastCycle FROM ca_search.contributions_temp WHERE RecipientCandidateNameNormalized <> '' AND CandidateContribution = 'Y' GROUP BY RecipientCandidateNameNormalized;

DROP TABLE IF EXISTS ca_search.smry_offices_temp;

CREATE TABLE ca_search.smry_offices_temp LIKE ca_search.smry_offices;

INSERT INTO ca_search.smry_offices_temp (RecipientCandidateOffice) SELECT DISTINCT RecipientCandidateOffice FROM ca_search.contributions_temp WHERE RecipientCandidateOffice <> '' AND CandidateContribution = 'Y';

DROP TABLE IF EXISTS ca_search.smry_committees_temp;

CREATE TABLE ca_search.smry_committees_temp LIKE ca_search.smry_committees;

INSERT INTO ca_search.smry_committees_temp (RecipientCommitteeNameNormalized, RecipientCommitteeID) SELECT DISTINCT RecipientCommitteeNameNormalized, RecipientCommitteeID FROM ca_search.contributions_temp WHERE RecipientCommitteeNameNormalized <> '';

DROP TABLE IF EXISTS ca_search.smry_cycles_temp;

CREATE TABLE ca_search.smry_cycles_temp LIKE ca_search.smry_cycles;

INSERT INTO ca_search.smry_cycles_temp SELECT DISTINCT ElectionCycle FROM ca_search.contributions_temp;

DROP TABLE IF EXISTS ca_search.smry_propositions_temp;

CREATE TABLE ca_search.smry_propositions_temp LIKE ca_search.smry_propositions;

INSERT INTO ca_search.smry_propositions_temp (Election, Target) SELECT DISTINCT election_date, name FROM cal_access_propositions;

DROP TABLE IF EXISTS ca_search.contributions_search_temp;

CREATE TABLE ca_search.contributions_search_temp LIKE ca_search.contributions_search;

INSERT INTO ca_search.contributions_search_temp
  SELECT
   id,
   AlliedCommittee,
   TransactionDateStart,
   TransactionDateEnd,
   TransactionAmount,
   ElectionCycle,
   CandidateContribution,
   BallotMeasureContribution,
   IsEmployee,
   0,
   0,
   0,
   0,
   CASE Position
     WHEN 'SUPPORT' THEN 1
     WHEN 'OPPOSE' THEN 2
     ELSE 0
   END,
   ContributionID
FROM ca_search.contributions_temp;

UPDATE ca_search.contributions_temp
  INNER JOIN ca_search.contributions_search_temp USING (id)
  INNER JOIN ca_search.smry_propositions_temp ON (contributions_temp.Election = smry_propositions_temp.Election AND contributions_temp.Target = smry_propositions_temp.Target)
SET contributions_search_temp.PropositionID = smry_propositions_temp.PropositionID;

UPDATE ca_search.contributions_temp
  INNER JOIN ca_search.contributions_search_temp USING (id)
  INNER JOIN ca_search.smry_committees_temp ON (contributions_temp.RecipientCommitteeID = smry_committees_temp.RecipientCommitteeID AND contributions_temp.RecipientCommitteeNameNormalized = smry_committees_temp.RecipientCommitteeNameNormalized) 
SET contributions_search_temp.MapLightCommitteeID = smry_committees_temp.MapLightCommitteeID;

UPDATE ca_search.contributions_temp
  INNER JOIN ca_search.contributions_search_temp USING (id)
  INNER JOIN ca_search.smry_offices_temp USING (RecipientCandidateOffice)
SET contributions_search_temp.MapLightCandidateOfficeID = smry_offices_temp.MapLightCandidateOfficeID;

UPDATE ca_search.contributions_temp
  INNER JOIN ca_search.contributions_search_temp USING (id)
  INNER JOIN ca_search.smry_candidates_temp USING (RecipientCandidateNameNormalized)
SET contributions_search_temp.MapLightCandidateNameID = smry_candidates_temp.MapLightCandidateNameID;

DROP TABLE IF EXISTS ca_search.contributions_grouped_temp;

CREATE TABLE ca_search.contributions_grouped_temp LIKE ca_search.contributions_grouped;

INSERT INTO ca_search.contributions_grouped_temp
  SELECT
    id,
    ContributionID,
    IF(NOT ISNULL(Target), GROUP_CONCAT(CONCAT(IF(PositionID = 1, 'SUPPORTED', IF(PositionID = 2, 'OPPOSED', '')), ': ', Target) SEPARATOR ' | '), '')
  FROM ca_search.contributions_search_temp
    LEFT JOIN ca_search.smry_propositions_temp USING (PropositionID)
  GROUP BY ContributionID
  ORDER BY Target;

DROP TABLE IF EXISTS ca_search.contributions_search_donors_temp;

CREATE TABLE ca_search.contributions_search_donors_temp LIKE ca_search.contributions_search_donors;
