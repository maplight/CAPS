<?php
function fill_state_list($selected)
{
  global $web_db;
  echo "<OPTION VALUE=\"ALL\">All states</OPTION>";
  foreach ($web_db->query("SELECT StateName, StateCode FROM smry_states WHERE InDropdown = 1 ORDER BY StateName") as $row) {
    if ($row["StateCode"] == $selected) {echo "<OPTION VALUE={$row["StateCode"]} SELECTED>{$row["StateName"]}</OPTION>";} else {echo "<OPTION VALUE={$row["StateCode"]}>{$row["StateName"]}</OPTION>";}
  }
}


function fill_offices_sought($selected)
{
  global $web_db;
  echo "<OPTION>All Offices</OPTION>";
  foreach ($web_db->query("SELECT DISTINCT RecipientCandidateOffice FROM smry_offices ORDER BY RecipientCandidateOffice") as $row) {
    if ($row["RecipientCandidateOffice"] == $selected) {echo "<OPTION SELECTED>{$row["RecipientCandidateOffice"]}</OPTION>";} else {echo "<OPTION>{$row["RecipientCandidateOffice"]}</OPTION>";}
  }
}


function fill_propositions($selected)
{
  global $web_db;
  echo "<OPTION VALUE=\"ALL\">All ballot measures</OPTION>";
  $javascript_array = "[\"ALL\",\"All ballot measures\"],";
  $last_election = "";
  foreach ($web_db->query("SELECT DISTINCT Election, Target FROM smry_propositions WHERE Election > '2002-03-01' ORDER BY Election DESC, Target") as $row) {
    if ($last_election != $row["Election"]) {
      if ("ALL#{$row["Election"]}" == $selected) {echo "<OPTION VALUE=\"ALL#{$row["Election"]}\" SELECTED>" . date("M j, Y", strtotime($row["Election"])) . " ballot measures</OPTION>";} else {echo "<OPTION VALUE=\"ALL#{$row["Election"]}\">" . date("M j, Y", strtotime($row["Election"])) . " ballot measures</OPTION>";}
      $javascript_array .= "[\"ALL#{$row["Election"]}\",\"" . date("M j, Y", strtotime($row["Election"])) . " ballot measures\"],";
      $last_election = $row["Election"];
    }
    if ("{$row["Election"]}#{$row["Target"]}" == $selected) {echo "<OPTION VALUE=\"{$row["Election"]}#" . htmlentities($row["Target"]) . "\" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;" . htmlentities($row["Target"]) . "</OPTION>";} else {echo "<OPTION VALUE=\"{$row["Election"]}#" . htmlentities($row["Target"]) . "\">&nbsp;&nbsp;&nbsp;&nbsp;". htmlentities($row["Target"]) . "</OPTION>";}
    $javascript_array .= "[\"" . str_replace("\"", "'", $row["Election"] . "#" . $row["Target"]) . "\",\"" . str_replace("\"", "'", "\u00a0\u00a0\u00a0\u00a0" . $row["Target"]) . "\"],";
   }
  return $javascript_array;
}


function fill_qs_elections()
{
  global $web_db;
  foreach ($web_db->query("SELECT DISTINCT Election FROM smry_propositions WHERE Election > '2002-03-01' ORDER BY Election DESC, Target") as $row) {
    echo "<OPTION VALUE=\"ALL#{$row["Election"]}\">" . date("F j, Y", strtotime($row["Election"])) . "</OPTION>";
  }
}


function fill_election_cycles($cycles)
{
  global $web_db;
  foreach ($web_db->query("SELECT ElectionCycle FROM smry_cycles ORDER BY ElectionCycle DESC") as $row) {
    $cycle_start = $row["ElectionCycle"];
    $cycle_end = $cycle_start + 1;
    echo "<div>";
    if (in_array($cycle_start, $cycles)) {echo "<input type=\"checkbox\" id=\"y{$cycle_start}\" name=\"cycles[]\" value=\"{$cycle_start}\" onFocus=\"document.getElementById('cycle_dates').checked=true;\" class=\"left caps_radio6\" alt=\"Election Cycle {$cycle_start}\" CHECKED>";} else {echo "<input type=\"checkbox\" id=\"y{$cycle_start}\" name=\"cycles[]\" value=\"{$cycle_start}\" onFocus=\"document.getElementById('cycle_dates').checked=true;\" class=\"left caps_radio6\" alt=\"Election Cycle {$cycle_start}\">";}
    echo "<label for=\"y{$cycle_start}\" class=\"left font_input caps_label5\">$cycle_start-$cycle_end</label>";
    echo "</div>";
  }
}

