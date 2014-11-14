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
  <li><a href="index.php">Quick Search</a></li>
  <li><a href="advanced.php">Advanced Search</a></li>
</ul>

<b>NOTE: This search is in BETA. Please do not cite.</b><p>

<h1>Advanced Search</h1>
<p><a href="nocss.php">Reset Form</a></p>
<form method="post">
  <input type="submit" value="Search">

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
  echo "<input type=\"text\" name=\"contributor\" value=\"{$text}\"><br>";
?>

<b>Contributor Location</b><br>
<select id="select_location" name="state_list" class="caps_select1">

<?php
  $selected = "";
  if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
  fill_state_list ($selected);
?>

</select>
<hr>

<!-- Contributions To -->
          <h2 class="caps_header1">Contributions To:</h2>

<!-- Contributions To Everything -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "all") {$checked= "checked";}} else {$checked = "checked";}  
  echo "<input type=\"radio\" id=\"comms_to\" name=\"contrib_types\" value=\"all\" class=\"caps_radio1\" {$checked}>";
?>
          <div class="caps_box1">Everything (Candidates, Ballot Measures & Other Committees)</div>
          <hr class="caps_hr1">

<!-- Contributions To Candidates -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "candidates") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"contrib_to\" name=\"contrib_types\" value=\"candidates\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="contrib_to" class="caps_label1">Candidates</label>
          <a href="#" class="info"></a>
<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" id=\"all_cands\" name=\"cand_select\" value=\"all\" class=\"caps_radio2\" {$checked}>";
?>
          <label for="all_cands" class="caps_label1">All candidates</label>
<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"search_cands\" name=\"cand_select\" value=\"search\" class=\"caps_radio3\" {$checked}>";
  $text = "Search candidates";
  if (isset ($_POST["search_candidates"])) {$text = $_POST["search_candidates"];}
  echo "<input type=\"text\" id=\"search_candidates\" name=\"search_candidates\" value=\"{$text}\" class=\"caps_text1\" onkeyup=\"filter_candidates_list();\">";
?>
          <select id="candidate_list" name="candidate_list" class="caps_select2">
<?php
  $selected = "";
  if (isset ($_POST["candidate_list"])) {$selected = $_POST["candidate_list"];}
  $js_candidates = fill_candidate_names ($selected, "1999");
?>
          </select>
          <a href="#" class="info"></a>
<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "office") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"select_cands\" name=\"cand_select\" value=\"office\" class=\"caps_radio3\" {$checked}>";
?>
          <select id="office_list" name="office_list" class="caps_select3">
<?php
  $selected = "";
  if (isset ($_POST["office_list"])) {$selected = $_POST["office_list"];}
  fill_offices_sought ($selected);
?>
          </select>
          <a href="#" class="info"></a>
          <hr class="caps_hr1">

<!-- Contributions To Ballot Measures -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "ballots") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"props_to\" name=\"contrib_types\" value=\"ballots\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="props_to" class="caps_label1">Ballot Measures</label>
          <a href="#" class="info"></a>
<?php
  $text = "Search propositions";
  if (isset ($_POST["search_propositions"])) {$text = $_POST["search_propositions"];}
  echo "<input type=\"text\" id=\"search_propositions\" name=\"search_propositions\" value=\"{$text}\" class=\"caps_text2\" onkeyup=\"filter_propositions_list();\">";
?>
          <select id="propositions_list" name="proposition_list" class="caps_select4">
<?php
  $selected = "";
  if (isset ($_POST["proposition_list"])) {$selected = $_POST["proposition_list"];}
  $js_propositions = fill_propositions ($selected);
?>
          </select>
          <select id="position" name="position" class="caps_select4">
          <option value="B">Both support &amp; oppose</option>
<?php
  if ($_POST["position"] == "S") {echo "<option value=\"S\" SELECTED>Support</option>";} else {echo "<option value=\"S\">Support</option>";}
  if ($_POST["position"] == "O") {echo "<option value=\"O\" SELECTED>Oppose</option>";} else {echo "<option value=\"O\">Oppose</option>";}
?>
          </select>
<?php
  $checked = "";
  if (isset ($_POST["exclude"])) {if ($_POST["exclude"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"exclude\" name=\"exclude\" class=\"caps_radio4\" {$checked}>";
?>
          <label for="exclude" class="caps_label3">Exclude contributions between allied committees</label>
          <hr class="caps_hr1">

<!-- Contributions To Committees -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "committees") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"comms_to\" name=\"contrib_types\" value=\"committees\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="comms_to" class="caps_label1">Committees</label>
          <a href="#" class="info"></a>
<?php
  $text = "Just these committees";
  if (isset ($_POST["committee_search"])) {$text = $_POST["committee_search"];}
  echo "<input type=\"text\" id=\"committee_search\" name=\"committee_search\" value=\"{$text}\" class=\"caps_text5\">";
?>
          <hr class="caps_hr1">

<!-- Dates -->
          <h2 class="caps_header1">Dates:<a href="#" class="info"></a></h2>
<?php
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" id=\"all_dates\" name=\"date_select\" value=\"all\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="all_dates" class="caps_label1">All dates and election cycles</label>
<?php
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "range") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"range_dates\" name=\"date_select\" value=\"range\" class=\"caps_radio5\" {$checked}>"
?>
          <label for="range_dates" class="caps_label4">Date range</label>
<?php
  $text = "mm/dd/yyyy";
  if (isset ($_POST["start_date"])) {$text = $_POST["start_date"];}
  echo "<input type=\"text\" id=\"start_date\" name=\"start_date\" value=\"{$text}\" class=\"caps_text3\">";
  echo "<label for=\"start_date\" class=\"caps_label5\">to</label>";
  if (isset ($_POST["end_date"])) {$text = $_POST["end_date"];}
  echo "<input type=\"text\" name=\"end_date\" value=\"{$text}\" class=\"caps_text4\">";
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "cycle") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"cycle_dates\" name=\"date_select\" value=\"cycle\" class=\"caps_radio5\" {$checked}>";
?>
          <label for="cycle_dates" class="caps_label4">Election cycles</label>
          <div id="cycles_box">
<?php
  if (isset ($_POST["cycles"])) {$cycles = $_POST["cycles"];} else {$cycles = array ("");}
  fill_election_cycles ($cycles);
?>
          </div> <!-- cycles_box -->
          <input type="submit" value="Search" id="caps_search_btn">

<?php
  # Data for javascript to filter select boxes
  echo "<SCRIPT type=text/javascript>";
  echo "var candidates = [{$js_candidates}\"\"];\n";
  echo "var propositions = [{$js_propositions}\"\"];\n";
  echo "</SCRIPT>";
?>
        </div> <!-- #sidebar -->

        <div id="content">
<?php
  build_results_table ();
?>
        </div> <!-- #content -->
      </form>
    </div> <!-- # columns -->
  </div> <!-- #containter -->
</div> <!-- #wrapper-->

</body>
</html>
