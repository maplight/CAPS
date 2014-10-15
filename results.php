<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<P>&nbsp;</P><BLOCKQUOTE><B>Search political contributions from 2001 through the present, using the controls on the left.</B></BLOCKQUOTE>";
    } else {
      parse_search_form ($_POST);
      # Parse search form resultsForm search entered
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
      $DateRange = "contributions_search.TargetDate >= '" . date ("Y-m-d", strtotime ($search_data["start_date"])) . " AND contributions_search.TargetDate <= '" . date ("Y-m-d", strtotime ($search_data["end_date"])) . "'";

      # build cycles query
      if (isset ($search_data["cycles"])) {
        foreach ($search_data["cycles"] as $cycle) {
          $ElectionCycle .= "contributions_search.ElectionCycle = $cycle OR ";
        }
        $ElectionCycle = substr ($ElectionCycle, 0, -4); # Remove the final OR
      }
    }

    $where = "";

    # Add donor sub queries
    if ($Donor != "") {$where .= "{$Donor} AND ";}
    if ($DonorState != "") {$where .= "({$DonorState}) AND ";}
    
    # Add candidate sub queries
    if ($CandidateList == "") {
      if ($Candidate != "") {$where .= "{$Candidate} AND ";}
    } else {
      $where .= "({$CandidateList}) AND ";
    }
    if ($OfficeList != "") {$where .= "({$OfficeList}) AND ";}


echo "<P>elections_list: " . $ElectionList . "</P>";
echo "<P>search_propositions: " . $PropositionSearch . "</P>";
echo "<P>propositions_list: " . $Proposition . "</P>";
echo "<P>support: " . $Support . "</P>";
echo "<P>oppose: " . $Oppose . "</P>";
echo "<P>exclude: " . $Allied . "</P>";
echo "<P>committee: " . $Committee . "</P>";
echo "<P>date_range: " . $DateRange . "</P>";
echo "<P>cycles: " . $ElectionCycle . "</P>";

echo "<P>WHERE: " . $where . "</P>";
    
    $query = "SELECT * FROM contributions INNER JOIN contributions_search USING(id)";

  }
?>