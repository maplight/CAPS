<?php
//ini_set('memory_limit', '2028M');
# link in the needed libraries for scraping and connecting to the database
require("cal_access_scraper.inc");

# check to see if script is running, do not run if it is
$ps_check = trim(shell_exec("ps aux | grep 'php update_data.php' | grep -v grep | wc -l"));

echo $ps_check . "\n";

# If nothing is returned then probably not running in LINUX
if ($ps_check == "") {$ps_check = 1;}

# If script isn't currently running, process the data
if ($ps_check == "1") {
  echo "Starting update... \n";

  echo "Update the most recent cal_access session \n";
  system("php cal_access_data_scraper.php");

  echo "Get the ftp data \n";
  system("php get_ftp_data.php");

  echo "Process data for contributions table - stage 1 \n";
  process_sql_file("process_stage_1.sql");

  echo "Clean up names \n";
  # Clean up names
  clean_candidate_names();

  echo "Process data for contributions table - stage 2 \n";
  process_sql_file("process_stage_2.sql");

  echo "Process data for contributions table - stage 3 \n";
  process_sql_file("process_stage_3.sql");

  echo "generate search words \n";
  generate_search_words(); 

  echo "Reset last update file \n";
  $script_db->query("TRUNCATE ca_search.smry_last_update");
  $script_db->query("INSERT INTO ca_search.smry_last_update SELECT FiledDate FROM contributions_full WHERE FiledDate <= NOW() ORDER BY FiledDate DESC LIMIT 1");

  echo "Process data for contributions table - stage 4 \n";
  process_sql_file("process_stage_4.sql");

  echo "Update done... \n";
}

