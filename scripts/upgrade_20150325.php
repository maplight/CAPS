<?php
  require_once ("cal_access_scraper.inc");

  $query = "ALTER TABLE contributions_full
              ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions
              ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions_search 
              DROP COLUMN DonorWords,
              DROP COLUMN DonorState,
              CHANGE COLUMN AlliedCommittee AlliedCommittee ENUM('Y','N') NOT NULL,
              CHANGE COLUMN CandidateContribution CandidateContribution ENUM('Y','N') NOT NULL,
              CHANGE COLUMN BallotMeasureContribution BallotMeasureContribution ENUM('Y','N') NOT NULL,
              ADD COLUMN IsEmployee ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER BallotMeasureContribution,
              DROP INDEX DonorWords,
              DROP INDEX DonorState,
              ADD INDEX IsEmployee(IsEmployee)";
  script_query ($query);

  $query = "CREATE TABLE ca_search.contributions_search_donors (
              id BIGINT NOT NULL PRIMARY KEY,
              DonorState CHAR(2) NOT NULL,
              DonorCommitteeID BIGINT NOT NULL,
              DonorWords VARCHAR(250) NOT NULL,
              KEY DonorState(DonorState),
              KEY DonorCommitteeID(DonorCommitteeID),
              FULLTEXT DonorWords(DonorWords)
            ) ENGINE=MyISAM";
  script_query ($query);

  $query = "ALTER TABLE ca_search.smry_committees 
              ADD COLUMN RecipientCommitteeID BIGINT NOT NULL AFTER RecipientCommitteeNameNormalized,
              ADD INDEX RecipientCommitteeID(RecipientCommitteeID)";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions 
              DROP INDEX DonorNameNormalized,
              ADD INDEX DonorNameNormalized(DonorNameNormalized(20)),
              DROP INDEX DonorEmployerNormalized,
              ADD INDEX DonorEmployerNormalized(DonorEmployerNormalized(20)),
              DROP INDEX RecipientCandidateOffice,
              ADD INDEX RecipientCandidateOffice(RecipientCandidateOffice(20)),
              DROP INDEX RecipientCandidateDistrict,
              ADD INDEX RecipientCandidateDistrict(RecipientCandidateDistrict(20)),
              DROP INDEX Target,
              ADD INDEX Target(Target(20)),
              DROP INDEX RecipientCommitteeNameNormalized,
              ADD INDEX RecipientCommitteeNameNormalized(RecipientCommitteeNameNormalized(20)),
              DROP INDEX RecipientCandidateNameNormalized,
              ADD INDEX RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(20)),
              DROP INDEX DonorOccupationNormalized,
              ADD INDEX DonorOccupationNormalized(DonorOccupationNormalized(20)),
              ADD INDEX CandidateContribution(CandidateContribution),
              ADD INDEX ElectionCycle(ElectionCycle),
              ADD INDEX MultiIndex1(Election, Target(20)),
              ADD INDEX MultiIndex2(RecipientCommitteeID, RecipientCommitteeNameNormalized(20))";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions_grouped
              DROP INDEX ballot_measures,
              ADD INDEX ballot_measures(ballot_measures(20))";
  script_query ($query);

  $query = "ALTER TABLE ca_search.smry_propositions 
              ADD INDEX MultiIndex1(Election, Target(20))";
  script_query ($query);

  $query = "ALTER TABLE ca_search.smry_committees
              ADD INDEX MultiIndex1(RecipientCommitteeID, RecipientCommitteeNameNormalized(20))";
  script_query ($query);
?>
