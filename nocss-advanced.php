<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
  <title>CAL-ACCESS Campaign Power Search</title>
</head>

<body>
<?php
    # Cal-Access Campaign Power Search Project
    # MapLight
    # Mike Krejci

    # Load required libraries
    require ("connect.php");
    require ("sidebar.php");
    require ("results.php");
?>
<img src="img/MapLight_Demo.jpg">

<ul>
  <li><b>CAL-ACCESS Campaign Power Search</b></li>
  <li><a href="nocss.php">Quick Search</a></li>
  <li><a href="nocss-advanced.php">Advanced Search</a></li>
  <li><a href="index.php">Graphical Power Search</a></li>
</ul>

<b>NOTE: This search is in BETA. Please do not cite.</b><p>

<h1>Advanced Search</h1>
<p><a href="nocss-advanced.php">Reset Form</a></p>
<form method="post">
  <h2>Contributions From:</h2>

<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
  echo "<input type=\"radio\" name=\"contrib_select\" value=\"all\" {$checked}> All contributors (<i>tooltip info</i>)<br>";

  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"contrib_select\" value=\"search\" {$checked}>";

  $text = "Just these contributors";
  if (isset ($_POST["contributor"])) {$text = $_POST["contributor"];}
  echo "<input type=\"text\" name=\"contributor\" value=\"{$text}\"><p>";
?>

  <b>Contributor Location</b> (<i>tooltip info</i>)<br>
  <select id="select_location" name="state_list" class="caps_select1">
<?php
  $selected = "";
  if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
  fill_state_list ($selected);
?>
  </select>
  <hr>

  <h2>Contributions To:</h2>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "all") {$checked= "checked";}} else {$checked = "checked";}  
  echo "<input type=\"radio\" name=\"contrib_types\" value=\"all\" {$checked}> ";
?>
  <b>Everything (Candidates, Ballot Measures & Other Committees)</b> (<i>Tip about selecting the Contributions To category</i>)<p>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "candidates") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"contrib_types\" value=\"candidates\" {$checked}> ";
?>
  <b>Candidates</b> (<i>Tooltip info</i>)
  <blockquote>

<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" name=\"cand_select\" value=\"all\" {$checked}> All candidates<br>";

  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"cand_select\" value=\"search\" {$checked} >";
  $text = "Search candidates";
  if (isset ($_POST["search_candidates"])) {$text = $_POST["search_candidates"];}
  echo "<input type=\"text\" name=\"search_candidates\" value=\"{$text}\"> ";
?>

    <select name="candidate_list">
<?php
  $selected = "";
  if (isset ($_POST["candidate_list"])) {$selected = $_POST["candidate_list"];}
  fill_candidate_names ($selected, "1999");
?>
    </select> (<i>Tooltop info</i>)<br>

<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "office") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"cand_select\" value=\"office\" {$checked}> ";
?>

    <select name="office_list">
<?php
  $selected = "";
  if (isset ($_POST["office_list"])) {$selected = $_POST["office_list"];}
  fill_offices_sought ($selected);
?>
    </select> (<i>Tooltip text)</i><br>
  </blockquote>
  <hr>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "ballots") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"contrib_types\" value=\"ballots\" {$checked}> ";
?>
  <b>Ballot Measures</b> (<i>Tooltip info</i>)
  <blockquote>

<?php
  $text = "Search propositions";
  if (isset ($_POST["search_propositions"])) {$text = $_POST["search_propositions"];}
  echo "<input type=\"text\" name=\"search_propositions\" value=\"{$text}\"> ";
?>

    <select id="propositions_list" name="proposition_list">
<?php
  $selected = "";
  if (isset ($_POST["proposition_list"])) {$selected = $_POST["proposition_list"];}
  fill_propositions ($selected);
?>
    </select><br>

    <select name="position">
      <option value="B">Both support & oppose</option>
<?php
  if ($_POST["position"] == "S") {echo "<option value=\"S\" SELECTED>Support</option>";} else {echo "<option value=\"S\">Support</option>";}
  if ($_POST["position"] == "O") {echo "<option value=\"O\" SELECTED>Oppose</option>";} else {echo "<option value=\"O\">Oppose</option>";}
?>
    </select><br>

<?php
  $checked = "";
  if (isset ($_POST["exclude"])) {if ($_POST["exclude"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" name=\"exclude\" {$checked}> Exclude contributions between allied committees<br>";
?>
  </blockquote>
  <hr>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "committees") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"contrib_types\" value=\"committees\" {$checked}> ";
?>
  <b>Committees</b>
<?php
  $text = "Committee name contains";
  if (isset ($_POST["committee_search"])) {$text = $_POST["committee_search"];}
  echo " <input type=\"text\" name=\"committee_search\" value=\"{$text}\"> (<i>Tooltip text</i>)";
?>
  <hr>

  <h2>Dates:</h2>

<?php
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" name=\"date_select\" value=\"all\" {$checked}> All dates and election cycles<br>";

  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "range") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"date_select\" value=\"range\" {$checked}> Date range ";

  $text = "mm/dd/yyyy";
  if (isset ($_POST["start_date"])) {$text = $_POST["start_date"];}
  echo "<input type=\"text\" name=\"start_date\" value=\"{$text}\"> to ";
  if (isset ($_POST["end_date"])) {$text = $_POST["end_date"];}
  echo "<input type=\"text\" name=\"end_date\" value=\"{$text}\"><br>";

  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "cycle") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"date_select\" value=\"cycle\" {$checked}> Election cycles";
?>

  <blockquote>
<?php
  if (isset ($_POST["cycles"])) {$cycles = $_POST["cycles"];} else {$cycles = array ("");}
  fill_election_cycles ($cycles, true);
?>
  </blockquote>

  <input type="submit" value="Search">

<?php
  build_results_table (true);
?>

  </form>

</body>
</html>
