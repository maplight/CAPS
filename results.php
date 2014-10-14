<?php
  function build_results_table () {
    if (! isset ($_POST["contributor"])) {
      # No form search yet
      echo "<P>&nbsp;</P><BLOCKQUOTE><B>Search political contributions from 2001 through the present, using the controls on the left.</B></BLOCKQUOTE>";
    } else {
      parse_search_form ($_POST);
      # Parse search form resultsForm search entered
      
      echo "<PRE>"; print_r ($_POST); echo "</PRE>";
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
        if ($candidate != "ALL") {$CandidateList .= "contributions_search.RecipientCandidateNameNormalized = '{$candidate}' OR ";}
      }
      $CandidateList = substr ($CandidateList, 0, -4); # Remove the final OR
    }

    # build office list query
    $OfficeList = "";
    if (isset ($search_data["office_list"])) {
      foreach ($search_data["office_list"] as $office) {
        if ($office != "ALL") {$OfficeList .= "contributions_search.RecipientCandidateOffice = '{$office}' OR ";}
      }
      $OfficeList = substr ($OfficeList, 0, -4); # Remove the final OR
    }


# elections_list (array)

# search_propositions

# propositions_list

# support

# oppose

# committee 

# start_date

# end_date

    # build cycles query
    $ElectionCycle = "";
    if (isset ($search_data["cycles"])) {
      foreach ($search_data["cycles"] as $cycle) {
        $ElectionCycle .= "ElectionCycle = $cycle OR ";
      }
      $ElectionCycle = substr ($ElectionCycle, 0, -4); # Remove the final OR
    }

echo "<P>contributor: " . $Donor . "</P>";
echo "<P>location_list: " . $DonorState . "</P>";
echo "<P>search_candidates: " . $Candidate . "</P>";
echo "<P>candidates_list: " . $CandidateList . "</P>";
echo "<P>office_list: " . $OfficeList . "</P>";

  }
?>