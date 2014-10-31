<?php
  # Setup the caps database / install script
  require ("cal_access_scraper.inc");

  # Create empty cal_access tables (used to store data scraped)
  process_sql_file ("install_cal_access.sql");

  # Create empty ftp tables (used to store the data from ftp)
  process_sql_file ("install_ftp_tables.sql");

  # Create tables used to process the data
  process_sql_file ("install_processing_tables.sql");

  # Create populated tables (name parse tables and state name table)
  process_sql_file ("install_populated.sql");


  # Populate all cal_access sessions
  get_elections_list ();

  $query = "SELECT session FROM cal_access_sessions ORDER BY session";
  $result = my_query ($query);
  while ($row = $result->fetch_assoc()) {
    $session = $row["session"];

    get_propositions ($session, 1);
    get_propositions_committees ($session, 1);
    get_candidate_names ($session, 1);
    get_candidate_data ($session, 1);
  }

  # check for missing proposition committes
  $query = "SELECT cal_access_propositions_committees.session, cal_access_propositions_committees.filer_id FROM cal_access_propositions_committees
            LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_propositions_committees.filer_id AND cal_access_committees.session = cal_access_propositions_committees.session)
            WHERE ISNULL(cal_access_committees.filer_id)";
  $result = my_query ($query);
  while ($row = $result->fetch_assoc()) {
    $session = $row["session"];
    $filer_id = $row["filer_id"];
    get_committee_information ($session, $filer_id, 1);
  }
   
  # check for missing candidate committes
  $query = "SELECT cal_access_candidates_committees.session, cal_access_candidates_committees.filer_id FROM cal_access_candidates_committees
            LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_candidates_committees.filer_id AND cal_access_committees.session = cal_access_candidates_committees.session)
            WHERE ISNULL(cal_access_committees.filer_id)";
  $result = my_query ($query);
  while ($row = $result->fetch_assoc()) {
    $session = $row["session"];
    $filer_id = $row["filer_id"];
    get_committee_information ($session, $filer_id, 1);
  }

  # get the ftp data
  exec ("php get_ftp_data.php > /dev/null &");
    

#===============================================================================================
# load an sql file
  function process_sql_file ($filename) {
    system("mysql -ucaps -pcaps-dev14 ca_search < \"$filename\"");
  }
?>
