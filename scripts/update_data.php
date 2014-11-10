<?php
  require ("../connect.php");

  # Update the most recent cal_access session
  system ("php cal_access_data_scraper.php");

  # Get the ftp data
  system ("php get_ftp_data.php");

  # Process data for contributions table - stage 1
  process_sql_file ("process_stage_1.sql");

  # Clean up names
  clean_candidate_names ();

  # Process data for contributions table - stage 2
  process_sql_file ("process_stage_2.sql");

  # Process data for contributions table - stage 3
  process_sql_file ("process_stage_3.sql");

  generate_search_words (); 

  # Reset last update file
  my_query ("TRUNCATE smry_last_update");
  my_query ("INSERT INTO smry_last_update VALUES (NOW())");

  # Process data for contributions table - stage 4
  process_sql_file ("process_stage_4.sql");


#===============================================================================================
# load an sql file
  function process_sql_file ($filename) {
    global $login, $pwd;
    system("mysql -u{$login} -p{$pwd} ca_search < \"$filename\"");
  }


#===============================================================================================
# Update special grouping tables
  function clean_candidate_names () {
    require_once ("name_parser.inc");

    $words_to_remove = array ();
    $result = my_query ("SELECT * FROM names_to_remove");
    while ($row = $result->fetch_assoc()) {$words_to_remove[] = $row["removal_word"];}

    $result = my_query ("SELECT * FROM filing_amends");
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
      if ($row["name"] != "") {$display_name = $name;}

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
                          WHERE filing_id = '{$row["filing_id"]}' AND amend_id = '{$row["amend_id"]}'";
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
                          WHERE filing_id = '{$row["filing_id"]}' AND amend_id = '{$row["amend_id"]}'";
      }
      my_query ($query);
    }
  }


  function generate_search_words () {
    $query = "SELECT id, DonorNameNormalized, DonorEmployerNormalized, DonorOrganization FROM contributions_temp";
    $result = my_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["DonorNameNormalized"] . " " . $row["DonorEmployerNormalized"] . " " . $row["DonorOrganization"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper ($word) . " ";}}
      my_query ("UPDATE contributions_search_temp SET DonorWords = '{$word_str}' WHERE id = {$row["id"]}");
    }
    $result->close();

    $query = "SELECT * FROM smry_candidates_temp";
    $result = my_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["RecipientCandidateNameNormalized"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper ($word) . " ";}}
      my_query ("UPDATE smry_candidates_temp SET CandidateWords = '{$word_str}' WHERE RecipientCandidateNameID = {$row["RecipientCandidateNameID"]}");
    }
    $result->close();

    $query = "SELECT * FROM smry_committees_temp";
    $result = my_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["RecipientCommitteeNameNormalized"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper ($word) . " ";}}
      my_query ("UPDATE smry_committees_temp SET CommitteeWords = '{$word_str}' WHERE RecipientCommitteeID = {$row["RecipientCommitteeID"]}");
    }
    $result->close();

    $query = "SELECT * FROM smry_propositions_temp";
    $result = my_query ($query);
    while ($row = $result->fetch_assoc()) {
      $word_str = " ";
      $word_data = preg_replace ("/[^a-z0-9 ]+/i", "", $row["Target"]);
      $words = explode (" ", $word_data);
      foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper ($word) . " ";}}
      my_query ("UPDATE smry_propositions_temp SET PropositionWords = '{$word_str}' WHERE PropositionID = {$row["PropositionID"]}");
    }
    $result->close();
  } 
?>
