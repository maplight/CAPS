<?php
  require ("../connect.php");
  //ini_set('memory_limit', '2028M');
  $script_conn = mysqli_init ();
  mysqli_options ($script_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect ($script_conn, $hostname, $script_login, $script_pwd, "ca_process");

 echo "Starting update... \n";

 echo "Update the most recent cal_access session \n";
  # Update the most recent cal_access session
  system ("php cal_access_data_scraper.php");

  echo "Get the ftp data \n";
  # Get the ftp data
  system ("php get_ftp_data.php");

  echo "Process data for contributions table - stage 1 \n";
  # Process data for contributions table - stage 1
  process_sql_file ("process_stage_1.sql");

  echo "Clean up names \n";
  # Clean up names
  clean_candidate_names ();

  echo "Process data for contributions table - stage 2 \n";
  # Process data for contributions table - stage 2
  process_sql_file ("process_stage_2.sql");

  echo "Process data for contributions table - stage 3 \n";
  # Process data for contributions table - stage 3
  process_sql_file ("process_stage_3.sql");

  echo "generate search words \n";
  generate_search_words (); 

  echo "Reset last update file \n";
  # Reset last update file
  script_query ("TRUNCATE ca_search.smry_last_update");
  script_query ("INSERT INTO ca_search.smry_last_update VALUES (NOW())");

  echo "Process data for contributions table - stage 4 \n";
  # Process data for contributions table - stage 4
  process_sql_file ("process_stage_4.sql");

  echo "Update done... \n";

#===============================================================================================
# process script query
  function script_query ($query) {
    global $script_conn;
    $ret = $script_conn->query ($query);
    return $ret;
  }


#===============================================================================================
# load an sql file
function process_sql_file($filename)
{
    $sql_contents = file_get_contents($filename);
    $sql_contents = rtrim(rtrim($sql_contents), ";");
    $sql_contents = preg_split("/;(\n|\r)/", $sql_contents);

    foreach ($sql_contents as $query) {

        if($query){
            $result = script_query (trim($query));
            if (!$result)
                echo "Error on import of " . $query . "\n";
        }
    }
}


#===============================================================================================
# Update special grouping tables
  function clean_candidate_names () {
    require_once ("name_parser.inc");

    $words_to_remove = array ();
    $result = script_query ("SELECT * FROM names_to_remove");
    while ($row = $result->fetch_assoc()) {$words_to_remove[] = $row["removal_word"];}

    $result = script_query ("SELECT * FROM filing_amends WHERE cand_naml <> '' OR cand_nams <> '' OR cand_namt <> '' OR cand_namf <> '' OR candidate_name <> ''");
    while ($row = $result->fetch_assoc()) {
      if ($row["candidate_name"] == "") {
        $name = $row["cand_naml"] . " " . $row["cand_nams"] . ", " . $row["cand_namt"] . " " . $row["cand_namf"];
      } else {
        $name = $row["candidate_name"];
      }

      $name = trim (str_replace (array ("0", "1", "2", "3", "4", "5", "6", "7", "8", "9"), "", $name));
      $parsed_name = parse_name ($name);
      $first_name = strtoupper ($parsed_name["first_name"]);
      $last_name = strtoupper ($parsed_name["last_name"]);
      $middle_name = strtoupper ($parsed_name["middle_name"]);
      $nick_name = strtoupper ($parsed_name["nick_name"]);
      $name_suffix = strtoupper ($parsed_name["suffix"]);
      $name_prefix = strtoupper ($parsed_name["prefix"]);

      if ($middle_name . " " . $last_name == strtoupper ($row["cand_naml"])) {
        $last_name = $middle_name . " " . $last_name;
        $middle_name = "";
      }

      if ($last_name <> "") {$ln = "$last_name";} else {$ln = "";}
      if ($name_suffix <> "") {$ns = " $name_suffix";} else {$ns = "";}
      if ($first_name <> "") {$fn = ", $first_name";} else {$fn = "";}
      if ($middle_name <> "") {$mn = " $middle_name";} else {$mn = "";}
      $display_name = trim ($ln . $ns . $fn . $mn);
      $gender = $parsed_name["gender"];
      if ($row["display_name"] != "") {$display_name = $name;}

      $removal_word_found = false;
      foreach ($words_to_remove as $removal_word) {
        if (strpos (" " . strtoupper ($name) . " ", " " . $removal_word . " ") !== false) {$removal_word_found = true;}
      }

      if ($removal_word_found) {
        $query = "UPDATE filing_amends
                            SET gender = '',
                            first_name = '',
                            middle_name = '',
                            last_name = '',
                            name_suffix = '',
                            name_prefix = '',
                            nick_name = '',
                            display_name = ''
                          WHERE id = {$row["id"]}";
      } else {
        $query = "UPDATE filing_amends
                            SET gender = '$gender',
                            first_name = '" . addslashes ($first_name) . "',
                            middle_name = '" . addslashes ($middle_name) . "',
                            last_name = '" . addslashes ($last_name) . "',
                            name_suffix = '" . addslashes ($name_suffix) . "',
                            name_prefix = '" . addslashes ($name_prefix) . "',
                            nick_name = '" . addslashes ($nick_name) . "',
                            display_name = '" . addslashes ($display_name) . "'
                          WHERE id = {$row["id"]}";
      }
      script_query ($query);
    }
  }


  function generate_search_words () {
    $query = "SELECT id, DonorNameNormalized, DonorEmployerNormalized, DonorOrganization FROM ca_search.contributions_temp";
    $result = script_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["DonorNameNormalized"] . " " . $row["DonorEmployerNormalized"] . " " . $row["DonorOrganization"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper (ltrim ($word, "0")) . " ";}}
      script_query ("UPDATE ca_search.contributions_search_temp SET DonorWords = '{$word_str}' WHERE id = {$row["id"]}");
    }
    $result->close();

    $query = "SELECT * FROM ca_search.smry_candidates_temp";
    $result = script_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["RecipientCandidateNameNormalized"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper (ltrim ($word, "0")) . " ";}}
      script_query ("UPDATE ca_search.smry_candidates_temp SET CandidateWords = '{$word_str}' WHERE RecipientCandidateNameID = {$row["RecipientCandidateNameID"]}");
    }
    $result->close();

    $query = "SELECT * FROM ca_search.smry_committees_temp";
    $result = script_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["RecipientCommitteeNameNormalized"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper (ltrim ($word, "0")) . " ";}}
      script_query ("UPDATE ca_search.smry_committees_temp SET CommitteeWords = '{$word_str}' WHERE RecipientCommitteeID = {$row["RecipientCommitteeID"]}");
    }
    $result->close();

    $query = "SELECT * FROM ca_search.smry_propositions_temp";
    $result = script_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["Target"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper (ltrim ($word, "0")) . " ";}}
      script_query ("UPDATE ca_search.smry_propositions_temp SET PropositionWords = '{$word_str}' WHERE PropositionID = {$row["PropositionID"]}");
    }
    $result->close();
  } 
?>
