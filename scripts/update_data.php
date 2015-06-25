<?php
//ini_set('memory_limit', '2028M');

# prepare a system lock file to determine if the script is running or not, exit if it is
$lockFile = fopen("update_data.pid", "c");
$gotLock = flock($lockFile, LOCK_EX | LOCK_NB, $wouldblock);  
if (!$gotLock && $wouldblock) {
  exit("Another instance is already running; terminating.\n");
}

# put the pid id in the file 
ftruncate($lockFile, 0);
fwrite($lockFile, getmypid() . "\n");

# link in the needed libraries for scraping and connecting to the database
require("cal_access_scraper.inc");

echo "Starting update... \n";

echo "Update the most recent cal_access session \n";
system("php cal_access_data_scraper.php");

echo "Get the ftp data \n";
system("php get_ftp_data.php");

echo "Process data for contributions table - stage 1 \n";
process_sql_file("process_stage_1.sql");

echo "Clean up names \n";
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

# release the lock file 
ftruncate($lockFile, 0);
flock($lockFile, LOCK_UN);
