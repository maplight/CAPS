<?php
//ini_set('auto_detect_line_endings', true);
# Setup the caps database / install script
# link in the needed libraries for scraping and connecting to the database
require("cal_access_scraper.inc");

Echo "Install started ...\n";

echo "Creating File Folder... \n";

# Create the files directory if it's not already there
if (!file_exists("files")) {
    mkdir("files", 0777, true);
}


echo "Installing cal_access.sql... \n";
# Create empty cal_access tables (used to store data scraped)
process_sql_file("install_cal_access.sql");

echo "Installing ftp_tables.sql... \n";
# Create empty ftp tables (used to store the data from ftp)
process_sql_file("install_ftp_tables.sql");

echo "Installing processing_tables.sql... \n";
# Create tables used to process the data
process_sql_file("install_processing_tables.sql");

echo "Installing smry_tables.sql... \n";
# Create tables used for fast web searches
process_sql_file("install_smry_tables.sql");

echo "Installing populated.sql... \n";
# Create populated tables (name parse tables and state name table)
process_sql_file("install_populated.sql");

# Make the files directory if it doesn't exist

echo "Populating all cal_access sessions from http://www.sos.ca.gov/elections/elections_cand.htm \n";
# Populate all cal_access sessions
get_elections_list();

echo "Scraping the cal-access data for all sessions (this process can take up to 8 - 10 hours) \n";
# Scrape the cal-access data for all sessions (this process can take up to 8 - 10 hours)
$query = "SELECT session FROM cal_access_sessions ORDER BY session";
$result = script_query($query);
while ($row = $result->fetch_assoc()) {
    $session = $row["session"];

    echo "Retrieving data for session " . $session . "\n";
    get_propositions($session, 1);
    get_propositions_committees($session, 1);
    get_candidate_names($session, 1);
    get_candidate_data($session, 1);
}

echo "check for missing proposition committes";
# check for missing proposition committes
$query = "SELECT cal_access_propositions_committees.session, cal_access_propositions_committees.filer_id FROM cal_access_propositions_committees
            LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_propositions_committees.filer_id AND cal_access_committees.session = cal_access_propositions_committees.session)
            WHERE ISNULL(cal_access_committees.filer_id)";
$result = script_query($query);
while ($row = $result->fetch_assoc()) {
    $session = $row["session"];
    $filer_id = $row["filer_id"];
    get_committee_information($session, $filer_id, 1);
}

echo "check for missing candidate committes";
# check for missing candidate committes
$query = "SELECT cal_access_candidates_committees.session, cal_access_candidates_committees.filer_id FROM cal_access_candidates_committees
            LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_candidates_committees.filer_id AND cal_access_committees.session = cal_access_candidates_committees.session)
            WHERE ISNULL(cal_access_committees.filer_id)";
$result = script_query($query);
while ($row = $result->fetch_assoc()) {
    $session = $row["session"];
    $filer_id = $row["filer_id"];
    get_committee_information($session, $filer_id, 1);
}

# Process an update - the processes the ftp data
system("php update_data.php");

echo "Install complete....";
#===============================================================================================
# load an sql file
function process_sql_file($filename)
{
    //  global $script_login, $script_pwd;
    //  system("mysql -u{$script_login} -p{$script_pwd} ca_process < \"$filename\"");

    $sql_contents = file_get_contents($filename);
    $sql_contents = rtrim(rtrim($sql_contents), ";");
    $sql_contents = preg_split("/;/", $sql_contents);

    foreach ($sql_contents as $query) {
        if($query){
            $result = script_query (trim($query));
            if (!$result)
            echo "Error on import of " . $query . "\n";
        }
    }
}

?>
