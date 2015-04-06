<?php
  require_once ("cal_access_scraper.inc");

  $query = "ALTER TABLE ca_search.contributions_search_donors 
              CHANGE COLUMN DonorCommitteeID DonorCommitteeID BIGINT NULL";
  script_query ($query);

  $query = "UPDATE ca_search.contributions_search_donors SET DonorCommitteeID = NULL WHERE DonorCommitteeID = 0";
  script_query ($query);

  $query = "ALTER TABLE ca_search.smry_committees 
              CHANGE COLUMN RecipientCommitteeID RecipientCommitteeID BIGINT NULL";
  script_query ($query);

  $query = "UPDATE ca_search.smry_committees SET RecipientCommitteeID = NULL WHERE RecipientCommitteeID = 0";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions 
              CHANGE COLUMN ElectionCycle ElectionCycle SMALLINT NOT NULL,
              CHANGE COLUMN RecipientCommitteeID RecipientCommitteeID BIGINT NULL,
              CHANGE COLUMN DonorCommitteeID DonorCommitteeID BIGINT NULL,
              CHANGE COLUMN Unitemized Unitemized ENUM('Y','N') NOT NULL DEFAULT 'N',
              CHANGE COLUMN IsEmployee IsEmployee ENUM('Y','N') NOT NULL DEFAULT 'N',
              CHANGE COLUMN AlliedCommittee AlliedCommittee ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'formerly Flag',
              CHANGE COLUMN CandidateContribution CandidateContribution ENUM('Y','N') NOT NULL DEFAULT 'N',
              CHANGE COLUMN BallotMeasureContribution BallotMeasureContribution ENUM('Y','N') NOT NULL DEFAULT 'N',
              CHANGE COLUMN ContributionID ContributionID BIGINT NOT NULL";
  script_query ($query);

  $query = "UPDATE ca_search.contributions SET DonorCommitteeID = NULL WHERE DonorCommitteeID = 0";
  script_query ($query);

  $query = "UPDATE ca_search.contributions SET RecipientCommitteeID = NULL WHERE RecipientCommitteeID = 0";
  script_query ($query);
?>