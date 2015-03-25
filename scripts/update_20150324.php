<?php
  require_once ("cal_access_scraper.inc");

  $query = "ALTER TABLE contributions_full
              CHANGE COLUMN RecipientCommitteeID BIGINT NOT NULL DEFAULT 0";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions
              ADD COLUMN RecipientCommitteeID BIGINT NOT NULL DEFAULT 0 AFTER TransactionAmount,
              ADD COLUMN DonorCommitteeID BIGINT NOT NULL DEFAULT 0 AFTER DonorOrganization";
  script_query ($query);

  $query = "ALTER TABLE ca_search.smry_candidates 
              CHANGE COLUMN RecipientCandidateNameID MapLightCandidateNameID BIGINT NOT NULL";
  script_query ($query);

?>


ALTER TABLE `ca_search`.`contributions_search` 
CHANGE COLUMN `RecipientCandidateNameID` `MapLightCandidateNameID` BIGINT(20) NOT NULL ,
CHANGE COLUMN `RecipientCandidateOfficeID` `MapLightCandidateOfficeID` BIGINT(20) NOT NULL ,
CHANGE COLUMN `RecipientCommitteeID` `MapLightCommitteeID` BIGINT(20) NOT NULL ;

ALTER TABLE `ca_search`.`smry_committees` 
CHANGE COLUMN `RecipientCommitteeID` `MapLightCommitteeID` BIGINT(20) NOT NULL ;

ALTER TABLE `ca_search`.`smry_offices` 
CHANGE COLUMN `RecipientCandidateOfficeID` `MapLightCandidateOfficeID` BIGINT(20) NOT NULL ;

