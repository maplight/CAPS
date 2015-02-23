<?php
// date_default_timezone_set('America/Los_Angeles'); <--- Set date optional

  require ("simple_html_dom.php");
  require ("../connect.php");
  $script_conn = mysqli_init ();
  mysqli_options ($script_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect ($script_conn, $hostname, $script_login, $script_pwd, "ca_process");


#===============================================================================================
# process script query
  function script_query ($query) {
    global $script_conn;
    $ret = $script_conn->query (str_replace (array ("\\'", "\\\"", "\\n", "\\\\"), array ("'", "\"", "\n", "\\"), mysqli_real_escape_string ($script_conn, $query)));
    return $ret;
  }


#===============================================================================================
# update elections list
  function get_elections_list () {
    # Check upcoming elections
    $html_page = get_html_file ("http://www.sos.ca.gov/elections/upcoming-elections");
    if (! $html_page) {} else {
      foreach ($html_page->find ("li") as $list_item) {
        $election_data = parse_election_data ($list_item->plaintext);
        if ($election_data["election_date"] != "") {
          if ($election_data["election_id"] == 0) {script_query ("INSERT INTO cal_access_elections (date, description, special, election_type, office) VALUES ('{$election_data["election_date"]}', '{$election_data["title"]}', {$election_data["special"]}, '{$election_data["election_type"]}', '{$election_data["office"]}')");}
        }
      }
    }

    # Check prior statewide elections
    $html_page = get_html_file ("http://www.sos.ca.gov/elections/prior-elections/statewide-election-results");
    if (! $html_page) {} else {
      foreach ($html_page->find ("li") as $list_item) {
        $election_data = parse_election_data ($list_item->plaintext);
        if ($election_data["election_date"] != "") {
          if ($election_data["election_id"] == 0) {
            script_query ("INSERT INTO cal_access_elections (date, description, special, election_type, office) VALUES ('{$election_data["election_date"]}', '{$election_data["title"]}', {$election_data["special"]}, '{$election_data["election_type"]}', '{$election_data["office"]}')");
          }
        }
      }
    }

    # Check prior special elections
    $html_page = get_html_file ("http://www.sos.ca.gov/elections/special-elections/");
    if (! $html_page) {} else {
      $special_elections = explode ("<a href=", $html_page->innertext);                 # create an array of items by <a href=
      foreach ($special_elections as $election) {                                       # cycle through each of the array items
        $t_start = strpos ($election, "/\">");                                          # find the end of the href
        $t_end = strpos ($election, "</a>");                                            # find the close of the anchor
        $title = substr ($election, $t_start + 3, $t_end - $t_start - 3);               # get the text of the anchor, the district for the special elections
        $election_dates = explode ("<li>", $election);                                  # seperate the items by <li>
        foreach ($election_dates as $election_date) {                                   # cycle through each list item
          if (strpos ($election_date, "</li>") !== false) {                             # if there is an </li> try parsing the date
            $election_data = parse_election_data ($title . " Special " . substr ($election_date, 0, strpos ($election_date, "</li>")));
            if ($election_data["election_date"] != "") {
              if ($election_data["election_id"] == 0) {
                script_query ("INSERT INTO cal_access_elections (date, description, special, election_type, office) VALUES ('{$election_data["election_date"]}', '{$election_data["title"]}', {$election_data["special"]}, '{$election_data["election_type"]}', '{$election_data["office"]}')");
              }
            }
          }
        }
        $election_data = parse_election_data ($title);                                  # parse the title alone
        if ($election_data["election_date"] != "") {
          if ($election_data["election_id"] == 0) {
            script_query ("INSERT INTO cal_access_elections (date, description, special, election_type, office) VALUES ('{$election_data["election_date"]}', '{$election_data["title"]}', {$election_data["special"]}, '{$election_data["election_type"]}', '{$election_data["office"]}')");
          }
        }
      }
    }
  }


#===============================================================================================
# gets the election information from the string
  function parse_election_data ($election_string) {
    $election_string = preg_replace ("/[^A-Za-z0-9 ]/", '', $election_string);
    $special = 0;
    $election_type = "";
    $election_date = "";
    $election_year = "";
    $office = "";
    $election_id = 0;
    $title_array["district"] = ""; $title_array["special"] = ""; $title_array["type"] = "";
    $election_array = NULL;

    $str_words = explode (" ", strtolower ($election_string));
    $word_idx = 0;
    foreach ($str_words as $word) {
      switch ($word) {
        case "special": $special = 1; $title_array["special"] = " Special"; break;
        case "primary": $election_type = "P"; $title_array ["type"] = " Primary"; break;
        case "general": case "runoff": $election_type = "G"; $title_array ["type"] = " General"; break;
        case "january": case "february": case "march": case "april": case "may": case "june": case "july": case "august": case "september": case "october": case "november": case "december":
          if (sizeof ($str_words) > $word_idx + 2) {$election_date = date ("Y-m-d", strtotime ($word . " " . $str_words[$word_idx + 1] . ", " . $str_words[$word_idx + 2]));}
          break;
        case "1996": case "1997": case "1998": case "1999": case "2000": case "2001": case "2002": case "2003": case "2004": case "2005": case "2006": case "2007": case "2008": case "2009": case "2010": case "2011": case "2012": case "2013": case "2014": case "2015":
          $election_year = $word; break;
        case "statewide": $office = "ALL"; $title_array["district"] = "Statewide"; break;
        case "presidential": $office = "P"; $title_array["district"] = "Presidential"; break;
        case "gubernatorial": $office = "G"; $title_array["district"] = "Gubernatorial"; break;
        case "congressional": $office = "C"; $title_array["district"] = "Congressional"; break;
        case "senate": $office = "S"; $title_array["district"] = "Senate"; break;
        case "assembly": $office = "A"; $title_array["district"] = "Assembly"; break;
        case "district": $office .= substr ("00" . trim ($str_words[$word_idx + 1]), -2); $title_array["district"] .= " District " . substr ("00" . trim ($str_words[$word_idx + 1]), -2); break;
      }
      $word_idx++;
    }

    $title = trim ($title_array["district"] . $title_array["special"] . $title_array["type"] . " Election");
    if ($special == 0) {
      $result = script_query ("SELECT election_id FROM cal_access_elections WHERE special = 0 AND office = 'ALL' AND election_type = '$election_type' AND YEAR(date) = '$election_year'");
      $row = $result->fetch_assoc();
      $election_id = $row["election_id"];
    } else {
      if ($election_type == "") {$election_type = "P";}
      if ($election_year == "2013" AND $office == "S04" AND $election_type == "P") {$election_type = "G";} # correct for incorrect election name (should be runoff)
      $result = script_query ("SELECT election_id FROM cal_access_elections WHERE special = 1 AND office = '$office' AND election_type = '$election_type' AND YEAR(date) = '$election_year'");
      $row = $result->fetch_assoc();
      $election_id = $row["election_id"];
    }
    if ($election_id == "") {$election_id = 0;}
    $election_array = array ("election_id" => $election_id, "special" => $special, "election_type" => $election_type, "office" => $office, "election_year" => $election_year, "election_date" => $election_date, "title" => $title, "election_parsed" => $election_string);

    return $election_array;
 }


#===============================================================================================
# get propositions list for specific session
  function get_propositions ($session, $err_chk) {
    $html_page = get_html_file ("http://cal-access.sos.ca.gov/Campaign/Measures/list.aspx?session=$session");
    if (! $html_page && $err_chk == 1) {} else {
      script_query ("DELETE FROM cal_access_propositions WHERE session = $session");

      # look for upcoming elections
      foreach ($html_page->find ("table[id=ListElections1_Panel1]") as $main_table) {
        foreach ($main_table->find ("table") as $table) {
          $caption = $table->find ("caption");
          $election_words = explode (" ", trim($caption[0]->plaintext));
          $election_date = date ("Y-m-d", strtotime ($election_words[0] . " " . $election_words[1] . " " . $election_words[2]));

          foreach ($table->find ("a") as $anchor) {
            # Get id# from html link
            $id = substr (substr ($anchor->href, 0, strpos ($anchor->href, "&")), strpos (substr ($anchor->href, 0, strpos ($anchor->href, "&")), "id=") + 3);
            $name = addslashes (trim ($anchor->plaintext));
            $link = "http://cal-access.sos.ca.gov/Campaign/Measures/". trim ($anchor->href);
            script_query ("INSERT INTO cal_access_propositions (session, proposition_id, election_date, name, link) VALUES ($session, $id, '$election_date', '$name', '$link')");
          }
        }
      }

      # look for prior elections
      foreach ($html_page->find ("table[id=ListElections1_Panel2]") as $main_table) {
        foreach ($main_table->find ("table") as $table) {
          $caption = $table->find ("caption");
          $election_words = explode (" ", trim($caption[0]->plaintext));
          $election_date = date ("Y-m-d", strtotime ($election_words[0] . " " . $election_words[1] . " " . $election_words[2]));

          foreach ($table->find ("a") as $anchor) {
            # Get id# from html link
            $id = substr (substr ($anchor->href, 0, strpos ($anchor->href, "&")), strpos (substr ($anchor->href, 0, strpos ($anchor->href, "&")), "id=") + 3);
            $name = addslashes (trim ($anchor->plaintext));
            $link = "http://cal-access.sos.ca.gov/Campaign/Measures/". trim ($anchor->href);
            script_query ("INSERT INTO cal_access_propositions (session, proposition_id, election_date, name, link) VALUES ($session, $id, '$election_date', '$name', '$link')");
          }
        }
      }
    }
  }


#===============================================================================================
# get proposition committes for specific session
  function get_propositions_committees ($session, $err_chk) {
    script_query ("DELETE FROM cal_access_propositions_committees WHERE session = $session");

    $query = "SELECT proposition_id, link FROM cal_access_propositions WHERE session = $session";
    $result = script_query ($query);
    while ($row = $result->fetch_assoc()) {
      $html_page = get_html_file ($row["link"]);
      if (! $html_page && $err_chk == 1) {} else {
        foreach ($html_page->find ("table") as $table) {
          if (strpos ($table->innertext, "COMMITTEE ID") !== false && strpos ($table->innertext, "category side nav starts here") === false) {
            $tr_count = 0; $filer_id = 0; $position = "";
            foreach ($table->find ("tr") as $tablerow) {
              $td_count = 0;
              foreach ($tablerow->find ("td") as $tabledata) {
                if ($tr_count > 0) {
                  switch ($td_count) {
                    case 0: $filer_id = trim ($tabledata->plaintext);
                    case 1: foreach ($tabledata->find("a") as $anchor) {$id = substr (substr ($anchor->href, 0, strpos ($anchor->href, "&")), strpos (substr ($anchor->href, 0, strpos ($anchor->href, "&")), "id=") + 3);}
                    case 2: $position = addslashes (trim ($tabledata->plaintext));
                  }
                }
                $td_count++;
              }
              if ($tr_count > 0) {script_query ("INSERT INTO cal_access_propositions_committees (session, proposition_id, filer_id, position) VALUES ($session, {$row["proposition_id"]}, $id, '$position')");}
              $tr_count++;
            }
          }
        }
      }
    }
  }


#===============================================================================================
# get all candidate names for a specific session
  function get_candidate_names ($session, $err_chk) {
    $alphabet = array ("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

    $query = "DELETE FROM cal_access_candidates WHERE session = $session"; script_query ($query);
    $query = "DELETE FROM cal_access_candidates_names WHERE session = $session"; script_query ($query);
    $query = "DELETE FROM cal_access_candidates_spending_limits WHERE session = $session"; script_query ($query);
    $query = "DELETE FROM cal_access_candidates_races WHERE session = $session"; script_query ($query);
    $query = "DELETE FROM cal_access_candidates_committees WHERE session = $session"; script_query ($query);

    foreach ($alphabet as $letter) {
      $html_page = get_html_file ("http://cal-access.sos.ca.gov/Campaign/Candidates/list.aspx?view=name&letter=$letter&session=$session");
      if (! $html_page && $err_chk == 1) {} else {
        foreach ($html_page->find ("table[id=_ctl3_candidates]") as $table) {
          foreach ($table->find ("tr") as $tablerow) {
            foreach ($tablerow->find ("td") as $tabledata) {
              $name = addslashes ($tabledata->plaintext);
              if ($name != "NAME") {
                $link_element = $tabledata->find ("a");
                $link = addslashes (trim ("http://cal-access.sos.ca.gov/Campaign/Candidates/" . htmlspecialchars_decode ($link_element[0]->href)));
                $id = substr (substr ($link, 0, strpos ($link, "&")), strpos (substr ($link, 0, strpos ($link, "&")), "id=") + 3);
                script_query ("INSERT INTO cal_access_candidates (session, name, id, link) VALUES ($session, '$name', $id, '$link')");
              }
            }
          }
        }
        $html_page->clear ();
      }
    }
  }


#===============================================================================================
# get all candidate data for a specific session and candidate
  function get_candidate_data ($session, $err_chk) {
    $query = "SELECT id, link FROM cal_access_candidates WHERE session = $session";
    $result = script_query ($query);
    while ($row = $result->fetch_assoc()) {
      $html_page = get_html_file ($row["link"]);
      if (! $html_page && $err_chk == 1) {} else {
        $data_in_process = "";
        $historical_names = array ();
        $party = "";
        $limit_election = "";
        $spending_limits = array ();
        $races = array ();
        $committees = array ();

        foreach ($html_page->find ("table") as $table) {
          if (strpos ($table->innertext, "category side nav starts here") === false) {
            $tr_count = 0;
            foreach ($table->find ("tr") as $tablerow) {
              $td_count = 0;
              foreach ($tablerow->find ("td") as $tabledata) {
                switch ($data_in_process) {
                  case "HISTORICAL": if (trim ($tabledata->plaintext) != "PARTY") {$historical_names[] = addslashes (trim ($tabledata->plaintext));} break;
                  case "PARTY": $party = addslashes (trim ($tabledata->plaintext)); $data_in_process = ""; break;
                  case "LIMITS":
                      switch ($td_count) {
                        case 0: $limit_election = trim ($tabledata->plaintext); break;
                        case 1: if ($limit_election != "") {$spending_limits[$limit_election] = trim ($tabledata->plaintext);} break;
                      }
                      break;
                  case "RACES":
                      switch ($td_count) {
                        case 0: $office = trim ($tabledata->plaintext); break;
                        case 1: $election = trim ($tabledata->plaintext); break;
                        case 2:
                            if ($office != "OFFICE") {
                              $result2 = script_query ("SELECT election_id, date, description FROM cal_access_elections WHERE IF(office = 'ALL', CONCAT(YEAR(date), IF(election_type = 'G', ' GENERAL', ' PRIMARY'), ' ELECTION'), '') = '$election';");
                              $election_info = $result2->fetch_object();
                               if (! $election_info) {
                                 $election_data = parse_election_data ($office . " " . $election);
                               } else {
                                 $election_data = parse_election_data (date ("F j, Y", strtotime ($election_info->date)) . " " . $election_info->description);
                               }
                              $races[] = array ("election_id" => $election_data["election_id"], "result" => trim ($tabledata->plaintext), "office" => $election_data["office"], "election_parsed" => $election_data["election_parsed"]);
                            }
                            break;
                      }
                      break;
                }

                switch (trim (substr ($tabledata->plaintext, 0, 5))) {
                  case "HISTO": $data_in_process = "HISTORICAL"; break;
                  case "PARTY": $data_in_process = "PARTY"; break;
                  case "ELECT": if ($data_in_process != "RACES") {$data_in_process = "LIMITS";} break;
                  case "RACES": $data_in_process = "RACES"; break;
                }

                if (strpos ($tabledata->innertext, "/Misc/redirector.aspx?session=$session") !== false) {
                  $anchor = $tabledata->find ("a");
                  $committees[] = substr ($anchor[0]->href, strrpos ($anchor[0]->href, "id=") + 3);
                }

                $td_count++;
              }
              $tr_count++;
            }
          }
        }
        $html_page->clear ();

        script_query ("UPDATE cal_access_candidates SET party = '$party' WHERE session = $session AND id = {$row["id"]}");
        foreach ($historical_names as $name) {if ($name != "") {script_query ("INSERT INTO cal_access_candidates_names (session, id, name) VALUES ($session, {$row["id"]}, '$name')");}}
        foreach ($spending_limits as $election => $accepted) {if ($election != "") {script_query ("INSERT INTO cal_access_candidates_spending_limits (session, id, election, accepted) VALUES ($session, {$row["id"]}, '$election', '$accepted')");}}
        foreach ($races as $race_data) {script_query ("INSERT INTO cal_access_candidates_races (session, id, election_id, office, result, parsed) VALUES ($session, {$row["id"]}, {$race_data["election_id"]}, '{$race_data["office"]}', '{$race_data["result"]}', '{$race_data["election_parsed"]}')");}
        foreach ($committees as $filer_id) {script_query ("INSERT INTO cal_access_candidates_committees (session, id, filer_id) VALUES ($session, {$row["id"]}, $filer_id)");}
      }
    }
  }


#===============================================================================================
# get specific committee information
  function get_committee_information ($session, $filer_id, $err_chk) {
    $link = "http://cal-access.sos.ca.gov/Campaign/Committees/Detail.aspx?id={$filer_id}&session={$session}";
    $type = "UNKNOWN";
    
    $html_page = get_html_file ($link);
    if (! $html_page && $err_chk == 1) {} else {
      $name_span = $html_page->find ("span[id=lblFilerName]");
      $name = addslashes ($name_span[0]->innertext);

      if ($name != "NAME") {
        $query = "INSERT INTO cal_access_committees (session, name, link, type, filer_id) VALUES ($session, '$name', '$link', '$type', $filer_id)";
        script_query ($query);
        get_committee_general_information ($session, $filer_id, $err_chk);
      }
    }
  }


#===============================================================================================
# get general information for a specific committee
  function get_committee_general_information ($session, $filer_id, $err_chk) {
    $query = "SELECT link FROM cal_access_committees WHERE session = $session AND filer_id = $filer_id";
    $result = script_query ($query); $row = $result->fetch_assoc();

    $html_page = get_html_file ($row["link"]); # load the page in
    if (! $html_page && $err_chk == 1) {} else {
      # get historical names
      foreach ($html_page->find ("table[id=_ctl3_names]") as $table) {
        foreach ($table->find ("tr") as $tablerow) {
          foreach ($tablerow->find ("td") as $tabledata) {
            if ($tabledata->plaintext != "HISTORICAL NAMES FOR THIS COMMITTEE") {
              $name = addslashes (trim ($tabledata->plaintext));
              $query = "INSERT INTO cal_access_committees_names (session, filer_id, name) VALUES ($session, $filer_id, '$name')"; script_query ($query);
            }
          }
        }
      }

      # reset values to empty
      $phone = "";
      $status = "";
      $last_date = "";
      $report_start = "";
      $report_end = "";
      $total_start = "";
      $total_end = "";
      $report_contributions = "NULL";
      $report_expenditures = "NULL";
      $total_contributions = "NULL";
      $total_expenditures = "NULL";
      $ending_cash = "NULL";

      # get phone from the page
      $page_phone = $html_page->find ("span[id=_ctl3_lblFilerAddress]");
      if (array_key_exists (0, $page_phone)) {$phone = addslashes (trim ($page_phone[0]->plaintext));}

      # get summary information from page - cycle through each table, but only use the one that has "SUMMARY INFORMATION"  and is not the full page table
      foreach ($html_page->find ("table") as $table) {
        if (strpos ($table->innertext, "SUMMARY INFORMATION") !== false && strpos ($table->innertext, "category side nav starts here") === false) {
          foreach ($table->find ("tr") as $tablerow) {
            $count = 0;
            foreach ($tablerow->find ("td") as $tabledata) {
              switch ($count) {
                case 0: $title = substr (trim ($tabledata->plaintext), 0, 10); $full_title = trim ($tabledata->plaintext); break;
                case 1: switch ($title) {
                                 case "CURRENT ST": $status = addslashes (trim ($tabledata->plaintext)); break;
                                 case "LAST REPOR": $last_date = date ("Y-m-d", strtotime ($tabledata->plaintext)); break;
                                 case "REPORTING ": $report_start = date ("Y-m-d", strtotime (substr (trim ($tabledata->plaintext), 0, 10)));
                                                                         $report_end = date ("Y-m-d", strtotime (substr (trim ($tabledata->plaintext), -10))); break;
                                 case "CONTRIBUTI": $report_contributions = get_amount ($tabledata->plaintext); break;
                                 case "EXPENDITUR": $report_expenditures = get_amount ($tabledata->plaintext); break;
                                 case "TOTAL CONT": $total_contributions = get_amount ($tabledata->plaintext);
                                                                         $title_words = explode (" ", $full_title);
                                                                         $total_start = date ("Y-m-d", strtotime ($title_words[2]));
                                                                         $total_end = date ("Y-m-d", strtotime ($title_words[4])); break;
                                 case "TOTAL EXPE": $total_expenditures = get_amount ($tabledata->plaintext);
                                                                         $title_words = explode (" ", $full_title);
                                                                         $total_start = date ("Y-m-d", strtotime ($title_words[2]));
                                                                         $total_end = date ("Y-m-d", strtotime ($title_words[4])); break;
                                 case "ENDING CAS": $ending_cash = get_amount ($tabledata->plaintext); break;
                               }
                               break;
              }
              $count++;
            }
          }
        }
      }

      $query = "UPDATE cal_access_committees SET phone = '$phone', status = '$status', last_date = '$last_date', report_start = '$report_start', report_end = '$report_end', total_start = '$total_start', total_end = '$total_end', report_contributions = $report_contributions, report_expenditures = $report_expenditures, total_contributions = $total_contributions, total_expenditures = $total_expenditures, ending_cash = $ending_cash, general_information = 1 WHERE session = $session AND filer_id = $filer_id";
      script_query ($query);

      $html_page->clear ();
    }
  }


#===============================================================================================
# get html file with repeat before fail
  function get_html_file ($url) {
    $retries = 5;
    $loop_count = 0;

    do {
      $html = file_get_html ($url);
      $loop_count++;
    } while ($loop_count < $retries && $html == "");

    return $html; 
  }


#===============================================================================================
# process amount values and set them correctly
  function get_amount ($str_amount) {
    $cleaned_amount = str_replace (array ("$", ","), "", $str_amount);
    if (substr ($cleaned_amount, 0, 1) == "(" || substr ($cleaned_amount, 0, 1) == "-") {$multiplier = -1;} else {$multiplier = 1;}
    $cleaned_amount = str_replace (array ("(", ")", "-"), "", $cleaned_amount);
    return floatval ($cleaned_amount) * $multiplier;
  }
?>
