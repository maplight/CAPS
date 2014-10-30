<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<P>&nbsp;</P><BLOCKQUOTE><DIV CLASS=\"title\">Search political contributions from 2001 through the present, using the controls on the left.</DIV></BLOCKQUOTE>";
    } else {
      # Parse search form
echo "<pre>"; print_r ($_POST); echo "</PRE><P>";
      $query = parse_search_form ($_POST);
echo "$query<P>";
#      display_data ($query);
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
        $DateRange = "contributions_search.TransactionDate >= '" . date ("Y-m-d", strtotime ($search_data["start_date"])) . "' AND contributions_search.TransactionDate <= '" . date ("Y-m-d", strtotime ($search_data["end_date"])) . "'";
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

echo "$proposition_where<P>";




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
      $where .= "({$candidate_where} OR {$proposition_where}) AND ";
    } else {
      if ($candidate_where != "") {$where .= "{$candidate_where} AND ";}
      if ($proposition_where != "") {$where .= "{$proposition_where} AND ";}
    }
    if ($committee_where != "") {$where .= "{$committee_where} AND ";}
    if ($date_where != "") {$where .= "{$date_where} AND ";}
    if ($where != "") {$where = "WHERE " . substr ($where, 0, -5);} # remove extra AND

    # create query
    $query = "SELECT contributions.* FROM contributions INNER JOIN contributions_search USING(id) {$where}";

    return $query;
  }


  function display_data ($query) {
    $limit = 25;
    $page = 0;
    $sort = "contributions_search.TransactionDate DESC";
    
    echo "<TABLE CLASS=results_table>";
    echo "<TR><TH>Recipient Name</TH><TH>Recipient Committee</TH><TH>Target</TH><TH>Position</TH><TH>Office</TH><TH>Contributor Name</TH><TH>Contributor Employer</TH><TH>Contributor Occupation</TH><TH>Contributor Organization</TH><TH>Date</TH><TH>Amount</TH></TR>";

    $result = my_query ($query . " ORDER BY {$sort} LIMIT " . ($page * $limit) . ",{$limit}");
    $rows_returned = $result->num_rows;

    while ($row = $result->fetch_assoc()) {
      echo "<TR>";
      echo "<TD>{$row["RecipientCandidateNameNormalized"]}</TD>";
      echo "<TD>{$row["RecipientCommitteeNameNormalized"]}</TD>";
      echo "<TD>{$row["Target"]}</TD>";
      echo "<TD>{$row["Position"]}</TD>";
      echo "<TD>{$row["RecipientCandidateOffice"]}</TD>";
      echo "<TD>{$row["DonorNameNormalized"]}</TD>";
      echo "<TD>{$row["DonorEmployerNormalized"]}</TD>";
      echo "<TD>{$row["DonorEmployerNormalized"]}</TD>";
      echo "<TD>{$row["DonorOrganization"]}</TD>";
      if (date ("F j, Y", strtotime ($row["TransactionDate"])) == "December 31, 1969") {
        echo "<TD><I>unknown</I></TD>";
      } else {
        echo "<TD>" . str_replace (" ", "&nbsp", date ("F j, Y", strtotime ($row["TransactionDate"]))) . "</TD>";
      }
      echo "<TD STYLE=\"text-align:right;\">$" . number_format($row["TransactionAmount"], 2, ".", ",") . "</TD>";
      echo "</TR>";
    }

    echo "</TABLE>";
  }
?>
