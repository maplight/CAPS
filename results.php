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
    if (isset ($search_data["contributor"])) {
      foreach (str_word_count ($search_data["contributor"], 1) as $word) {
        if (strpos ($word, "'") === false) {
          $Donor .= "+{$word} ";
        } else {
          $Donor .= "+\"" . addslashes ($word) . "\" ";
        }
      }
    }
    $Donor = "MATCH (contributions_search.DonorNameNormalized, contributions_search.DonorEmployerNormalized, contributions_search.DonorOrganization) AGAINST ('" . $Donor . "' IN BOOLEAN MODE)"; 
  
    # build locations query
    $DonorState = "";
    if (isset ($search_data["location_list"])) {
      foreach ($search_data["location_list"] as $state) {
        if ($state != "ALL") {$DonorState .= "contributions_search.DonorState = '{$state}' OR ";}
      }
      $DonorState = substr ($DonorState, 0, -4); # Remove the final OR
    }

# search_candidates

# candidates_list (array)

# office_list (array)

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

echo "contributor: " . $Donor . "<BR>";
echo "location_list: " . $DonorState . "<BR>";


echo "cycles: " . $ElectionCycle . "<BR>";


  }
?>