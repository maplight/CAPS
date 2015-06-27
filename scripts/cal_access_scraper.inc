<?php
  require("simple_html_dom.php");
  require("../connect.php");
  require("script_connect.php");
  require("name_parser.inc");


#===============================================================================================
# load an sql file
function process_sql_file($filename)
{
    global $script_db;

    $sql_str = "";    
    $sql_file = fopen($filename, "r");
    
    if ($sql_file) {
        while (($line = fgets($sql_file)) !== false) {
            if (trim($line) == "") {
                if (trim($sql_str) != "") {
                    $result = $script_db->prepare($sql_str);
                    $result->execute();
                    if (!$result) {
                        echo "Error on import of " . $sql_str . "\n";
                    }
                }
                $sql_str = "";
            } else {
                $sql_str .= $line;
            }
        }
    }
    if (trim($sql_str) != "") {
        $result = $script_db->prepare($sql_str);
        $result->execute();
        if (!$result) {
            echo "Error on import of " . $sql_str . "\n";
        }
    }
}


#===============================================================================================
# update elections list
function get_elections_list()
{
  global $script_db;

  # Check upcoming elections
  $html_page = get_html_file("http://www.sos.ca.gov/elections/upcoming-elections");
  if (! $html_page) {} else {
    foreach ($html_page->find("li") as $list_item) {
      $election_data = parse_election_data($list_item->plaintext);
      if ($election_data["election_date"] != "") {
        if ($election_data["election_id"] == 0) {
          $query = "INSERT INTO cal_access_elections
                      (date,
                       description,
                       special,
                       election_type,
                       office)
                    VALUES
                      (?, ?, ?, ?, ?)";
          $result = $script_db->prepare($query);
          $result->execute(array($election_data["election_date"], $election_data["title"], $election_data["special"], $election_data["election_type"], $election_data["office"]));
        }
      }
    }
  }

  # Check prior statewide elections
  $html_page = get_html_file("http://www.sos.ca.gov/elections/prior-elections/statewide-election-results");
  if (! $html_page) {} else {
    foreach ($html_page->find("li") as $list_item) {
      $election_data = parse_election_data($list_item->plaintext);
      if ($election_data["election_date"] != "") {
        if ($election_data["election_id"] == 0) {
          $query = "INSERT INTO cal_access_elections
                      (date,
                       description,
                       special,
                       election_type,
                       office)
                    VALUES
                      (?, ?, ?, ?, ?)";
          $result = $script_db->prepare($query);
          $result->execute(array($election_data["election_date"], $election_data["title"], $election_data["special"], $election_data["election_type"], $election_data["office"]));
        }
      }
    }
  }

  # Check prior special elections
  $html_page = get_html_file("http://www.sos.ca.gov/elections/prior-elections/special-elections");
  if (! $html_page) {} else {
    $special_elections = explode("<a href=", $html_page->innertext);                 # create an array of items by <a href=
    foreach ($special_elections as $election) {                                       # cycle through each of the array items
      $t_start = strpos($election, "/\">");                                          # find the end of the href
      $t_end = strpos($election, "</a>");                                            # find the close of the anchor
      $title = substr($election, $t_start + 3, $t_end - $t_start - 3);               # get the text of the anchor, the district for the special elections
      $election_dates = explode("<li>", $election);                                  # seperate the items by <li>
      foreach ($election_dates as $election_date) {                                   # cycle through each list item
        if (strpos($election_date, "</li>") !== false) {                             # if there is an </li> try parsing the date
          $election_data = parse_election_data($title . " Special " . substr($election_date, 0, strpos($election_date, "</li>")));
          if ($election_data["election_date"] != "") {
            if ($election_data["election_id"] == 0) {
              $query = "INSERT INTO cal_access_elections
                          (date,
                           description,
                           special,
                           election_type,
                           office)
                        VALUES
                          (?, ?, ?, ?, ?)";
              $result = $script_db->prepare($query);
              $result->execute(array($election_data["election_date"], $election_data["title"], $election_data["special"], $election_data["election_type"], $election_data["office"]));
            }
          }
        }
      }
      $election_data = parse_election_data($title);                                  # parse the title alone
      if ($election_data["election_date"] != "") {
        if ($election_data["election_id"] == 0) {
          $query = "INSERT INTO cal_access_elections
                      (date,
                       description,
                       special,
                       election_type,
                       office)
                    VALUES
                      (?, ?, ?, ?, ?)";
          $result = $script_db->prepare($query);
          $result->execute(array($election_data["election_date"], $election_data["title"], $election_data["special"], $election_data["election_type"], $election_data["office"]));
        }
      }
    }
  }
}


#===============================================================================================
# gets the election information from the string
function parse_election_data($election_string)
{
  global $script_db;

  $election_string = preg_replace("/[^A-Za-z0-9 ]/", '', $election_string);
  $special = 0;
  $election_type = "";
  $election_date = "";
  $election_year = "";
  $office = "";
  $election_id = 0;
  $title_array["district"] = ""; $title_array["special"] = ""; $title_array["type"] = "";
  $election_array = NULL;

  $str_words = explode(" ", strtolower($election_string));
  $word_idx = 0;
  foreach ($str_words as $word) {
    switch ($word) {
      case "special": $special = 1; $title_array["special"] = " Special"; break;
      case "primary": $election_type = "P"; $title_array ["type"] = " Primary"; break;
      case "general": case "runoff": $election_type = "G"; $title_array ["type"] = " General"; break;
      case "january": case "february": case "march": case "april": case "may": case "june": case "july": case "august": case "september": case "october": case "november": case "december":
        if (sizeof($str_words) > $word_idx + 2) {$election_date = date("Y-m-d", strtotime($word . " " . $str_words[$word_idx + 1] . ", " . $str_words[$word_idx + 2]));}
        break;
      case "1990": case "1991": case "1992": case "1993": case "1994": case "1995": case "1996": case "1997": case "1998": case "1999":
      case "2000": case "2001": case "2002": case "2003": case "2004": case "2005": case "2006": case "2007": case "2008": case "2009":
      case "2010": case "2011": case "2012": case "2013": case "2014": case "2015": case "2016": case "2017": case "2018": case "2019":
      case "2020": case "2021": case "2022": case "2023": case "2024": case "2025": case "2026": case "2027": case "2028": case "2029":
        $election_year = $word; break;
      case "statewide": $office = "ALL"; $title_array["district"] = "Statewide"; break;
      case "presidential": $office = "P"; $title_array["district"] = "Presidential"; break;
      case "gubernatorial": $office = "G"; $title_array["district"] = "Gubernatorial"; break;
      case "congressional": $office = "C"; $title_array["district"] = "Congressional"; break;
      case "senate": $office = "S"; $title_array["district"] = "Senate"; break;
      case "assembly": $office = "A"; $title_array["district"] = "Assembly"; break;
      case "district": $office .= substr("00" . trim($str_words[$word_idx + 1]), -2); $title_array["district"] .= " District " . substr("00" . trim($str_words[$word_idx + 1]), -2); break;
    }
    $word_idx++;
  }

  $title = trim($title_array["district"] . $title_array["special"] . $title_array["type"] . " Election");
  if ($election_year == "") {$election_year = date("Y", strtotime($election_date));}
  if ($election_type == "") {$election_type = "P";}
  if ($election_year == "2013" AND $office == "S04" AND $election_type == "P") {$election_type = "G";} # correct for incorrect election name (should be runoff)

  $result = $script_db->prepare("SELECT election_id FROM cal_access_elections WHERE special = ? AND office = ? AND election_type = ? AND YEAR(date) = ?");
  $result->execute(array($special, $office, $election_type, $election_year));
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $election_id = $row["election_id"];

  # If no specific election was found, check to see if this is the general election and use it.
  if ($election_id == "" && $special == 0 && $election_type == "G") {
    $result = $script_db->prepare("SELECT election_id FROM cal_access_elections WHERE special = 0 AND election_type = 'G' AND YEAR(date) = ?");
    $result->execute(array($election_year));
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $election_id = $row["election_id"];
  }

  if ($election_id == "") {$election_id = 0;}
  $election_array = array("election_id" => $election_id, "special" => $special, "election_type" => $election_type, "office" => $office, "election_year" => $election_year, "election_date" => $election_date, "title" => $title, "election_parsed" => $election_string);
  return $election_array;
}


#===============================================================================================
# get propositions list for specific session
function get_propositions($session, $err_chk)
{
  global $script_db;

  $html_page = get_html_file("http://cal-access.sos.ca.gov/Campaign/Measures/list.aspx?session=$session");
  if (! $html_page && $err_chk == 1) {} else {
    $result = $script_db->prepare("DELETE FROM cal_access_propositions WHERE session = ?");
    $result->execute(array($session));

    # look for upcoming elections
    foreach ($html_page->find("table[id=ListElections1_Panel1]") as $main_table) {
      foreach ($main_table->find("table") as $table) {
        $caption = $table->find("caption");
        $election_words = explode(" ", trim($caption[0]->plaintext));
        $election_date = date("Y-m-d", strtotime($election_words[0] . " " . $election_words[1] . " " . $election_words[2]));

        foreach ($table->find("a") as $anchor) {
          # Get id# from html link
          $id = substr(substr($anchor->href, 0, strpos($anchor->href, "&")), strpos(substr($anchor->href, 0, strpos($anchor->href, "&")), "id=") + 3);
          $name = trim($anchor->plaintext);
          $link = "http://cal-access.sos.ca.gov/Campaign/Measures/". trim($anchor->href);
          $result = $script_db->prepare("INSERT INTO cal_access_propositions (session, proposition_id, election_date, name, link) VALUES (?, ?, ?, ?, ?)");
          $result->execute(array($session, $id, $election_date, $name, $link));
        }
      }
    }

    # look for prior elections
    foreach ($html_page->find("table[id=ListElections1_Panel2]") as $main_table) {
      foreach ($main_table->find("table") as $table) {
        $caption = $table->find("caption");
        $election_words = explode(" ", trim($caption[0]->plaintext));
        $election_date = date("Y-m-d", strtotime($election_words[0] . " " . $election_words[1] . " " . $election_words[2]));

        foreach ($table->find ("a") as $anchor) {
          # Get id# from html link
          $id = substr(substr($anchor->href, 0, strpos($anchor->href, "&")), strpos(substr($anchor->href, 0, strpos($anchor->href, "&")), "id=") + 3);
          $name = trim($anchor->plaintext);
          $link = "http://cal-access.sos.ca.gov/Campaign/Measures/". trim($anchor->href);
          $result = $script_db->prepare("INSERT INTO cal_access_propositions (session, proposition_id, election_date, name, link) VALUES (?, ?, ?, ?, ?)");
          $result->execute(array($session, $id, $election_date, $name, $link));
        }
      }
    }
  }
}


#===============================================================================================
# get proposition committes for specific session
function get_propositions_committees($session, $err_chk)
{
  global $script_db;

  $result = $script_db->prepare("DELETE FROM cal_access_propositions_committees WHERE session = ?");
  $result->execute(array($session));

  $result = $script_db->prepare("SELECT proposition_id, link FROM cal_access_propositions WHERE session = ?");
  $result->execute(array($session));
  foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $html_page = get_html_file($row["link"]);
    if (! $html_page && $err_chk == 1) {} else {
      foreach ($html_page->find("table") as $table) {
        if (strpos($table->innertext, "COMMITTEE ID") !== false && strpos($table->innertext, "category side nav starts here") === false) {
          $tr_count = 0; $filer_id = 0; $position = "";
          foreach ($table->find("tr") as $tablerow) {
            $td_count = 0;
            foreach ($tablerow->find("td") as $tabledata) {
              if ($tr_count > 0) {
                switch ($td_count) {
                  case 0: $filer_id = trim($tabledata->plaintext);
                  case 1: foreach ($tabledata->find("a") as $anchor) {$id = substr(substr($anchor->href, 0, strpos($anchor->href, "&")), strpos(substr($anchor->href, 0, strpos($anchor->href, "&")), "id=") + 3);}
                  case 2: $position = trim($tabledata->plaintext);
                }
              }
              $td_count++;
            }
            if ($tr_count > 0) {
              $result = $script_db->prepare("INSERT INTO cal_access_propositions_committees (session, proposition_id, filer_id, position) VALUES (?, ?, ?, ?)");
              $result->execute(array($session, $row["proposition_id"], $id, $position));
            }
            $tr_count++;
          }
        }
      }
    }
  }
}


#===============================================================================================
# get all candidate names for a specific session
function get_candidate_names($session, $err_chk)
{
  global $script_db;

  $alphabet = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
  $result = $script_db->prepare("DELETE FROM cal_access_candidates WHERE session = ?"); $result->execute(array($session));
  $result = $script_db->prepare("DELETE FROM cal_access_candidates_names WHERE session = ?"); $result->execute(array($session));
  $result = $script_db->prepare("DELETE FROM cal_access_candidates_spending_limits WHERE session = ?"); $result->execute(array($session));
  $result = $script_db->prepare("DELETE FROM cal_access_candidates_races WHERE session = ?"); $result->execute(array($session));
  $result = $script_db->prepare("DELETE FROM cal_access_candidates_committees WHERE session = ?"); $result->execute(array($session));

  foreach ($alphabet as $letter) {
    $html_page = get_html_file("http://cal-access.sos.ca.gov/Campaign/Candidates/list.aspx?view=name&letter=$letter&session=$session");
    if (! $html_page && $err_chk == 1) {} else {
      foreach ($html_page->find("table[id=_ctl3_candidates]") as $table) {
        foreach ($table->find("tr") as $tablerow) {
          foreach ($tablerow->find("td") as $tabledata) {
            $name = $tabledata->plaintext;
            if ($name != "NAME") {
              $link_element = $tabledata->find("a");
              $link = trim("http://cal-access.sos.ca.gov/Campaign/Candidates/" . htmlspecialchars_decode($link_element[0]->href));
              $id = substr(substr($link, 0, strpos($link, "&")), strpos(substr($link, 0, strpos($link, "&")), "id=") + 3);
              $result = $script_db->prepare("INSERT INTO cal_access_candidates (session, name, id, link) VALUES (?, ?, ?, ?)");
              $result->execute(array($session, $name, $id, $link));
            }
          }
        }
      }
      $html_page->clear();
    }
  }
}


#===============================================================================================
# get all candidate data for a specific session and candidate
function get_candidate_data($session, $err_chk)
{
  global $script_db;

  $result = $script_db->prepare("SELECT id, link FROM cal_access_candidates WHERE session = ?");
  $result->execute(array($session));
  foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $html_page = get_html_file($row["link"]);
    if (! $html_page && $err_chk == 1) {} else {
      $data_in_process = "";
      $historical_names = array();
      $party = "";
      $limit_election = "";
      $spending_limits = array();
      $races = array();
      $committees = array();

      foreach ($html_page->find("table") as $table) {
        if (strpos($table->innertext, "category side nav starts here") === false) {
          $tr_count = 0;
          foreach ($table->find("tr") as $tablerow) {
            $td_count = 0;
            foreach ($tablerow->find("td") as $tabledata) {
              switch ($data_in_process) {
                case "HISTORICAL": if (trim($tabledata->plaintext) != "PARTY") {$historical_names[] = trim($tabledata->plaintext);} break;
                case "PARTY": $party = trim($tabledata->plaintext); $data_in_process = ""; break;
                case "LIMITS":
                    switch ($td_count) {
                      case 0: $limit_election = trim($tabledata->plaintext); break;
                      case 1: if ($limit_election != "") {$spending_limits[$limit_election] = trim($tabledata->plaintext);} break;
                    }
                    break;
                case "RACES":
                    switch ($td_count) {
                      case 0: $office = trim($tabledata->plaintext); break;
                      case 1: $election = trim($tabledata->plaintext); break;
                      case 2:
                          if ($office != "OFFICE") {
                            $result2 = $script_db->prepare("SELECT election_id, date, description FROM cal_access_elections WHERE IF(office = 'ALL', CONCAT(YEAR(date), IF(election_type = 'G', ' GENERAL', ' PRIMARY'), ' ELECTION'), '') = ?;");
                            $result2->execute(array($election));
                            $election_info = $result2->fetch(PDO::FETCH_ASSOC);
                            if (empty($election_info)) {
                              $election_data = parse_election_data($office . " " . $election);
                            } else {
                              $election_data = parse_election_data(date("F j, Y", strtotime($election_info["date"])) . " " . $election_info["description"]);
                            }
                            $races[] = array("election_id" => $election_data["election_id"], "result" => trim($tabledata->plaintext), "office" => $election_data["office"], "election_parsed" => $election_data["election_parsed"]);
                          }
                          break;
                    }
                    break;
              }

              switch (trim(substr($tabledata->plaintext, 0, 5))) {
                case "HISTO": $data_in_process = "HISTORICAL"; break;
                case "PARTY": $data_in_process = "PARTY"; break;
                case "ELECT": if ($data_in_process != "RACES") {$data_in_process = "LIMITS";} break;
                case "RACES": $data_in_process = "RACES"; break;
              }

              if (strpos($tabledata->innertext, "/Misc/redirector.aspx?session=$session") !== false) {
                $anchor = $tabledata->find("a");
                $committees[] = substr($anchor[0]->href, strrpos($anchor[0]->href, "id=") + 3);
              }

              $td_count++;
            }
            $tr_count++;
          }
        }
      }
      $html_page->clear();

      $result = $script_db->prepare("UPDATE cal_access_candidates SET party = ? WHERE session = ? AND id = ?");
      $result->execute(array($party, $session, $row["id"]));
      foreach ($historical_names as $name) {
        if ($name != "") {
          $result = $script_db->prepare("INSERT INTO cal_access_candidates_names (session, id, name) VALUES (?, ?, ?)");
          $result->execute(array($session, $row["id"], $name));
        }
      }
      foreach ($spending_limits as $election => $accepted) {
        if ($election != "") {
          $result = $script_db->prepare("INSERT INTO cal_access_candidates_spending_limits (session, id, election, accepted) VALUES (?, ?, ?, ?)");
          $result->execute(array($session, $row["id"], $election, $accepted));
        }
      }
      foreach ($races as $race_data) {
        $result = $script_db->prepare("INSERT INTO cal_access_candidates_races (session, id, election_id, office, result, parsed) VALUES (?, ?, ?, ?, ?, ?)");
        $result->execute(array($session, $row["id"], $race_data["election_id"], $race_data["office"], $race_data["result"], $race_data["election_parsed"]));
      }
      foreach ($committees as $filer_id) {
        $result = $script_db->prepare("INSERT INTO cal_access_candidates_committees (session, id, filer_id) VALUES (?, ?, ?)");
        $result->execute(array($session, $row["id"], $filer_id));
      }
    }
  }
}


#===============================================================================================
# get specific committee information
function get_committee_information($session, $filer_id, $err_chk)
{
  global $script_db;

  $link = "http://cal-access.sos.ca.gov/Campaign/Committees/Detail.aspx?id={$filer_id}&session={$session}";
  $type = "UNKNOWN";
    
  $html_page = get_html_file($link);
  if (! $html_page && $err_chk == 1) {} else {
    $name_span = $html_page->find("span[id=lblFilerName]");
    $name = $name_span[0]->innertext;

    if ($name != "NAME") {
      $result = $script_db->prepare("INSERT INTO cal_access_committees (session, name, link, type, filer_id) VALUES (?, ?, ?, ?, ?)");
      $result->execute(array($session, $name, $link, $type, $filer_id));
      get_committee_general_information($session, $filer_id, $err_chk);
    }
  }
}


#===============================================================================================
# get general information for a specific committee
function get_committee_general_information ($session, $filer_id, $err_chk)
{
  global $script_db;

  $result = $script_db->prepare("SELECT link FROM cal_access_committees WHERE session = ? AND filer_id = ?");
  $result->execute(array($session, $filer_id));
  $row = $result->fetch(PDO::FETCH_ASSOC);

  $html_page = get_html_file($row["link"]); # load the page in
  if (! $html_page && $err_chk == 1) {} else {
    # get historical names
    foreach ($html_page->find("table[id=_ctl3_names]") as $table) {
      foreach ($table->find("tr") as $tablerow) {
        foreach ($tablerow->find("td") as $tabledata) {
          if ($tabledata->plaintext != "HISTORICAL NAMES FOR THIS COMMITTEE") {
            $name = trim($tabledata->plaintext);
            $result = $script_db->prepare("INSERT INTO cal_access_committees_names (session, filer_id, name) VALUES (?, ?, ?)");
            $result->execute(array($session, $filer_id, $name));
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
    $page_phone = $html_page->find("span[id=_ctl3_lblFilerAddress]");
    if (array_key_exists(0, $page_phone)) {$phone = trim($page_phone[0]->plaintext);}

    # get summary information from page - cycle through each table, but only use the one that has "SUMMARY INFORMATION"  and is not the full page table
    foreach ($html_page->find("table") as $table) {
      if (strpos ($table->innertext, "SUMMARY INFORMATION") !== false && strpos($table->innertext, "category side nav starts here") === false) {
        foreach ($table->find ("tr") as $tablerow) {
          $count = 0;
          foreach ($tablerow->find ("td") as $tabledata) {
            switch ($count) {
              case 0: $title = substr(trim($tabledata->plaintext), 0, 10); $full_title = trim($tabledata->plaintext); break;
              case 1: switch ($title) {
                               case "CURRENT ST": $status = trim($tabledata->plaintext); break;
                               case "LAST REPOR": $last_date = date("Y-m-d", strtotime($tabledata->plaintext)); break;
                               case "REPORTING ": $report_start = date("Y-m-d", strtotime(substr(trim($tabledata->plaintext), 0, 10)));
                                                                       $report_end = date("Y-m-d", strtotime(substr(trim($tabledata->plaintext), -10))); break;
                               case "CONTRIBUTI": $report_contributions = get_amount($tabledata->plaintext); break;
                               case "EXPENDITUR": $report_expenditures = get_amount($tabledata->plaintext); break;
                               case "TOTAL CONT": $total_contributions = get_amount($tabledata->plaintext);
                                                                       $title_words = explode(" ", $full_title);
                                                                       $total_start = date("Y-m-d", strtotime($title_words[2]));
                                                                       $total_end = date("Y-m-d", strtotime($title_words[4])); break;
                               case "TOTAL EXPE": $total_expenditures = get_amount($tabledata->plaintext);
                                                                       $title_words = explode(" ", $full_title);
                                                                       $total_start = date("Y-m-d", strtotime($title_words[2]));
                                                                       $total_end = date("Y-m-d", strtotime($title_words[4])); break;
                               case "ENDING CAS": $ending_cash = get_amount($tabledata->plaintext); break;
                             }
                             break;
            }
            $count++;
          }
        }
      }
    }
    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));

    $html_page->clear();
  }
}


#===============================================================================================
# get html file with repeat before fail
function get_html_file($url)
{
  $retries = 10;
  $loop_count = 0;

  do {
    $html = file_get_html($url);
    $loop_count++;
  } while ($loop_count < $retries && $html == "");

  return $html; 
}


#===============================================================================================
# process amount values and set them correctly
function get_amount($str_amount)
{
  $cleaned_amount = str_replace(array("$", ","), "", $str_amount);
  if (substr($cleaned_amount, 0, 1) == "(" || substr($cleaned_amount, 0, 1) == "-") {$multiplier = -1;} else {$multiplier = 1;}
  $cleaned_amount = str_replace(array("(", ")", "-"), "", $cleaned_amount);
  return floatval($cleaned_amount) * $multiplier;
}


#===============================================================================================
function process_mysqli_query($query)
{
  global $hostname;
  global $script_login;
  global $script_pwd;
  $script_conn = mysqli_init();
  mysqli_options($script_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect($script_conn, $hostname, $script_login, $script_pwd, "ca_process");
  $ret = $script_conn->query($query);
  $script_conn->close();
}


#===============================================================================================
function clean_candidate_names()
{
  global $script_db;

  $update_qry = $script_db->prepare("UPDATE filing_amends SET gender = ?, first_name = ?, middle_name = ?, last_name = ?, name_suffix = ?, name_prefix = ?, nick_name = ?, display_name = ? WHERE id = ?");

  $words_to_remove = array();
  $result = $script_db->query("SELECT * FROM names_to_remove");
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {$words_to_remove[] = $row["removal_word"];}

  $result = $script_db->query("SELECT * FROM filing_amends WHERE cand_naml <> '' OR cand_nams <> '' OR cand_namt <> '' OR cand_namf <> '' OR candidate_name <> ''");
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    if ($row["candidate_name"] == "") {
      $name = $row["cand_naml"] . " " . $row["cand_nams"] . ", " . $row["cand_namt"] . " " . $row["cand_namf"];
    } else {
      $name = $row["candidate_name"];
    }

    $name = trim(str_replace(array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9"), "", $name));
    $parsed_name = parse_name($name);
    $first_name = strtoupper($parsed_name["first_name"]);
    $last_name = strtoupper($parsed_name["last_name"]);
    $middle_name = strtoupper($parsed_name["middle_name"]);
    $nick_name = strtoupper($parsed_name["nick_name"]);
    $name_suffix = strtoupper($parsed_name["suffix"]);
    $name_prefix = strtoupper($parsed_name["prefix"]);

    if ($middle_name . " " . $last_name == strtoupper($row["cand_naml"])) {
      $last_name = $middle_name . " " . $last_name;
      $middle_name = "";
    }

    if ($last_name <> "") {$ln = "$last_name";} else {$ln = "";}
    if ($name_suffix <> "") {$ns = " $name_suffix";} else {$ns = "";}
    if ($first_name <> "") {$fn = ", $first_name";} else {$fn = "";}
    if ($middle_name <> "") {$mn = " $middle_name";} else {$mn = "";}
    $display_name = trim($ln . $ns . $fn . $mn);
    $gender = $parsed_name["gender"];
    if ($row["display_name"] != "") {$display_name = $name;}
    if ($row["candidate_name"] != "") {$display_name = $row["candidate_name"];}

    $removal_word_found = false;
    foreach ($words_to_remove as $removal_word) {
      if (strpos(" " . strtoupper($name) . " ", " " . $removal_word . " ") !== false) {$removal_word_found = true;}
    }

    if ($removal_word_found && $row["candidate_name"] == "") {
      $update_qry->execute(array('', '', '', '', '', '', '', '', $row["id"]));
    } else {
      $update_qry->execute(array($gender, $first_name, $middle_name, $last_name, $name_suffix, $name_prefix, $nick_name, $display_name, $row["id"]));
    }
  }
}


#===============================================================================================
function generate_search_words()
{
  global $script_db;

  $update_qry = $script_db->prepare("INSERT INTO ca_search.contributions_search_donors_temp (id, DonorState, DonorCommitteeID, DonorWords) VALUES (?, ?, ?, ?)");
  $result = $script_db->query("SELECT id, DonorState, DonorCommitteeID, DonorNameNormalized, DonorEmployerNormalized, DonorOccupationNormalized FROM ca_search.contributions_temp");
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $word_str = " ";
    $word_data = preg_replace("/[^a-z0-9 ]+/i", "", $row["DonorNameNormalized"] . " " . $row["DonorEmployerNormalized"] . " " . $row["DonorOccupationNormalized"]);
    $words = explode(" ", $word_data);
    foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper(ltrim($word, "0")) . " ";}}
    $update_qry->execute(array($row["id"], $row["DonorState"], $row["DonorCommitteeID"], $word_str));
  }

  $update_qry = $script_db->prepare("UPDATE ca_search.smry_candidates_temp SET CandidateWords = ? WHERE MapLightCandidateNameID = ?");
  $result = $script_db->query("SELECT * FROM ca_search.smry_candidates_temp");
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $word_str = " ";
    $word_data = preg_replace("/[^a-z0-9 ]+/i", "", $row["RecipientCandidateNameNormalized"]);
    $words = explode(" ", $word_data);
    foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper(ltrim($word, "0")) . " ";}}
    $update_qry->execute(array($word_str, $row["MapLightCandidateNameID"]));
  }

  $update_qry = $script_db->prepare("UPDATE ca_search.smry_committees_temp SET CommitteeWords = ? WHERE MapLightCommitteeID = ?");
  $result = $script_db->query("SELECT * FROM ca_search.smry_committees_temp");
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $word_str = " ";
    $word_data = preg_replace("/[^a-z0-9 ]+/i", "", $row["RecipientCommitteeNameNormalized"]);
    $words = explode(" ", $word_data);
    foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper(ltrim($word, "0")) . " ";}}
    $update_qry->execute(array($word_str, $row["MapLightCommitteeID"]));
  }

  $update_qry = $script_db->prepare("UPDATE ca_search.smry_propositions_temp SET PropositionWords = ? WHERE PropositionID = ?");
  $result = $script_db->query("SELECT * FROM ca_search.smry_propositions_temp");
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $word_str = " ";
    $word_data = preg_replace("/[^a-z0-9 ]+/i", "", $row["Target"]);
    $words = explode(" ", $word_data);
    foreach ($words as $word) {if ($word != "") {$word_str .= strtoupper(ltrim($word, "0")) . " ";}}
    $update_qry->execute(array($word_str, $row["PropositionID"]));
  }
} 

