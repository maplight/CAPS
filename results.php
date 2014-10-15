<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<P>&nbsp;</P><BLOCKQUOTE><B>Search political contributions from 2001 through the present, using the controls on the left.</B></BLOCKQUOTE>";
    } else {
      # Parse search form
      $query = parse_search_form ($_POST);
      display_data ($query);
    }
  }


  function parse_search_form ($search_data) {
    $Donor = "";
    foreach (str_word_count ($search_data["contributor"], 1) as $word) {
      if (strpos ($word, "'") === false) {
        $Donor .= "+{$word} ";
      } else {
        $Donor .= "+\"" . addslashes ($word) . "\" ";
      }
    }
    if ($Donor != "") {$Donor = "MATCH (contributions_search.DonorNameNormalized, contributions_search.DonorEmployerNormalized, contributions_search.DonorOrganization) AGAINST ('" . $Donor . "' IN BOOLEAN MODE)";} 
  
    # build locations query
    $DonorState = "";
    if (isset ($search_data["location_list"])) {
      foreach ($search_data["location_list"] as $state) {
        if ($state != "ALL") {$DonorState .= "contributions_search.DonorState = '{$state}' OR ";}
      }
      $DonorState = substr ($DonorState, 0, -4); # Remove the final OR
    }

    # build candidate search query
    $Candidate = "";
    if (! isset ($search_data["candidates_list"])) {
      foreach (str_word_count ($search_data["search_candidates"], 1) as $word) {
        if (strpos ($word, "'") === false) {
          $Candidate .= "+{$word} ";
        } else {
          $Candidate .= "+\"" . addslashes ($word) . "\" ";
        }
      }
    }
    if ($Candidate != "") {$Candidate = "MATCH (contributions_search.RecipientCandidateNameNormalized) AGAINST ('" . $Candidate . "' IN BOOLEAN MODE)";} 

    # build candidate list query
    $CandidateList = "";
    if (isset ($search_data["candidates_list"])) {
      foreach ($search_data["candidates_list"] as $candidate) {
        if ($candidate != "ALL") {$CandidateList .= "contributions_search.RecipientCandidateNameNormalized = '" . addslashes ($candidate) . "' OR ";}
      }
      $CandidateList = substr ($CandidateList, 0, -4); # Remove the final OR
    }

    # build office list query
    $OfficeList = "";
    if (isset ($search_data["office_list"])) {
      foreach ($search_data["office_list"] as $office) {
        if ($office != "ALL") {$OfficeList .= "contributions_search.RecipientCandidateOffice = '" . addslashes ($office) . "' OR ";}
      }
      $OfficeList = substr ($OfficeList, 0, -4); # Remove the final OR
    }

    # build election list query
    $ElectionList = "";
    if (isset ($search_data["elections_list"])) {
      foreach ($search_data["elections_list"] as $election) {
        if ($election != "ALL") {$ElectionList .= "contributions_search.Election = '{$election}' OR ";}
      }
      $ElectionList = substr ($ElectionList, 0, -4); # Remove the final OR
    }

    # build proposition search query
    $PropositionSearch = "";
    foreach (str_word_count ($search_data["search_propositions"], 1) as $word) {
      if (strpos ($word, "'") === false) {
        $PropositionSearch .= "+{$word} ";
      } else {
        $PropositionSearch .= "+\"" . addslashes ($word) . "\" ";
      }
    }
    if ($PropositionSearch != "") {$PropositionSearch = "MATCH (contributions_search.Target) AGAINST ('" . $PropositionSearch . "' IN BOOLEAN MODE)";} 

    # build specific proposition query
    if ($search_data["propositions_list"] != "ALL") {
      $Proposition = "contributions_search.Target = '" . addslashes ($search_data["propositions_list"]) . "'";
    } else {
      $Proposition = "";
    }

    # build support/oppose query
    if (isset ($search_data["support"])) {$Support = "contributions_search.Position = 'SUPPORT'";} else {$Support = "";}
    if (isset ($search_data["oppose"])) {$Oppose = "contributions_search.Position = 'OPPOSE'";} else {$Oppose = "";}

    # exclude allied committees query
    if (isset ($search_data["exclude"])) {$Allied = "contributions_search.AlliedCommittee = 'N'";} else {$Allied = "";}

    # build committee search query
    $Committee = "";
    foreach (str_word_count ($search_data["committee"], 1) as $word) {
      if (strpos ($word, "'") === false) {
        $Committee .= "+{$word} ";
      } else {
        $Committee .= "+\"" . addslashes ($word) . "\" ";
      }
    }
    if ($Committee != "") {$Committee = "MATCH (contributions_search.RecipientCommitteeNameNormalized) AGAINST ('" . $Committee . "' IN BOOLEAN MODE)";} 

    # build date & cycle query
    $DateRange = "";
    $ElectionCycle = "";
    if (! isset ($search_data["all_dates"])) {
      # user is narrowing date / cycle search
      $DateRange = "contributions_search.TransactionDate >= '" . date ("Y-m-d", strtotime ($search_data["start_date"])) . "' AND contributions_search.TransactionDate <= '" . date ("Y-m-d", strtotime ($search_data["end_date"])) . "'";

      # build cycles query
      if (isset ($search_data["cycles"])) {
        foreach ($search_data["cycles"] as $cycle) {
          $ElectionCycle .= "contributions_search.ElectionCycle = $cycle OR ";
        }
        $ElectionCycle = substr ($ElectionCycle, 0, -4); # Remove the final OR
      }
    }

    $donor_where = "";
    $candidate_where = "";
    $proposition_where = "";
    $committee_where = "";
    $date_where = "";

    # create donor query
    if ($Donor != "") {$donor_where .= "{$Donor} AND ";}
    if ($DonorState != "") {$donor_where .= "({$DonorState}) AND ";}
    if ($donor_where != "") {$donor_where = substr ($donor_where, 0, -5);} # remove extra AND
    
    # create candidate query
    if ($CandidateList == "") {
      if ($Candidate != "") {$candidatewhere .= "{$Candidate} AND ";}
    } else {
      $candidate_where .= "({$CandidateList}) AND ";
    }
    if ($OfficeList != "") {$candidate_where .= "({$OfficeList}) AND ";}
    if ($candidate_where != "") {$candidate_where .= "contributions_search.CandidateContribution = 'Y'";}

    # create proposition query
    if ($ElectionList != "") {$proposition_where .= "({$ElectionList}) AND ";}
    if ($Proposition == "") {
      if ($PropositionSearch != "") {$proposition_where .= "{$PropositionSearch} AND ";}
    } else {
      $proposition_where .= "{$Proposition} AND ";
    }
    if (($Support != "" && $Oppose == "") || ($Support == "" && $Oppose != "")) {
      if ($Support != "") {$proposition_where .= "{$Support} AND ";}
      if ($Oppose != "") {$proposition_where .= "{$Oppose} AND ";}
    }
    if ($Allied != "") {$proposition_where .= "{$Allied} AND ";}
    if ($proposition_where != "") {$proposition_where .= "contributions_search.BallotMeasureContribution = 'Y'";}

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