<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<DIV CLASS=\"caps_title2\">Search political contributions from 2001 through the present, using the controls on the left.</DIV>";
    } else {
      # Parse search form
      $where = parse_search_form ($_POST);
      display_data ($where);
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


    #------------------------------------------------------------------------------------------
    # Build contributor search query:
    if ($search_data["contrib_select"] == "search") {
      $Donor = "";
      foreach (explode (";", $search_data["contributor"]) as $search_item) {
        $word_str = "";
        foreach (explode (" ", $search_item) as $word) {
          $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
          $word_str .= "+{$word} ";
        }
        $Donor .= "(" . trim ($word_str) . ") ";
      }
      if ($Donor != "") {$Donor = "MATCH (contributions_search.DonorWords) AGAINST ('" . substr ($Donor, 0, -1) . "' IN BOOLEAN MODE)";}
    }
  
    # build locations query
    if ($search_data["state_list"] != "ALL") {$DonorState = "contributions_search.DonorState = '{$search_data["state_list"]}'";}


    switch ($search_data["contrib_types"]) {
      case "candidates":
        #------------------------------------------------------------------------------------------
        # Build candidate search query:
        $CandidateContribution = "contributions_search.CandidateContribution = 'Y'";

        switch ($search_data["cand_select"]) {
          case "search":
            # build candidate search query
            if ($search_data["candidate_list"] == "Select candidate") {
              $Candidate = "";
              foreach (explode (";", $search_data["search_candidates"]) as $search_item) {
                $word_str = "";
                foreach (explode (" ", $search_item) as $word) {
                  $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
                  $word_str .= "+{$word} ";
                }
                $Candidate .= "(" . trim ($word_str) . ") ";
              }
              if ($Candidate != "") {$Candidate = "MATCH (smry_candidates.CandidateWords) AGAINST ('" . substr ($Candidate, 0, -1) . "' IN BOOLEAN MODE)";}
            } else {
              $CandidateList = "smry_candidates.RecipientCandidateNameNormalized = '" . addslashes ($search_data["candidate_list"]) . "'";
            }
            break;

          case "office":
            # build office list query
            $OfficeList = "smry_offices.RecipientCandidateOffice = '" . addslashes ($search_data["office_list"]) . "'";
            break;
        }
        break; # candidates

      case "ballots":
        #------------------------------------------------------------------------------------------
        # Build ballot measure search query:
        $PropositionContribution = "contributions_search.BallotMeasureContribution = 'Y'";

        if ($search_data["search_propositions"] != "Search propositions" && $search_data["proposition_list"] == "ALL") {
          # build proposition search query
          $PropositionSearch = "";
          foreach (explode (";", $search_data["search_propositions"]) as $search_item) {
            $word_str = "";
            foreach (explode (" ", $search_item) as $word) {
              $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
              $word_str .= "+{$word} ";
            }
            $PropositionSearch .= "(" . trim ($word_str) . ") ";
          }
          if ($PropositionSearch != "") {$PropositionSearch = "MATCH (smry_propositions.PropositionWords) AGAINST ('" . substr ($PropositionSearch, 0, -1) . "' IN BOOLEAN MODE)";}
        } else {
          if ($search_data["proposition_list"] != "ALL") {
            if (substr ($search_data["proposition_list"], 0, 3) == "ALL") {
              # build query for a specific election
              $selected_data = explode ("#", $search_data["proposition_list"]);
              $Election = "smry_propositions.Election = '" . $selected_data[1] . "'";
            } else {
              # build query for a specific proposition
              $selected_data = explode ("#", $search_data["proposition_list"]);
              $Election = "smry_propositions.Election = '" . $selected_data[0] . "'";
              $Proposition = "smry_propositions.Target = '" . addslashes ($selected_data[1]) . "'";
            }
          }
        }

        # build support/oppose query
        if ($search_data["position"] == "S") {$Position = "contributions_search.PositionID = 1";}
        if ($search_data["position"] == "O") {$Position = "contributions_search.PositionID = 2";}

        # exclude allied committees query
        if (isset ($search_data["exclude"])) {$Allied = "contributions_search.AlliedCommittee = 'N'";}
        break; # ballots

      case "committees":
        #------------------------------------------------------------------------------------------
        # build committee search query
        $Committee = "";
        foreach (explode (";", $search_data["committee_search"]) as $search_item) {
          $word_str = "";
          foreach (explode (" ", $search_item) as $word) {
            $word = strtoupper (preg_replace ("/[^a-z0-9 ]+/i", "", $word));
            $word_str .= "+{$word} ";
          }
          $Committee .= "(" . trim ($word_str) . ") ";
        }
        if ($Committee != "") {$Committee = "MATCH (smry_committees.CommitteeWords) AGAINST ('" . substr ($Committee, 0, -1) . "' IN BOOLEAN MODE)";}
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
          $DateRange = "contributions_search.TransactionDateEnd <= '" . date ("Y-m-d", $end_date) . "'";
        } else if ($end_date == "") {
          $DateRange = "contributions_search.TransactionDateStart >= '" . date ("Y-m-d", $start_date) . "'";
        } else {
          $DateRange = "contributions_search.TransactionDateStart >= '" . date ("Y-m-d", $start_date) . "' AND contributions_search.TransactionDateEnd <= '" . date ("Y-m-d", $end_date) . "'";
        }
        break;

      case "cycle":
        # build election cycle query
        if (isset ($search_data["cycles"])) {
          foreach ($search_data["cycles"] as $cycle) {
            $ElectionCycle .= "contributions_search.ElectionCycle = $cycle OR ";
          }
          $ElectionCycle = substr ($ElectionCycle, 0, -4); # Remove the final OR
        }
        break;
    }


    #------------------------------------------------------------------------------------------
    # Build sub-query components
    $donor_where = "";
    $candidate_where = "";
    $proposition_where = "";
    $committee_where = "";
    $date_where = "";

    # create donor query
    if ($Donor != "") {$donor_where .= "{$Donor} AND ";}
    if ($DonorState != "") {$donor_where .= "{$DonorState} AND ";}
    if ($donor_where != "") {$donor_where = substr ($donor_where, 0, -5);} # remove extra AND
    
    # create candidate query
    if ($CandidateList == "") {
      if ($Candidate != "") {$candidate_where .= "({$Candidate}) AND ";}
    } else {
      $candidate_where .= "{$CandidateList} AND ";
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
    if ($Election != "") {$proposition_where .= "{$Election} AND ";}
    if ($Position != "") {$proposition_where .= "{$Position} AND ";}
    if ($Allied != "") {$proposition_where .= "{$Allied} AND ";}
    if ($PropositionContribution != "") {$proposition_where .= "$PropositionContribution AND ";}
    if ($proposition_where != "") {$proposition_where = substr ($proposition_where, 0, -5);} # Remove the final AND

    # create committee query
    if ($Committee != "") {$committee_where .= "({$Committee}) AND ";}
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

    return $where;
  }


  function display_data ($where) {
    if ($where == "") {
      echo "You have not entered any search data, please select a criteria on the side.";
    } else {
      $search_join = "";

      if (strpos ($where, "smry_candidates") !== false) {$search_join .= "INNER JOIN smry_candidates USING (RecipientCandidateNameID) ";}
      if (strpos ($where, "smry_offices") !== false) {$search_join .= "INNER JOIN smry_offices USING (RecipientCandidateOfficeID) ";}
      if (strpos ($where, "smry_committees") !== false) {$search_join .= "INNER JOIN smry_committees USING (RecipientCommitteeID) ";}
      if (strpos ($where, "smry_propositions") !== false) {$search_join .= "INNER JOIN smry_propositions USING (PropositionID) ";}

      $result = my_query ("SELECT COUNT(*) AS records, SUM(TransactionAmount) AS total FROM contributions_search {$search_join} {$where}");
      $totals_row = $result->fetch_assoc();

      if ($totals_row["records"] == 0) {
        echo "Your search did not return any records.";
      } else {
        # Calculate total pages based on display rows
        if (isset ($_POST["return_rows"])) {$limit = $_POST["return_rows"];} else {$limit = 10;}
        $total_pages = intval (($totals_row["records"] - 1) / $limit) + 1;
 
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
        if ($first_row > $totals_row["records"]) {$first_row = 1;}
        if ($last_row > $totals_row["records"]) {$last_row = $totals_row["records"];}

        $sort = "contributions_search.TransactionDateEnd DESC";
        if (isset ($_POST["sort"])) {
          $sort = $_POST["sort"];
        }
 
        $field_set = "";
        $fields = array ("RecipientCandidateNameNormalized|Recipient Name|",
                         "RecipientCommitteeNameNormalized|Recipient Committee|",
                         "RecipientCandidateOffice|Office|",
                         "DonorNameNormalized|Contributor Name|",
                         "DonorEmployerNormalized|Contributor Employer|",
                         "DonorOccupationNormalized|Contributor Occupation|",
                         "DonorOrganization|Contributor Organization|",
                         "DonorState|Contributor State|",
                         "TransactionDateEnd|Date|Date",
                         "TransactionAmount|Amount|Currency");

        if (isset ($_POST["field_list"])) {$field_set = $_POST["field_list"];}
        if (isset ($_POST["fields"])) {$field_set = $_POST["fields"];}

        if ($field_set == "Show more fields") {
          $fields = array ("TransactionType|Transaction Type|",
                           "ElectionCycle|Cyle|",
                           "Election|Election|Date",
                           "TransactionDateStart|Start Date|Date",
                           "TransactionDateEnd|End Date|Date",
                           "TransactionAmount|Amount|Currency",
                           "RecipientCommitteeNameNormalized|Recipient Committee|",
                           "RecipientCandidateNameNormalized|Recipient Name|",
                           "RecipientCandidateOffice|Office|",
                           "RecipientCandidateDistrict|District|",
                           "Target|Ballot Measure|",
                           "Position|Ballot Measure Support|",
                           "DonorNameNormalized|Contributor Name|",
                           "DonorCity|Contributor City|",
                           "DonorState|Contributor State|",
                           "DonorZipCode|Contributor ZipCode|",
                           "DonorEmployerNormalized|Contributor Employer|",
                           "DonorOccupationNormalized|Contributor Occupation|",
                           "DonorOrganization|Contributor Occupation|");
        }

        $sort_fields = array ("contributions_search.TransactionAmount|Amount Ascending",
                              "contributions_search.TransactionAmount DESC|Amount Descending",
                              "contributions_search.TransactionDateEnd|Date Ascending",
                              "contributions_search.TransactionDateEnd DESC|Date Descending",
                              "contributions.DonorNameNormalized|Contributor Name Ascending",
                              "contributions.DonorNameNormalized DESC|Contributor Name Descending",
                              "contributions.DonorEmployerNormalized|Contributor Employer Ascending",
                              "contributions.DonorEmployerNormalized DESC|Contributor Employer Descending",
                              "contributions_search.DonorState|Contributor State Ascending",
                              "contributions_search.DonorState DESC|Contributor State Descending",
                              "contributions.RecipientCandidateNameNormalized|Recipient Name Ascending",
                              "contributions.RecipientCandidateNameNormalized DESC|Recipient Name Descending",
                              "contributions.RecipientCandidateOffice, contributions.RecipientCandidateDistrict|Recipient Office Ascending",
                              "contributions.RecipientCandidateOffice DESC, contributions.RecipientCandidateDistrict DESC|Recipient Office Descending",
                              "contributions.RecipientCommitteeNameNormalized|Recipient Committee Ascending",
                              "contributions.RecipientCommitteeNameNormalized DESC|Recipient Committee Descending");

        $result = my_query ("SELECT contributions.* FROM contributions INNER JOIN contributions_search USING(id) {$search_join} {$where} ORDER BY {$sort} LIMIT " . (($page - 1) * $limit) . ",{$limit}");
        $rows_returned = $result->num_rows;

        echo "<div id=\"results\">";

        if ($summary_type == "") {
          echo "<h1 class=\"caps_title3\">Search Results</h1>";
          echo "<hr class=\"caps_hr1\">";
          echo "<div class=\"content_title1\"><strong class=\"content_strong1\">\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions";
          echo "<img src=\"img/infotool.png\" class=\"info\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 9);\" alt=\"This is the total amount received by number of contributions (does not include unitemized contributions). The table displays all contributions in the given search parameters, including both itemized contributions (of $100 or more) and unitemized contribution totals.\">";
          echo "<h2 class=\"caps_title4\">Contributions</h2>";
          echo "<hr class=\"caps_hr1\">";
        } else {
          if (strlen ($summary_type) == 1) {
            switch ($summary_type) {
              case "C":
                echo "<div class=\"content_title1\"><strong class=\"content_strong1\">{$_POST["candidate_list"]}</strong> has received</div>";
                echo "<div class=\"content_title1\"><strong class=\"content_strong1\">\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions";
                echo "<img src=\"img/infotool.png\" class=\"info\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 9);\" alt=\"This is the total amount received by number of contributions (does not include unitemized contributions). The table displays all contributions in the given search parameters, including both itemized contributions (of $100 or more) and unitemized contribution totals.\">";
                echo "<hr class=\"caps_hr1\">";
                break;            

              case "D":
                echo "<div class=\"content_title1\"><strong class=\"content_strong1\">\"" . strtoupper ($_POST["contributor"]) . "\"</strong> has contributed</div>";
                echo "<div class=\"content_title1\"><strong class=\"content_strong1\">\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions";
                echo "<img src=\"img/infotool.png\" class=\"info\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 9);\" alt=\"This is the total amount received by number of contributions (does not include unitemized contributions). The table displays all contributions in the given search parameters, including both itemized contributions (of $100 or more) and unitemized contribution totals.\">";
                echo "<div id=\"breakdown_box\">";
                $result2 = my_query ("SELECT CandidateContribution, BallotMeasureContribution, SUM(TransactionAmount) AS TotalAmount FROM ca_search.contributions_search {$where} GROUP BY CandidateContribution, BallotMeasureContribution ORDER BY CandidateContribution, BallotMeasureContribution");
                while ($row2 = $result2->fetch_assoc()) {
                  if ($row2["CandidateContribution"] == "Y" && $row2["BallotMeasureContribution"] == "N") {echo "<b>$" . number_format ($row2["TotalAmount"], 2, ".", ",") . "</b> to <b>candidates</b><br>";}
                  if ($row2["CandidateContribution"] == "N" && $row2["BallotMeasureContribution"] == "Y") {echo "<b>$" . number_format ($row2["TotalAmount"], 2, ".", ",") . "</b> to <b>ballot measures</b><br>";}
                  if ($row2["CandidateContribution"] == "N" && $row2["BallotMeasureContribution"] == "N") {echo "<b>$" . number_format ($row2["TotalAmount"], 2, ".", ",") . "</b> to <b>other committees</b><br>";}
                }
                echo "</div> <!-- breakdown_box -->";
                echo "<hr class=\"caps_hr1\">";
                break;

              case "E":
                $election = substr ($_POST["proposition_list"], 4);
                $election_date = date ("F Y", strtotime ($election));
                echo "<div class=\"content_title1\"><strong class=\"content_strong1\">Ballot Measures</strong> on the {$election_date} ballot have received</div>";
                echo "<div class=\"content_title1\"><strong class=\"content_strong1\">\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions";
                echo "<img src=\"img/infotool.png\" class=\"info\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 9);\" alt=\"This is the total amount received by number of contributions (does not include unitemized contributions). The table displays all contributions in the given search parameters, including both itemized contributions (of $100 or more) and unitemized contribution totals.\">";
                echo "<div id=\"breakdown_box\">";
                $result2 = my_query ("SELECT Target, COUNT(*) AS TotalCount, SUM(TransactionAmount) AS TotalAmount, SUM(IF(PositionID = 1,1,0)) AS SupportCount, SUM(IF(PositionID=1,TransactionAmount,0)) AS SupportAmount, SUM(IF(PositionID = 2,1,0)) AS OpposeCount, SUM(IF(PositionID=2,TransactionAmount,0)) AS OpposeAmount FROM contributions_search INNER JOIN smry_propositions USING (PropositionID) WHERE Election = '{$election}' AND BallotMeasureContribution = 'Y' GROUP BY Target ORDER BY Target");
                while ($row2 = $result2->fetch_assoc()) {
                  if (strpos ($row2["Target"], "-") !== false) {
                    echo "<p><b>" . substr ($row2["Target"], 0, strrpos ($row2["Target"], " - ")) . "</b>" . substr ($row2["Target"], strrpos ($row2["Target"], " - ")) . "<br>";
                  } else {
                    echo "<p><b>{$row2["Target"]}</b><br>";
                  }
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$" . number_format ($row2["TotalAmount"], 2, ".", ",") . " total raised - " . number_format ($row2["TotalCount"], 0, ".", ",") . " contributions<br>";
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Support: $" . number_format ($row2["SupportAmount"], 2, ".", ",") . " raised - " . number_format ($row2["SupportCount"], 0, ".", ",") . " contributions<br>";
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Oppose: $" . number_format ($row2["OpposeAmount"], 2, ".", ",") . " raised - " . number_format ($row2["OpposeCount"], 0, ".", ",") . " contributions";
                  echo "</p>";
                }
                echo "</div> <!-- breakdown_box -->";
                echo "<hr class=\"caps_hr1\">";
                break;            
            }
          } else {
            echo "<h1 class=\"caps_title3\">Search Results</h1>";
            echo "<hr class=\"caps_hr1\">";
            echo "<div class=\"content_title1\"><strong class=\"content_strong1\">\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions";
            echo "<img src=\"img/infotool.png\" class=\"info\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 9);\" alt=\"This is the total amount received by number of contributions (does not include unitemized contributions). The table displays all contributions in the given search parameters, including both itemized contributions (of $100 or more) and unitemized contribution totals.\">";
            echo "<h2 class=\"caps_title4\">Contributions</h2>";
            echo "<hr class=\"caps_hr1\">";
          }
        }

        echo "<div id=\"filter_box\">";
        echo "Show";
        echo "<select id=\"show\" name=\"return_rows\" class=\"content_select1\">";
        if ($limit == 10) {echo "<option selected>10</option>";} else {echo "<option>10</option>";}
        if ($limit == 25) {echo "<option selected>25</option>";} else {echo "<option>25</option>";}
        if ($limit == 50) {echo "<option selected>50</option>";} else {echo "<option>50</option>";}
        if ($limit == 100) {echo "<option selected>100</option>";} else {echo "<option>100</option>";}
        echo "</select>";
        echo "rows&nbsp;&nbsp;&nbsp;&nbsp;Sort by";
        echo "<select id=\"sort\" name=\"sort\" class=\"content_select1\">";
        foreach ($sort_fields as $sort_item) {
          $item_data = explode ("|", $sort_item); 
          if ($sort == $item_data[0]) {echo "<option value=\"{$item_data[0]}\" SELECTED>{$item_data[1]}</option>";} else {echo "<option value=\"{$item_data[0]}\">{$item_data[1]}</option>";}
        }
        echo "</select>";
        echo "<input type=\"submit\" value=\"Update\" id=\"caps_update_btn\">";
        echo "<div id=\"download_box\">";
        echo "<a href=\"download_csv.php?w=" . urlencode ($where) . "\" class=\"download_csv\">Download CSV</a>&nbsp;&nbsp;";
        echo "<img src=\"img/infotool.png\" class=\"info\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 10);\" alt=\"Download the search results as a CSV file.\">";
        echo "</div> <!-- download_box -->";
        echo "</div> <!-- filter_box -->";

        echo "Showing <strong>" . number_format ($first_row, 0, ".", ",") . "</strong> to <strong>" . number_format ($last_row, 0, ".", ",") . "</strong> of <strong>" . number_format ($totals_row["records"], 0, ".", ",") . "</strong> rows ";
        $field_msg = "Show more fields";
        if ($field_set == "Show more fields") {$field_msg = "Show fewer fields";}
        echo "<input type=\"submit\" name=\"fields\" value=\"{$field_msg}\" id=\"caps_field_btn\">";
        echo "<img src=\"img/infotool.png\" onMouseOver=\"this.src='img/infotool-hover.png';\" onMouseOut=\"this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';\" onClick=\"display_tooltip(event, 11);\" alt=\"Show more columns in the table for additional information on contributors.\">";

        echo "<div id=\"table_box\">";
        echo "<table title=\"search table\" summary=\"search table\" class=\"caps_table1\">";
        echo "<thead>";
        echo "<tr>";

        foreach ($fields as $field) {
          $field_data = explode ("|", $field);
          echo "<th>{$field_data[1]}</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          foreach ($fields as $field) {
            $field_data = explode ("|", $field);
            switch ($field_data[2]) {
              case "Date":
                if (date ("F j, Y", strtotime ($row[$field_data[0]])) == "December 31, 1969") {
                  echo "<td><I>unknown</I></td>";
                } else {
                  echo "<td>" . date ("M j, Y", strtotime ($row[$field_data[0]])) . "</td>";
                }
                break;

              case "Currency":
                echo "<td style=\"text-align:right\">$" . number_format($row[$field_data[0]], 2, ".", ",") . "</td>";
                break;

              default: 
                echo "<td>{$row[$field_data[0]]}</td>";
                break;
            }
          }
          echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div> <!-- table_box -->";

        # Pagination section
        echo "<center>";
        echo "<input type=\"hidden\" name=\"page\" value=\"{$page}\">";
        echo "<input type=\"hidden\" name=\"field_list\" value=\"{$field_set}\">";
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
        $result = my_query ("SELECT * FROM smry_last_update"); $row = $result->fetch_assoc(); $last_update = $row["LastUpdate"];

        echo "<p>&nbsp;</p>";
        echo "To view the entire set of search results, <a href=\"download_csv.php?w=" . urlencode ($where) . "\" class=\"download_csv\">download the CSV</a> file.<br>";
        echo "<div class=\"last_update\">Contributions data is current as of " . date ("F j, Y", strtotime ($last_update)) . ".</div>";
        echo "</center>";

        echo "</div> <!-- results ->";
      }
    }
  }
?>
