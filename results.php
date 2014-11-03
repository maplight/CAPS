<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<P>&nbsp;</P><BLOCKQUOTE><DIV CLASS=\"title\">Search political contributions from 2001 through the present, using the controls on the left.</DIV></BLOCKQUOTE>";
    } else {
      # Parse search form
      $where = parse_search_form ($_POST);
      display_data ($where, $_POST["fields"]);
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
      foreach (str_word_count ($search_data["contributor"], 1) as $word) {
        if (strpos ($word, "'") === false) {
          $Donor .= "+{$word} ";
        } else {
          $Donor .= "+\"" . addslashes ($word) . "\" ";
        }
      }
      if ($Donor != "") {$Donor = "MATCH (contributions_search.DonorNameNormalized, contributions_search.DonorEmployerNormalized, contributions_search.DonorOrganization) AGAINST ('" . $Donor . "' IN BOOLEAN MODE)";}
    }
  
    # build locations query
    if ($search_data["state_list"] != "ALL") {$DonorState = "contributions_search.DonorState = '{$search_data["state_list"]}'";}


    #------------------------------------------------------------------------------------------
    # Build candidate search query:
    if (isset ($search_data["candidates"])) {
      # Candidates checked
      $CandidateContribution = "contributions_search.CandidateContribution = 'Y'";

      switch ($search_data["cand_select"]) {
        case "search":
          # build candidate search query
          if ($search_data["candidate_list"] == "Select candidate") {
            foreach (str_word_count ($search_data["search_candidates"], 1) as $word) {
              if (strpos ($word, "'") === false) {
                $Candidate .= "+{$word} ";
              } else {
                $Candidate .= "+\"" . addslashes ($word) . "\" ";
              }
            }
            if ($Candidate != "") {$Candidate = "MATCH (contributions_search.RecipientCandidateNameNormalized) AGAINST ('" . $Candidate . "' IN BOOLEAN MODE)";}
          } else {
            $CandidateList = "contributions_search.RecipientCandidateNameNormalized = '" . addslashes ($search_data["candidate_list"]) . "'";
          }
          break;
   
        case "office":
          # build office list query
          $OfficeList = "contributions_search.RecipientCandidateOffice = '" . addslashes ($search_data["office_list"]) . "'";
          break;
      }
    }


    #------------------------------------------------------------------------------------------
    # Build ballot measure search query:
    if (isset ($search_data["propositions"])) {
      # Ballot Measures checked
      $PropositionContribution = "contributions_search.BallotMeasureContribution = 'Y'";

      if ($search_data["search_propositions"] != "Search propositions" && $search_data["proposition_list"] == "ALL") {
        # build proposition search query
        foreach (str_word_count ($search_data["search_propositions"], 1) as $word) {
          if (strpos ($word, "'") === false) {
            $PropositionSearch .= "+{$word} ";
          } else {
            $PropositionSearch .= "+\"" . addslashes ($word) . "\" ";
          }
        }
        if ($PropositionSearch != "") {$PropositionSearch = "MATCH (contributions_search.Target) AGAINST ('" . $PropositionSearch . "' IN BOOLEAN MODE)";}
      } else {
        if ($search_data["proposition_list"] != "ALL") {
          if (substr ($search_data["proposition_list"], 0, 3) == "ALL") {
            # build query for a specific election
            $selected_data = explode ("#", $search_data["proposition_list"]);
            $Election = "contributions_search.Election = '" . $selected_data[1] . "'";
          } else {
            # build query for a specific proposition
            $selected_data = explode ("#", $search_data["proposition_list"]);
            $Election = "contributions_search.Election = '" . $selected_data[0] . "'";
            $Proposition = "contributions_search.Target = '" . addslashes ($selected_data[1]) . "'";
          }
        }
      }

      # build support/oppose query
      if ($search_data["position"] == "S") {$Position = "contributions_search.Position = 'SUPPORT'";}
      if ($search_data["position"] == "O") {$Position = "contributions_search.Position = 'OPPOSE'";}

      # exclude allied committees query
      if (isset ($search_data["exclude"])) {$Allied = "contributions_search.AlliedCommittee = 'N'";}
    }


    #------------------------------------------------------------------------------------------
    # Build committe search query:
    if (isset ($search_data["committees"])) {
      # build committee search query
      if ($search_data["comm_select"] != "all") {
        foreach (str_word_count ($search_data["committee_search"], 1) as $word) {
          if (strpos ($word, "'") === false) {
            $Committee .= "+{$word} ";
          } else {
            $Committee .= "+\"" . addslashes ($word) . "\" ";
          }
        }
        if ($Committee != "") {$Committee = "MATCH (contributions_search.RecipientCommitteeNameNormalized) AGAINST ('" . $Committee . "' IN BOOLEAN MODE)";}
      } 
    }


    #------------------------------------------------------------------------------------------
    # Build dates / cycles query
    switch ($search_data["date_select"]) {
      case "range":
        # build date range query
        $DateRange = "contributions_search.TransactionDateStart >= '" . date ("Y-m-d", strtotime ($search_data["start_date"])) . "' AND contributions_search.TransactionDateEnd <= '" . date ("Y-m-d", strtotime ($search_data["end_date"])) . "'";
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
      if ($Candidate != "") {$candidate_where .= "{$Candidate} AND ";}
    } else {
      $candidate_where .= "{$CandidateList} AND ";
    }
    if ($OfficeList != "") {$candidate_where .= "{$OfficeList} AND ";}
    if ($CandidateContribution != "") {$candidate_where .= "$CandidateContribution AND ";}
    if ($candidate_where != "") {$candidate_where = substr ($candidate_where, 0, -5);} # Remove the final AND

    # create proposition query
    if ($Proposition == "") {
      if ($PropositionSearch != "") {$proposition_where .= "{$PropositionSearch} AND ";}
    } else {
      $proposition_where .= "{$Proposition} AND ";
    }
    if ($Election != "") {$proposition_where .= "{$Election} AND ";}
    if ($Position != "") {$proposition_where .= "{$Position} AND ";}
    if ($Allied != "") {$proposition_where .= "{$Allied} AND ";}
    if ($PropositionContribution != "") {$proposition_where .= "$PropositionContribution AND ";}
    if ($proposition_where != "") {$proposition_where = substr ($proposition_where, 0, -5);} # Remove the final AND

    # create committee query
    if ($Committee != "") {$committee_where .= "{$Committee} AND ";}
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


  function display_data ($where, $fields) {
    if ($where == "") {
      echo "You have not entered any search data, please select a criteria on the side.";
    } else {
      $result = my_query ("SELECT COUNT(*) AS records, SUM(TransactionAmount) AS total FROM contributions_search {$where}");
      $totals_row = $result->fetch_assoc();

      if ($totals_row["records"] == 0) {
        echo "Your search did not return any records.";
      } else {
        # Calculate total pages based on display rows
        if (isset ($_POST["return_rows"])) {$limit = $_POST["return_rows"];} else {$limit = 10;}
        $total_pages = intval (($totals_row["records"] - 1) / $limit) + 1;
 
        # Get page # to display
        if (isset ($_POST["page"])) {$page = $_POST["page"];} else {$page = 0;}
        if (isset ($_POST["page_button"])) {
          switch ($_POST["page_button"]) {
            case "Next": $page++; break;
            case "Previous": $page--; break;
            default: $page = $_POST["page_button"]; break;
          }
        }

        # Reset the page to 0 if you selected a smaller set then is currently displayed
        if ($page > $total_pages) {$page = 0;}

        # Determine rows being displayed
        $first_row = $page * $limit + 1;
        $last_row = $first_row + $limit - 1;
        if ($first_row > $totals_row["records"]) {$first_row = 1;}
        if ($last_row > $totals_row["records"]) {$last_row = $totals_row["records"];}


        $sort = "contributions_search.TransactionDateEnd DESC";

        $result = my_query ("SELECT contributions.* FROM contributions INNER JOIN contributions_search USING(id) {$where} ORDER BY {$sort} LIMIT " . ($page * $limit) . ",{$limit}");
        $rows_returned = $result->num_rows;

        echo "<h1>Search Results</h1>";
        echo "<div class=\"info-block\">";
        echo "<div class=\"search-info\">";
        echo "<div class=\"title\"><strong>\$" . number_format ($totals_row["total"], 2, ".", ",") . "</strong> in " . number_format ($totals_row["records"], 0, ".", ",") . " contributions <a href=\"#\" class=\"info\">info</a></div>";
        echo "<em>from election cycles xxxx through xxxx</em>";
        echo "</div>";
        echo "<div class=\"contributions-area\">";
        echo "<h2>Contributions</h2>";
        echo "<div class=\"output\">Showing all contributions of $100 or more <a href=\"#\" class=\"info\">info</a></div>";
        echo "</div>";
        echo "</div>";
        echo "<div class=\"filter-block\">";
        echo "<div class=\"filter-form\">";
        echo "<fieldset>";
        echo "<legend class=\"hidden\">filter-form</legend>";
        echo "<label for=\"show\">Show</label>";
        echo "<select id=\"show\" name=\"return_rows\">";
        if ($limit == 10) {echo "<option selected>10</option>";} else {echo "<option>10</option>";}
        if ($limit == 25) {echo "<option selected>25</option>";} else {echo "<option>25</option>";}
        if ($limit == 50) {echo "<option selected>50</option>";} else {echo "<option>50</option>";}
        if ($limit == 100) {echo "<option selected>100</option>";} else {echo "<option>100</option>";}
        echo "</select>";
        echo "<label for=\"row\">rows</label>";
        echo "<div class=\"holder\">";
        echo "<input type=\"submit\" value=\"Update\">";
        echo "<a href=\"#\" class=\"info\">info</a>";
        echo "</div>";
        echo "</fieldset>";
        echo "</div>";
        echo "<div class=\"download-area\">";
        echo "<a href=\"#\" class=\"download\">Download CSV</a>";
        echo "<div class=\"download-info\">";
        echo "<a href=\"#\" class=\"info-link\">opener</a>";
        echo "<div class=\"info-slide\">";
        echo "<p>Download your search results as a CSV file</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<div class=\"search-result\">";
        echo "<div class=\"output\">";
        echo "<p>Showing <strong>" . number_format ($first_row, 0, ".", ",") . "</strong> to <strong>" . number_format ($last_row, 0, ".", ",") . "</strong> of <strong>" . number_format ($totals_row["records"], 0, ".", ",") . "</strong> rows </p>";
        echo "</div>";
        echo "<a href=\"#\" class=\"see-more\">Show more fields</a>";
        echo "<a href=\"#\" class=\"info\">info</a>";
        echo "</div>";
        echo "<div class=\"table-holder\">";
        echo "<table title=\"search table\" summary=\"search table\" class=\"search-table\">";
        echo "<thead>";
        echo "<tr>";

        foreach ($fields as $field) {
          $field_data = explode ("|", $field);
          echo "<th>{$field_data[1]}";
          echo "<div class=\"links\">";
          echo "<a href=\"#\" class=\"upword\"> </a>";
          echo "<a href=\"#\" class=\"downword\"> </a>";
          echo "</div>";
          echo "</th>";
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

#        echo "<tr>";
#        foreach ($fields as $field) {
#          $field_data = explode ("|", $field);
#          echo "<th class=\"bottom\">{$field_data[1]}";
#          echo "</th>";
#        }
#        echo "</tr>";

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "<input type=\"hidden\" name=\"page\" value=\"{$page}\">";
        if ($total_pages > 1) {
          if ($page > 0) {echo "<INPUT TYPE=\"submit\" NAME=\"page_button\" VALUE=\"Previous\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
          if ($page < ($total_pages - 1)) {echo "<INPUT TYPE=\"submit\" NAME=\"page_button\" VALUE=\"Next\">";}
        }
        echo "<div class=\"notes\"><p>To view the entire set of search results, <a href=\"#\">download the CSV</a> file.  Contributions data is current as of [today's date].</p>";
        echo "</div>";
      }
    }
  }
?>
