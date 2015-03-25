<?php
  require_once ("cal_access_scraper.inc");

  $query = "ALTER TABLE contributions_full
              ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
  script_query ($query);

  $query = "ALTER TABLE ca_search.contributions
              ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
  script_query ($query);
?>
