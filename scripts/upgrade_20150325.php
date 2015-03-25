<?php
  require_once ("cal_access_scraper.inc");

  $query = "ALTER TABLE contributions_full
              ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions
              ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions
              ADD INDEX DonorOccupationNormalized(DonorOccupationNormalized(10))";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions_search 
              DROP COLUMN DonorWords,
              DROP COLUMN DonorState,
              CHANGE COLUMN AlliedCommittee AlliedCommittee ENUM('Y','N') NOT NULL,
              CHANGE COLUMN CandidateContribution CandidateContribution ENUM('Y','N') NOT NULL,
              CHANGE COLUMN BallotMeasureContribution BallotMeasureContribution ENUM('Y','N') NOT NULL,
              ADD COLUMN IsEmployee ENUM('Y','N') NOT NULL AFTER BallotMeasureContribution,
              DROP INDEX DonorWords,
              DROP INDEX DonorState,
              ADD INDEX IsEmployee(IsEmployee)";
  script_query ($query);

  $query = "CREATE TABLE ca_search.contributions_search_donors (
              id BIGINT NOT NULL PRIMARY KEY,
              DonorState CHAR(2) NOT NULL,
              DonorWords VARCHAR(250) NOT NULL,
              KEY DonorState(DonorState),
              FULLTEXT DonorWords(DonorWords)
            ) ENGINE=MyISAM";
  script_query ($query);

  $query = "ALTER TABLE ca_search.smry_committees 
              ADD COLUMN RecipientCommitteeID BIGINT NULL AFTER RecipientCommitteeNameNormalized,
              ADD COLUMN DonorCommitteeID BIGINT NULL AFTER RecipientCommitteeID,
              ADD INDEX RecipientCommitteeID(RecipientCommitteeID),
              ADD INDEX DonorCommitteeID(DonorCommitteeID)";
  script_query ($query);
?>

