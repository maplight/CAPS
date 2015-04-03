<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<DIV CLASS=\"font_title\">Search California political contributions from 2001 through the present using the form on this page.</DIV>";
    } else {
      # Parse search form
      $parse_data = parse_search_form ($_POST);
      display_data ($parse_data);
    }
  }


  function parse_search_form ($search_data) {
    #------------------------------------------------------------------------------------------
    # Set empty query holders
    $Donor = "";
    $DonorState = "";
    $Candidate = "";
    $CandidateList = "";
    $OfficeList = "";
    $PropositionSearch = "";
    $Election = "";
    $Proposition = "";
    $Position = "";
    $Allied = "";
    $Committee = "";
    $DateRange = "";
    $ElectionCycle = "";
    $CandidateContribution = "";
    $PropositionContribution = "";
    $PDO_data = array ();

    # This is passed to the CSV file generation script to display the selected criteria
    $criteria = array ();
    $criteria["00Contributor(s)"] = "All";
    $criteria["01Contributor_State"] = "All";
    $criteria["02Recipient(s)"] = "All";
    $criteria["06Exclude_Allied_Committees"] = "No";
    $criteria["07Contribution_Dates"] = "All";
    $criteria["08Cycles"] = "All";

    #------------------------------------------------------------------------------------------
    # Build contributor search query:
    if ($search_data["contrib_select"] == "search") {
      $Donor = "";
      $search_donor = "";
      foreach (explode (";", $search_data["contributor"]) as $search_item) {
        $word_str = "";
        if (substr (trim ($search_item), 0, 1) == "\"") {$quoted = true;} else {$quoted = false;}
        foreach (explode (" ", $search_item) as $word) {
          $word = strtoupper (ltrim (preg_replace ("/[^a-z0-9 ]+/i", "", $word), "0"));
          if (trim ($word) != "") {$word_str .= "+{$word} ";}
        }
        if ($quoted) {
          $Donor .= "(\"" . trim ($word_str) . "\") ";
          $search_donor .= "\"" . $word_str . "\"";
        } else {
          $Donor .= "(" . trim ($word_str) . ") ";
          $search_donor .= $word_str;
        }
      }
      if ($Donor != "") {
        if (intval (substr ($Donor, 2, -2)) == 0) {
          if (strpos ($search_data["contributor"], ";") !== false) {
            $criteria["00Contributors"] = "Contributor contains: \"" . trim (str_replace ("+", "OR ", substr ($search_donor, 1))) . "\"";
          } else {
            $criteria["00Contributors"] = "Contributor contains: \"" . trim (str_replace ("+", "", substr ($search_donor, 1))) . "\"";
          }
          $PDO_data[] = substr ($Donor, 0, -1);
          $Donor = "(MATCH (contributions_search_donors.DonorWords) AGAINST (? IN BOOLEAN MODE))";
        } else {
          if (strpos ($search_data["contributor"], ";") !== false) {
            $criteria["00Contributors"] = "Contributor contains: \"" . trim (str_replace ("+", "OR ", substr ($search_donor, 1))) . "\"";
          } else {
            $criteria["00Contributors"] = "Contributor contains: \"" . trim (str_replace ("+", "", substr ($search_donor, 1))) . "\"";
          }
          $PDO_data[] = substr ($Donor, 0, -1);
          $PDO_data[] = intval (substr ($Donor, 2, -2));
          $criteria["00Contributors"] .= " or DonorCommitteeID is " . intval (substr ($Donor, 2, -2)); 
          $Donor = "(MATCH (contributions_search_donors.DonorWords) AGAINST (? IN BOOLEAN MODE) OR contributions_search_donors.DonorCommitteeID = ?)";
        }
      }
    }
  
    # build locations query
    if ($search_data["state_list"] != "ALL") {
      $criteria["01Contributor_State"] = $search_data["state_list"];
      $PDO_data[] = $search_data["state_list"];
      $DonorState = "contributions_search_donors.DonorState = ?";
    }

    switch ($search_data["contrib_types"]) {
      case "candidates":
        #------------------------------------------------------------------------------------------
        # Build candidate search query:
        $CandidateContribution = "contributions_search.CandidateContribution = 'Y'";
        $criteria["02Recipient(s)"] = "All candidates";

        # build office list query
        if ($search_data["office_list"] == "All Offices") {
          $OfficeList = "";
        } else {
          $criteria["03Recipient_Office"] = $search_data["office_list"];
          $PDO_data[] = $search_data["office_list"];
          $OfficeList = "smry_offices.RecipientCandidateOffice = ?";
        }
        break;

      case "search_candidates":
        # build candidate search query
        $CandidateContribution = "contributions_search.CandidateContribution = 'Y'";

        if ($search_data["match_candidate"] == "no") {
          $Candidate = "";
          $search_candidate = "";
          foreach (explode (";", $search_data["search_candidates"]) as $search_item) {
            $word_str = "";
            foreach (explode (" ", $search_item) as $word) {
              $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
              if (trim ($word) != "") {$word_str .= "+{$word} ";}
            }
            $Candidate .= "(" . trim ($word_str) . ") ";
            $search_candidate .= $word_str;
          }
          if (strpos ($search_data["search_candidates"], ";") !== false) {
            $criteria["02Recipient(s)"] = "Candidate contains: \"" . trim (str_replace ("+", "OR ", substr ($search_candidate, 1))) . "\"";
          } else {
            $criteria["02Recipient(s)"] = "Candidate contains: \"" . trim (str_replace ("+", "", substr ($search_candidate, 1))) . "\"";
          }
          if ($Candidate != "") {
            $PDO_data[] = substr ($Candidate, 0, -1);
            $Candidate = "MATCH (smry_candidates.CandidateWords) AGAINST (? IN BOOLEAN MODE)";
          }
        } else {
          $criteria["02Recipient(s)"] = "Candidate: " . $search_data["search_candidates"];
          $PDO_data[] = $search_data["search_candidates"];
          $CandidateList = "smry_candidates.RecipientCandidateNameNormalized = ?";
        }

        # build office list query
        if ($search_data["office_list"] == "All Offices") {
          $OfficeList = "";
        } else {
          $criteria["03Recipient_Office"] = $search_data["office_list"];
          $PDO_data[] = $search_data["office_list"];
          $OfficeList = "smry_offices.RecipientCandidateOffice = ?";
        }
        break; # candidates

      case "ballots":
        #------------------------------------------------------------------------------------------
        # Build ballot measure search query:
        $criteria["02Recipient(s)"] = "All ballot measures";
        $PropositionContribution = "contributions_search.BallotMeasureContribution = 'Y'";

        # build support/oppose query
        if ($search_data["position"] == "S") {
          $Position = "contributions_search.PositionID = 1";
          $criteria["04Ballot_Position"] = "SUPPORT";
        }
        if ($search_data["position"] == "O") {
          $Position = "contributions_search.PositionID = 2";
          $criteria["04Ballot_Position"] = "OPPOSE";
        }

        if ($search_data["search_propositions"] != "Search ballot measures" && $search_data["proposition_list"] == "ALL") {
          # build proposition search query
          $PropositionSearch = "";
          $search_proposition = "";
          foreach (explode (";", $search_data["search_propositions"]) as $search_item) {
            $word_str = "";
            foreach (explode (" ", $search_item) as $word) {
              $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
              if (trim ($word) != "") {$word_str .= "+{$word} ";}
            }
            $PropositionSearch .= "(" . trim ($word_str) . ") ";
            $search_proposition .= $word_str;
          }
          if ($PropositionSearch != "") {
            $PDO_data[] = substr ($PropositionSearch, 0, -1);
            $PropositionSearch = "MATCH (smry_propositions.PropositionWords) AGAINST (? IN BOOLEAN MODE)";
            if (strpos ($search_data["search_propositions"], ";") !== false) {
              $criteria["02Recipient(s)"] = "Ballot measure contains: \"" . trim (str_replace ("+", "OR ", substr ($search_proposition, 1))) . "\"";
            } else {
              $criteria["02Recipient(s)"] = "Ballot measure contains: \"" . trim (str_replace ("+", "", substr ($search_proposition, 1))) . "\"";
            }
          } 
        } else {
          if ($search_data["proposition_list"] != "ALL") {
            if (substr ($search_data["proposition_list"], 0, 3) == "ALL") {
              # build query for a specific election
              $selected_data = explode ("#", $search_data["proposition_list"]);
              $Election = "smry_propositions.Election = ?";
              $PDO_data[] = $selected_data[1];
              $criteria["05Election"] = $selected_data[1];
            } else {
              # build query for a specific proposition
              $selected_data = explode ("#", $search_data["proposition_list"]);
              $PDO_data[] = $selected_data[1]; # Target
              $PDO_data[] = $selected_data[0]; # Election
              $Proposition = "smry_propositions.Target = ?";
              $Election = "smry_propositions.Election = ?";
              $criteria["05Election"] = $selected_data[0];
              $criteria["02Recipient(s)"] = "Ballot measure: " . $selected_data[1];
            }
          }
        }

        # exclude allied committees query
        if (isset ($search_data["exclude"])) {
          $Allied = "contributions_search.AlliedCommittee = 'N'";
          $criteria["06Exclude_Allied_Committees"] = "Yes";
        }
        break; # ballots

      case "committees":
        #------------------------------------------------------------------------------------------
        # build committee search query
        if ($search_data["match_committee"] == "no") {
          $Committee = "";
          $search_committee = "";
          foreach (explode (";", $search_data["search_committees"]) as $search_item) {
            $word_str = "";
            if (substr (trim ($search_item), 0, 1) == "\"") {$quoted = true;} else {$quoted = false;}
            foreach (explode (" ", $search_item) as $word) {
              $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
              if (trim ($word) != "") {$word_str .= "+{$word} ";}
            }
            if ($quoted) {
              $Committee .= "(\"" . trim ($word_str) . "\") ";
              $search_committee .= "\"" . $word_str . "\"";
            } else {
              $Committee .= "(" . trim ($word_str) . ") ";
              $search_committee .= $word_str;
            }
          }
          if ($Committee != "") {
            if (intval (substr ($Committee, 2, -2)) == 0) {
              if (strpos ($search_data["search_committees"], ";") !== false) {
                $criteria["02Recipient(s)"] = "Committee contains: \"" . trim (str_replace ("+", "OR ", substr ($search_committee, 1))) . "\"";
              } else {
                $criteria["02Recipient(s)"] = "Committee contains: \"" . trim (str_replace ("+", "", substr ($search_committee, 1))) . "\"";
              }
              $PDO_data[] = substr ($Committee, 0, -1);
              $Committee = "MATCH (smry_committees.CommitteeWords) AGAINST (? IN BOOLEAN MODE)";
            } else {
              if (strpos ($search_data["search_propositions"], ";") !== false) {
                $criteria["02Recipient(s)"] = "Committee contains: \"" . trim (str_replace ("+", "OR ", substr ($search_committee, 1))) . "\"";
              } else {
                $criteria["02Recipient(s)"] = "Committee contains: \"" . trim (str_replace ("+", "", substr ($search_committee, 1))) . "\"";
              }
              $criteria["02Recipient(s)"] .= " or RecipientCommitteeID is " . intval (substr ($Committee, 2, -2));
              $PDO_data[] = substr ($Committee, 0, -1);
              $PDO_data[] = intval (substr ($Committee, 2, -2));
              $Committee = "(MATCH (smry_committees.CommitteeWords) AGAINST (? IN BOOLEAN MODE) OR smry_committees.RecipientCommitteeID = ?)";
            }
          }
        } else {
          $criteria["02Recipient(s)"] = "Committee: " . $search_data["search_committees"];
          $PDO_data[] = $search_data["search_committees"];
          $Committee = "smry_committees.RecipientCommitteeNameNormalized = ?";
        }
        break; # committees
    }

    #------------------------------------------------------------------------------------------
    # Build dates / cycles query
    switch ($search_data["date_select"]) {
      case "range":
        # build date range query
        $start_date = strtotime ($search_data["start_date"]);
        $end_date = strtotime ($search_data["end_date"]);
        if ($start_date == "" && $end_date == "") {
          $DateRange = "";
        } else if ($start_date == "") {
          $PDO_data[] = date ("Y-m-d", $end_date);
          $DateRange = "contributions_search.TransactionDateEnd <= ?";
          $criteria["07Contribution_Dates"] = "Ending: " . date ("Y-m-d", $end_date);
        } else if ($end_date == "") {
          $PDO_data[] = date ("Y-m-d", $start_date);
          $DateRange = "contributions_search.TransactionDateStart >= ?";
          $criteria["07Contribution_Dates"] = "Starting: " . date ("Y-m-d", $start_date);
        } else {
          $PDO_data[] = date ("Y-m-d", $start_date);
          $PDO_data[] = date ("Y-m-d", $end_date);
          $DateRange = "contributions_search.TransactionDateStart >= ? AND contributions_search.TransactionDateEnd <= ?";
          $criteria["07Contribution_Dates"] = date ("Y-m-d", $start_date) . " - " . date ("Y-m-d", $end_date);
        }
        break;

      case "cycle":
        # build election cycle query
        if (isset ($search_data["cycles"])) {
          $criteria["08Cycles"] = "";
          foreach ($search_data["cycles"] as $cycle) {
            $PDO_data[] = $cycle;
            $ElectionCycle .= "contributions_search.ElectionCycle = ? OR ";
            $criteria["08Cycles"] .= $cycle . ", ";
          }
          $ElectionCycle = substr ($ElectionCycle, 0, -4); # Remove the final OR
          $criteria["08Cycles"] = substr ($criteria["08Cycles"], 0, -2); # Remove the final ,
        }
        break;
    }

    #------------------------------------------------------------------------------------------
    # Build sub-query components
    $summary_type = "";
    $donor_where = "";
    $candidate_where = "";
    $proposition_where = "";
    $committee_where = "";
    $date_where = "";

    # create donor query
    if ($Donor != "") {$donor_where .= "{$Donor} AND "; $summary_type .= "D";}
    if ($DonorState != "") {$donor_where .= "{$DonorState} AND ";}
    if ($donor_where != "") {$donor_where = substr ($donor_where, 0, -5);} # remove extra AND
    
    # create candidate query
    if ($CandidateList == "") {
      if ($Candidate != "") {$candidate_where .= "({$Candidate}) AND "; $summary_type .= "C";}
    } else {
      $candidate_where .= "{$CandidateList} AND "; $summary_type .= "C";
    }
    if ($OfficeList != "") {$candidate_where .= "{$OfficeList} AND ";}
    if ($CandidateContribution != "") {$candidate_where .= "$CandidateContribution AND ";}
    if ($candidate_where != "") {$candidate_where = substr ($candidate_where, 0, -5);} # Remove the final AND

    # create proposition query
    if ($Proposition == "") {
      if ($PropositionSearch != "") {$proposition_where .= "({$PropositionSearch}) AND ";}
    } else {
      $proposition_where .= "{$Proposition} AND ";
    }
    if ($Election != "") {$proposition_where .= "{$Election} AND "; $summary_type .= "E";}
    if ($Position != "") {$proposition_where .= "{$Position} AND ";}
    if ($Allied != "") {$proposition_where .= "{$Allied} AND ";}
    if ($PropositionContribution != "") {$proposition_where .= "$PropositionContribution AND ";}
    if ($proposition_where != "") {$proposition_where = substr ($proposition_where, 0, -5);} # Remove the final AND

    # create committee query
    if ($Committee != "") {$committee_where .= "({$Committee}) AND "; $summary_type .= "M";}
    if ($committee_where != "") {$committee_where = substr ($committee_where, 0, -5);} # remove extra AND

    # create date query
    if ($DateRange != "") {$date_where .= "({$DateRange}) AND ";}
    if ($ElectionCycle != "") {$date_where .= "({$ElectionCycle}) AND ";}
    if ($date_where != "") {$date_where = substr ($date_where, 0, -5);} # remove extra AND

    # generate full query where
    $where = "";
    if ($donor_where != "") {$where .= "{$donor_where} AND ";}
    if ($candidate_where != "" && $proposition_where != "") {
      $where .= "({$candidate_where}) AND ({$proposition_where}) AND ";
    } else {
      if ($candidate_where != "") {$where .= "{$candidate_where} AND ";}
      if ($proposition_where != "") {$where .= "{$proposition_where} AND ";}
    }
    if ($committee_where != "") {$where .= "{$committee_where} AND ";}
    if ($date_where != "") {$where .= "{$date_where} AND ";}
    if ($where != "") {$where = "WHERE " . substr ($where, 0, -5);} # remove extra AND

    $parse_data = array ($where, $summary_type, $criteria, $PDO_data);
    return $parse_data;
  }


  function display_data ($parse_data) {
    global $web_db;

    # Set this variable to control the maximum number of records that the download csv file is available.
    $max_download_records = 150000;

    $where = $parse_data[0];
    $summary_type = $parse_data[1];

# This code was used to only show the summaries from the quicksearch, they will now always be shown.
#    if (isset ($_POST["search_btn"])) {
#      $show_summary = "no";
#    } else {
#      if (isset ($_POST["show_summary"])) {
#        $show_summary = $_POST["show_summary"];
#      } else {
#        $show_summary = "yes";
#      }
#    }

    $show_summary = "yes";

    if ($where == "") {
      echo "You have not entered any search data, please select a criteria on the side.";
    } else {
      $search_join = "";
      if (strpos ($where, "contributions_search_donors") !== false) {$search_join .= "INNER JOIN contributions_search_donors  ON (contributions_search.id = contributions_search_donors.id) ";}
      if (strpos ($where, "smry_candidates") !== false) {$search_join .= "INNER JOIN smry_candidates USING (MapLightCandidateNameID) ";}
      if (strpos ($where, "smry_offices") !== false) {$search_join .= "INNER JOIN smry_offices USING (MapLightCandidateOfficeID) ";}
      if (strpos ($where, "smry_committees") !== false) {$search_join .= "INNER JOIN smry_committees USING (MapLightCommitteeID) ";}
      if (strpos ($where, "smry_propositions") !== false) {$search_join .= "INNER JOIN smry_propositions USING (PropositionID) ";}

      $result = $web_db->prepare("SELECT COUNT(*) AS records, SUM(TransactionAmount) AS total FROM (SELECT DISTINCT ContributionID, TransactionAmount FROM contributions_search {$search_join} {$where}) AS UniqueContribs");
      $result->execute($parse_data[3]);
      $totals_row = $result->fetchAll(PDO::FETCH_ASSOC); $totals_row = $totals_row[0];
      $result = $web_db->prepare("SELECT COUNT(DISTINCT ContributionID) AS records FROM contributions_search {$search_join} {$where}");
      $result->execute($parse_data[3]); 
      $record_count = $result->fetchAll(PDO::FETCH_ASSOC); $record_count = $record_count[0];

      if ($record_count["records"] == 0) {
        echo "Your search did not return any records.";
      } else {
        # Calculate total pages based on display rows
        if (isset ($_POST["return_rows"])) {$limit = $_POST["return_rows"];} else {$limit = 10;}
        $total_pages = intval (($record_count["records"] - 1) / $limit) + 1;
 
        # Get page # to display
        if (isset ($_POST["page"])) {$page = $_POST["page"];} else {$page = 1;}
        if (isset ($_POST["page_button"])) {
          switch ($_POST["page_button"]) {
            case "Next": $page++; break;
            case "Previous": $page--; break;
            default: $page = $_POST["page_button"]; break;
          }
        }
 
        # Reset the page to 1 if you selected a smaller set then is currently displayed, or if the search button was used
        if ($page > $total_pages || isset ($_POST["search_btn"])) {$page = 1;}

        # Determine rows being displayed
        $first_row = ($page - 1) * $limit + 1;
        $last_row = $first_row + $limit - 1;
        if ($first_row > $record_count["records"]) {$first_row = 1;}
        if ($last_row > $record_count["records"]) {$last_row = $record_count["records"];}

        $sort = "contributions_search.TransactionDateEnd";
        $sort_order = "DESC";
        if (isset ($_POST["sort"])) {
          $sort = $_POST["sort"];
          $sort_order = $_POST["sort_order"];
        }

        $field_set = "";
        $fields = array ("RecipientCandidateNameNormalized|Recipient Name|",
                         "RecipientCommitteeNameNormalized|Recipient Committee|",
                         "RecipientCommitteeID|Recipient Committee ID|",
                         "RecipientCandidateOffice|Office Sought|",
                         "ballot_measures|Ballot Measure(s)|MultiLine",
                         "DonorNameNormalized|Contributor Name|",
                         "DonorCommitteeID|Contributor ID|",
                         "TransactionAmount|Amount|Currency",
                         "TransactionDateEnd|Date|Date",
                         "DonorEmployerNormalized|Contributor Employer|",
                         "DonorOccupationNormalized|Contributor Occupation|",
                         "DonorState|Contributor State|");
        if (isset ($_POST["field_list"])) {$field_set = $_POST["field_list"];}
        if (isset ($_POST["fields"])) {$field_set = $_POST["fields"];}

        if ($field_set == "Show more fields") {
          $fields = array ("RecipientCandidateNameNormalized|Recipient Name|",
                           "RecipientCommitteeNameNormalized|Recipient Committee|",
                           "RecipientCommitteeID|Recipient Committee ID|",
                           "RecipientCandidateOffice|Office Sought|",
                           "RecipientCandidateDistrict|District|",
                           "ballot_measures|Ballot Measure(s)|MultiLine",
                           "DonorNameNormalized|Contributor Name|",
                           "DonorCommitteeID|Contributor ID|",
                           "TransactionAmount|Amount|Currency",
                           "TransactionDateEnd|Date|Date",
                           "DonorEmployerNormalized|Contributor Employer|",
                           "DonorOccupationNormalized|Contributor Occupation|",
                           "DonorState|Contributor State|",
                           "DonorZipCode|Contributor Zip Code|",
                           "DonorCity|Contributor City|",
                           "TransactionType|Transaction Type|",
                           "Election|Election|Date",
                           "ElectionCycle|Cycle|");
        }

        $sort_fields = array ("RecipientCandidateNameNormalized|Recipient Name",
                              "RecipientCommitteeNameNormalized|Recipient Committee",
                              "RecipientCommitteeID|Recipient Committee ID",
                              "RecipientCandidateOffice|Office Sought",
                              "RecipientCandidateDistrict|District",
                              "ballot_measures|Ballot Measure(s)",
                              "DonorNameNormalized|Contributor Name",
                              "DonorCommitteeID|Contributor ID",
                              "TransactionAmount|Amount",
                              "TransactionDateEnd|Date",
                              "DonorEmployerNormalized|Contributor Employer",
                              "DonorOccupationNormalized|Contributor Occupation",
                              "DonorState|Contributor State",
                              "DonorZipCode|Contributor Zip Code",
                              "DonorCity|Contributor City",
                              "TransactionType|Transaction Type",
                              "Election|Election",
                              "ElectionCycle|Cycle");

        $result = $web_db->prepare("SELECT contributions.*, ballot_measures FROM contributions LEFT JOIN contributions_grouped USING (ContributionID) INNER JOIN contributions_search ON (contributions.id = contributions_search.id) {$search_join} {$where} GROUP BY ContributionID ORDER BY {$sort} {$sort_order} LIMIT " . (($page - 1) * $limit) . ",{$limit}");
        $result->execute($parse_data[3]);
        $rows_returned = $result->rowCount();

        echo "<div id=\"caps_results\">";

        $results_tooltip = "This is the total amount received, including both itemized and unitemized contributions. If you searched for contributions to multiple ballot measures, please note that a single contribution to a multi-measure committee may be counted multiple times toward each measure supported/opposed by that committee.";
        if ($show_summary == "no") {
          echo "<h1 class=\"font_large_header\">Search Results</h1>";
          echo "<hr class=\"caps_hr1\">";
          echo "<div class=\"font_results_header\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions ";
          display_tooltip ($results_tooltip, -180, 10, 250, "");
          echo "<h2 class=\"font_large_header caps_title1\">Contributions</h2>";
          echo "<hr class=\"caps_hr1\">";
        } else {
          for ($i = 0; $i < strlen ($summary_type); $i++) {
            switch (substr ($summary_type, $i, 1)) {
              case "D":
                echo "<div class=\"font_results_header\"><strong>" . strtoupper ($_POST["contributor"]) . "</strong> has contributed</div>";
                echo "<div class=\"font_results_header\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions ";
                display_tooltip ($results_tooltip, -180, 10, 250, "");
                echo "<div id=\"caps_breakdown_box\">";
                $employee = "";
                $result2 = $web_db->prepare("SELECT IsEmployee, CandidateContribution, BallotMeasureContribution, SUM(TransactionAmount) AS TotalAmount FROM (SELECT DISTINCT ContributionID, IsEmployee, CandidateContribution, BallotMeasureContribution, TransactionAmount FROM contributions_search {$search_join} {$where}) AS UniqueContributions GROUP BY IsEmployee, CandidateContribution, BallotMeasureContribution ORDER BY IsEmployee, CandidateContribution, BallotMeasureContribution");
                $result2->execute($parse_data[3]);
                foreach ($result2->fetchAll(PDO::FETCH_ASSOC) as $row2) {
                  if ($row2["IsEmployee"] != $employee) {
                    if ($row2["IsEmployee"] == "Y") {echo "<b>Employee Contributions</b><br>";} else {if ($employee == "Y") {echo "&nbsp;<br>";} echo "<b>Organizational Contributions</b><br>";}
                    $employee = $row2["IsEmployee"];
                  }
                  if ($row2["CandidateContribution"] == "Y" && $row2["BallotMeasureContribution"] == "N") {echo "&nbsp;&nbsp;&nbsp;<b>$" . number_format ($row2["TotalAmount"], 2, ".", ",") . "</b> to <b>candidates</b><br>";}
                  if ($row2["CandidateContribution"] == "N" && $row2["BallotMeasureContribution"] == "Y") {echo "&nbsp;&nbsp;&nbsp;<b>$" . number_format ($row2["TotalAmount"], 2, ".", ",") . "</b> to <b>ballot measures</b><br>";}
                  if ($row2["CandidateContribution"] == "N" && $row2["BallotMeasureContribution"] == "N") {echo "&nbsp;&nbsp;&nbsp;<b>$" . number_format ($row2["TotalAmount"], 2, ".", ",") . "</b> to <b>other committees</b><br>";}
                }
                echo "</div>";
                echo "<hr class=\"caps_hr1\">";
                break;

              case "C":
                echo "<div class=\"font_results_header\"><strong>" . strtoupper ($_POST["search_candidates"]) . "</strong> has received</div>";
                echo "<div class=\"font_results_header\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions ";
                display_tooltip ($results_tooltip, -180, 10, 250, "");
                echo "<div id=\"caps_breakdown_box\">";
                $result2 = $web_db->prepare("SELECT RecipientCommitteeNameNormalized, RecipientCommitteeID, COUNT(*) AS TotalCount, SUM(TransactionAmount) AS TotalAmount FROM (SELECT DISTINCT ContributionID, MapLightCommitteeID, RecipientCommitteeNameNormalized, RecipientCommitteeID, TransactionAmount FROM contributions_search INNER JOIN smry_committees USING (MapLightCommitteeID) {$search_join} {$where}) AS UniqueContributions GROUP BY MapLightCommitteeID ORDER BY RecipientCommitteeID, RecipientCommitteeNameNormalized");
                $result2->execute($parse_data[3]);
                foreach ($result2->fetchAll(PDO::FETCH_ASSOC) as $row2) {
                  echo "<b>({$row2["RecipientCommitteeID"]}) {$row2["RecipientCommitteeNameNormalized"]}</b> has raised $" . number_format ($row2["TotalAmount"], 2, ".", ",") . " in " . number_format ($row2["TotalCount"], 0, ".", ",") . " contributions<br>";
                }
                echo "</div> <!-- end caps_breakdown_box -->";
                echo "<hr class=\"caps_hr1\">";
                break;            

              case "E":
                $election = substr ($_POST["proposition_list"], 4);
                if (intval (strtotime ($election)) > 0) {
                  $election_date = date ("F Y", strtotime ($election));
                  echo "<div class=\"font_results_header\"><strong>Ballot Measures</strong> on the {$election_date} ballot have received ";
                  echo "<div class=\"font_results_header\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions ";
                  display_tooltip ($results_tooltip, -180, 10, 250, "");
                  echo "</div>";
                  echo "<div id=\"caps_breakdown_box\">";
                  $result2 = $web_db->prepare("SELECT Target, COUNT(*) AS TotalCount, SUM(TransactionAmount) AS TotalAmount, SUM(IF(PositionID = 1,1,0)) AS SupportCount, SUM(IF(PositionID=1,TransactionAmount,0)) AS SupportAmount, SUM(IF(PositionID = 2,1,0)) AS OpposeCount, SUM(IF(PositionID=2,TransactionAmount,0)) AS OpposeAmount FROM (SELECT DISTINCT ContributionID, Target, PositionID, TransactionAmount FROM contributions_search {$search_join} {$where}) AS UniqueContributions GROUP BY Target ORDER BY Target");
                  $result2->execute($parse_data[3]);
                  foreach ($result2->fetchAll(PDO::FETCH_ASSOC) as $row2) {
                    if (strpos ($row2["Target"], "-") !== false) {
                      echo "<p><b>" . substr ($row2["Target"], 0, strrpos ($row2["Target"], " - ")) . "</b>" . substr ($row2["Target"], strrpos ($row2["Target"], " - ")) . "<br>";
                    } else {
                      echo "<p><b>{$row2["Target"]}</b><br>";
                    }
                    echo "&nbsp;&nbsp;&nbsp;$" . number_format ($row2["TotalAmount"], 2, ".", ",") . " total raised - " . number_format ($row2["TotalCount"], 0, ".", ",") . " contributions<br>";
                    echo "&nbsp;&nbsp;&nbsp;- Support: $" . number_format ($row2["SupportAmount"], 2, ".", ",") . " raised - " . number_format ($row2["SupportCount"], 0, ".", ",") . " contributions<br>";
                    echo "&nbsp;&nbsp;&nbsp;- Oppose: $" . number_format ($row2["OpposeAmount"], 2, ".", ",") . " raised - " . number_format ($row2["OpposeCount"], 0, ".", ",") . " contributions";
                    echo "</p>";
                  }
                  echo "</div>";
                  echo "<hr class=\"caps_hr1\">";
                } else {
                  $election_date = date ("F Y", strtotime (substr ($_POST["proposition_list"], 0, 10)));
                  echo "<div class=\"font_results_header\"><strong>" . substr ($_POST["proposition_list"], 11) . "</strong> on the {$election_date} ballot has received</div>";
                  echo "<div class=\"font_results_header\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions ";
                  display_tooltip ($results_tooltip, -180, 10, 250, "");
                  echo "<h2 class=\"font_large_header caps_title1\">Contributions</h2>";
                  echo "<hr class=\"caps_hr1\">";
                }
                break;            

              case "M":
                echo "<div class=\"font_results_header\"><strong>{$_POST["search_committees"]}</strong> received</div>";
                echo "<div class=\"font_results_header\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions ";
                display_tooltip ($results_tooltip, -180, 10, 250, "");
                echo "<h2 class=\"font_large_header caps_title1\">Contributions</h2>";
                echo "<hr class=\"caps_hr1\">";
                break;
            }
          }
        }

        $criteria = urlencode (serialize ($parse_data[2]));
        $data = urlencode (serialize ($parse_data[3]));

        echo "<div id=\"caps_filter_box\">";
        echo "Show";
        echo "<select id=\"show\" name=\"return_rows\" class=\"font_input input_border caps_select4\" alt=\"Number of Rows to Display\">";
        if ($limit == 10) {echo "<option selected>10</option>";} else {echo "<option>10</option>";}
        if ($limit == 25) {echo "<option selected>25</option>";} else {echo "<option>25</option>";}
        if ($limit == 50) {echo "<option selected>50</option>";} else {echo "<option>50</option>";}
        if ($limit == 100) {echo "<option selected>100</option>";} else {echo "<option>100</option>";}
        echo "</select>";
        echo "rows&nbsp;&nbsp;&nbsp;&nbsp;Sort by";
        echo "<select id=\"sort\" name=\"sort\" class=\"font_input input_border caps_select4\" alt=\"Sort By\">";
        foreach ($sort_fields as $sort_item) {
          $item_data = explode ("|", $sort_item); 
          if ($sort == $item_data[0]) {echo "<option value=\"{$item_data[0]}\" SELECTED>{$item_data[1]}</option>";} else {echo "<option value=\"{$item_data[0]}\">{$item_data[1]}</option>";}
        }
        echo "</select><select id=\"sort_order\" name=\"sort_order\" class=\"font_input input_border caps_select4\" alt=\"Sort Order\">";
        if ($sort_order == "ASC") {
          echo "<option value=\"ASC\" SELECTED>Ascending</option><option VALUE=\"DESC\">Descending<option>";
        } else {
          echo "<option value=\"ASC\">Ascending</option><option VALUE=\"DESC\" SELECTED>Descending<option>";
        }
        echo "</select>";
        echo "<input type=\"submit\" value=\"Update\" id=\"caps_update_btn\">";

        # Do not display the download option if there are more records then allowed to download
        if ($totals_row["records"] <= $max_download_records) { 
          echo "<div class=\"right\">";
          echo "<a href=\"download_csv.php?w=" . urlencode ($where) . "&d={$data}&c={$criteria}\" class=\"download_csv\">Download CSV</a>&nbsp;";
          display_tooltip ("Download the search results as a CSV file, which can be opened in most spreadsheet software.", -180, 10, 160, "");
          echo "</div> <!-- end download_box -->";
        }

        echo "</div> <!-- end filter_box -->";

        echo "<div class=\"font_input\">Showing <strong>" . number_format ($first_row, 0, ".", ",") . "</strong> to <strong>" . number_format ($last_row, 0, ".", ",") . "</strong> of <strong>" . number_format ($record_count["records"], 0, ".", ",") . "</strong> rows ";
        $field_msg = "Show more fields";
        if ($field_set == "Show more fields") {$field_msg = "Show fewer fields";}
        echo "<input type=\"submit\" name=\"fields\" value=\"{$field_msg}\" id=\"caps_field_btn\">";
        display_tooltip ("Show more columns in the table for additional information on contributions.", -180, 10, 160, "");
        echo "</div>";

        echo "<div id=\"caps_table_box\">";
        echo "<table title=\"search table\" summary=\"search table\" class=\"caps_table\">";
        echo "<thead>";
        echo "<tr>";

        $count = 1;
        foreach ($fields as $field) {
          $field_data = explode ("|", $field);
          echo "<th id=\"c{$count}\">{$field_data[1]}</th>";
          $count++;
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
          echo "<tr>";
          $count = 1;
          foreach ($fields as $field) {
            $field_data = explode ("|", $field);
            switch ($field_data[2]) {
              case "Date":
                if (date ("F j, Y", strtotime ($row[$field_data[0]])) == "December 31, 1969") {
                  echo "<td headers=\"c{$count}\"><I>unknown</I></td>";
                } else {
                  echo "<td headers=\"c{$count}\">" . date ("M j, Y", strtotime ($row[$field_data[0]])) . "</td>";
                }
                break;

              case "Currency":
                echo "<td headers=\"c{$count}\" style=\"text-align:right\">$" . number_format($row[$field_data[0]], 2, ".", ",") . "</td>";
                break;

              case "MultiLine":
                echo "<td headers=\"c{$count}\">" . str_replace (" | ", "<hr>", $row[$field_data[0]]) . "</td>";
                break;

              default: 
                echo "<td headers=\"c{$count}\">{$row[$field_data[0]]}</td>";
                break;
            }
            $count++;
          }
          echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div> <!-- end caps_table_box -->";

        # Pagination section
        echo "<center>";
        echo "<input type=\"hidden\" name=\"page\" value=\"{$page}\">";
        echo "<input type=\"hidden\" name=\"field_list\" value=\"{$field_set}\">";
        echo "<input type=\"hidden\" name=\"show_summary\" value=\"{$show_summary}\">";
        if ($total_pages > 1) {
          if ($page > 1) {echo "<input type=\"submit\" name=\"page_button\" value=\"Previous\" id=\"caps_previous_btn\">";}
          if ($total_pages >= 3) {
            for ($page_btn = 1; $page_btn <= $total_pages; $page_btn++) {
              if ($page == $page_btn) {
                echo "<input type=\"submit\" name=\"page_button\" value=\"{$page_btn}\" id=\"caps_current_page_btn\">";
              } else {
                echo "<input type=\"submit\" name=\"page_button\" value=\"{$page_btn}\" id=\"caps_page_btn\">";
              }
              if ($page_btn == 10) {break;}
            }
          }
          if ($page < $total_pages && $page < 10) {echo "<input type=\"submit\" name=\"page_button\" value=\"Next\" id=\"caps_next_btn\">";}
        }
        $result = $web_db->query("SELECT * FROM smry_last_update"); $row = $result->fetchAll(PDO::FETCH_ASSOC); $last_update = $row[0]["LastUpdate"];
        echo "<p>&nbsp;</p>";
        echo "<div class=\"font_input\"><p>This page will not display more than 1,000 entries.</p>";

        # Do not display the download option if there are more records then allowed to download
        if ($totals_row["records"] <= $max_download_records) {echo "(To view the entire set of search results, <a href=\"download_csv.php?w=" . urlencode ($where) . "&d={$data}&c={$criteria}\" class=\"download_csv\">download the CSV</a> file.)";}

        echo "</div><br><div class=\"font_small\">Contributions data is current as of " . date ("F j, Y", strtotime ($last_update)) . ".</div><br>";
        echo "</center>";

        echo "</div> <!-- end caps_results ->";
      }
    }
  }


  function display_tooltip ($text, $pos_x, $pos_y, $width, $position) {
    echo "<img src=\"img/infotool.png\" class=\"{$position} caps_info\" onMouseOver=\"this.src='img/infotool-hover.png'; display_tooltip(event, '{$text}', {$pos_x}, {$pos_y}, {$width});\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" alt=\"{$text}\">";
  }
?>
