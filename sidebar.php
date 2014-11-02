<?php
  function fill_state_list ($selected) {
    echo "<OPTION VALUE=\"ALL\">All states</OPTION>";
    $result = my_query ("SELECT StateName, StateCode FROM smry_states WHERE IsState = 1 ORDER BY StateName");
    while ($row = $result->fetch_assoc()) {
      if ($row["StateCode"] == $selected) {echo "<OPTION VALUE={$row["StateCode"]} SELECTED>{$row["StateName"]}</OPTION>";} else {echo "<OPTION VALUE={$row["StateCode"]}>{$row["StateName"]}</OPTION>";}
    }
  }


  function fill_candidate_names ($selected) {
    echo "<OPTION>Select candidate</OPTION>";
    $javascript_array = "[\"Select candidate\"],";
    $result = my_query ("SELECT RecipientCandidateNameNormalized FROM smry_candidates ORDER BY RecipientCandidateNameNormalized");
    while ($row = $result->fetch_assoc()) {
      if ($row["RecipientCandidateNameNormalized"] == $selected) {echo "<OPTION SELECTED>{$row["RecipientCandidateNameNormalized"]}</OPTION>";} else {echo "<OPTION>{$row["RecipientCandidateNameNormalized"]}</OPTION>";}
      $javascript_array .= "\"" . str_replace ("\"", "", $row["RecipientCandidateNameNormalized"]) . "\",";
    }
    return $javascript_array;
  }


  function fill_offices_sought ($selected) {
    $result = my_query ("SELECT DISTINCT RecipientCandidateOffice FROM smry_offices ORDER BY RecipientCandidateOffice");
    while ($row = $result->fetch_assoc()) {
      if ($row["RecipientCandidateOffice"] == $selected) {echo "<OPTION SELECTED>{$row["RecipientCandidateOffice"]}</OPTION>";} else {echo "<OPTION>{$row["RecipientCandidateOffice"]}</OPTION>";}
    }
  }


  function fill_elections () {
    $result = my_query ("SELECT DISTINCT Election FROM smry_propositions ORDER BY Election DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE={$row["Election"]}>" . date ("M j, Y", strtotime ($row["Election"])) . "</OPTION>";
    }
  }


  function fill_propositions ($selected) {
    echo "<OPTION VALUE=\"ALL\">All propositions</OPTION>";
    $javascript_array = "[\"ALL\",\"All propositions\"],";
    $last_election = "";
    $result = my_query ("SELECT DISTINCT Election, Target FROM smry_propositions ORDER BY Election DESC, Target");
    while ($row = $result->fetch_assoc()) {
      if ($last_election != $row["Election"]) {
        if ("ALL#{$row["Election"]}" == $selected) {echo "<OPTION VALUE=\"ALL#{$row["Election"]}\" SELECTED>" . date ("M j, Y", strtotime ($row["Election"])) . " propositions</OPTION>";} else {echo "<OPTION VALUE=\"ALL#{$row["Election"]}\">" . date ("M j, Y", strtotime ($row["Election"])) . " propositions</OPTION>";}
        $javascript_array .= "[\"ALL#{$row["Election"]}\",\"" . date ("M j, Y", strtotime ($row["Election"])) . " propositions\"],";
        $last_election = $row["Election"];
      }
      if ("{$row["Election"]}#" . str_replace ("\"", "\\\"", $row["Target"]) == $selected) {echo "<OPTION VALUE=\"{$row["Election"]}#" . str_replace ("\"", "\\\"", $row["Target"]) . "\" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;{$row["Target"]}</OPTION>";} else {echo "<OPTION VALUE=\"{$row["Election"]}#" . str_replace ("\"", "\\\"", $row["Target"]) . "\">&nbsp;&nbsp;&nbsp;&nbsp;{$row["Target"]}</OPTION>";}
      $javascript_array .= "[\"" . str_replace ("\"", "'", $row["Election"] . "#" . $row["Target"]) . "\",\"" . str_replace ("\"", "'", "\u00a0\u00a0\u00a0\u00a0" . $row["Target"]) . "\"],";
    }
    return $javascript_array;
  }


  function fill_election_cycles () {
    $result = my_query ("SELECT ElectionCycle FROM smry_cycles ORDER BY ElectionCycle DESC");
    while ($row = $result->fetch_assoc()) {
      $cycle_start = $row["ElectionCycle"];
      $cycle_end = $cycle_start + 1;
      echo "<div class=\"year-check\">";
      echo "<input type=\"checkbox\" id=\"y{$cycle_start}\" name=\"cycles[]\" value=\"{$cycle_start}\">";
      echo "<label for=\"y{$cycle_start}\">$cycle_start-$cycle_end</label>";
      echo "</div>";
    }
  }
?>