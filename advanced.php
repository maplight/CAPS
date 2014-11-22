<!DOCTYPE html>

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CAL-ACCESS Campaign Power Search</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main-max-width.css">
  <noscript><link href="css/noJs.css" rel="stylesheet" type="text/css" /></noscript>
  <!--[if lt IE 9]>
    <link rel="stylesheet" href="css/ie-main-max-width.css" media="screen" />
    <script src="js/vendor/compatibility.js"></script>
  <![endif]-->
  <link rel="stylesheet" type="text/css" href="css/caps.css" media="all">
  <script src="js/vendor/ga-async.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/caps.js"></script>
</head>

<body>
<form action="advanced.php" method="post">
<?php
    # Cal-Access Campaign Power Search Project
    # MapLight
    # Mike Krejci

    # Load required libraries
    require ("connect.php");
    require ("sidebar.php");
    require ("results.php");

    # Check for quick search entry
    if (isset ($_POST["quick_search"])) {
      $_POST["state_list"] = "ALL";
      $_POST["date_select"] = "all";
      $_POST["show_summary"] = "yes";

      if ($_POST["qs_button"] == "Search Candidates") {
        $_POST["contrib_select"] = "all";
        $_POST["contributor"] = "Just these contributors";
        $_POST["contrib_types"] = "search_candidates";
        $_POST["proposition_list"] = "ALL";
      }

      if ($_POST["qs_button"] == "Search Ballot Measures") {
        $_POST["contrib_select"] = "all";
        $_POST["contributor"] = "Just these contributors";
        $_POST["contrib_types"] = "ballots";
        $_POST["search_propositions"] = "Search propositions";
        $_POST["position"] = "B";
        $_POST["date_select"] = "all";
      }

      if ($_POST["qs_button"] == "Search Contributors") {
        $_POST["contrib_select"] = "search";
        $_POST["contrib_types"] = "all";
        $_POST["proposition_list"] = "ALL";
      }
    }
?>

<div id="tooltip" class="tooltip"><span id="tooltip_text"></span></div>

<div id="caps_header">
<div class="frme" id="top">
<img src="img/MapLight_Demo.jpg" style="margin-left:10px; margin-bottom:6px;">
</div>
        <ul id="utl" class="clearfix">
        <li><b>CAL-ACCESS Campaign Power Search</b></li>
        <li><a href="index.php">Quick Search</a></li>
        <li><a href="advanced.php">Advanced Search</a></li>
        </ul>

<div style="border:2px solid #FF0000; background:#FFCCCC; margin:2px; color:red; text-align:center;"><b>NOTE: This search is in BETA. Please do not cite.</b></div>

</div> <!-- caps_header -->

<div id="wrapper">
  <div id="container">
    <div id="columns">
        <div id="sidebar">
          <h1 class="caps_title1">Advanced Search</h1>
          <a href="advanced.php" id="caps_reset_btn">Clear Form</a>
          <input type="submit" name="search_btn" value="Search" id="caps_search_btn">

<!-- Contributions From -->
          <h2 class="caps_header1">Contributions From:</h2>
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
  echo "<input type=\"radio\" id=\"all_contribs\" name=\"contrib_select\" value=\"all\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="all_contribs" class="caps_label1">All contributors</label>

          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 1);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions from all contributors.">

<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"select_contribs\" name=\"contrib_select\" value=\"search\" class=\"caps_radio1\" {$checked}>";
  $text = "Just these contributors";
  if (isset ($_POST["contributor"])) {$text = $_POST["contributor"];}
  echo "<input type=\"text\" id=\"search_contribs\" name=\"contributor\" value=\"{$text}\" class=\"caps_text1\" onFocus=\"if(this.value == 'Just these contributors') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Just these contributors';}\">";
?>
          <label for="select_location" class="caps_label2">Contributor Location</label>
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 2);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions from a particular state.">
          <select id="select_location" name="state_list" class="caps_select1">
<?php
  $selected = "";
  if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
  fill_state_list ($selected);
?>
          </select>
          <hr class="caps_hr1">

<!-- Contributions To -->
          <h2 class="caps_header1">Contributions To:</h2>

<!-- Contributions To Everything -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "all") {$checked= "checked";}} else {$checked = "checked";}  
  echo "<input type=\"radio\" id=\"comms_to\" name=\"contrib_types\" value=\"all\" class=\"caps_radio1\" {$checked}>";
?>
          <div class="caps_box1">Everything (Candidates, Ballot Measures & Other Committees)</div>
          <hr class="caps_hr2">

<!-- Contributions To Candidates -->
          <div class="caps_header2">Candidates
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 3);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions to candidate campaigns only.">
          </div>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "candidates") {$checked = "checked";}} 
  echo "<input type=\"radio\" id=\"all_cands\" name=\"contrib_types\" value=\"candidates\" class=\"caps_radio2\" {$checked}>";
?>
          <label for="all_cands" class="caps_label1">All candidates</label>
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "search_candidates") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"search_cands\" name=\"contrib_types\" value=\"search_candidates\" class=\"caps_radio3\" {$checked}>";
  $text = "Search candidates";
  if (isset ($_POST["search_candidates"])) {$text = $_POST["search_candidates"];}
  echo "<input type=\"text\" id=\"search_candidates\" name=\"search_candidates\" value=\"{$text}\" class=\"caps_text1\" onkeyup=\"filter_candidates_list();\" onFocus=\"if(this.value == 'Search candidates') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Search candidates';}\">";
?>
          <select id="candidate_list" name="candidate_list" class="caps_select2">
<?php
  $selected = "";
  if (isset ($_POST["candidate_list"])) {$selected = $_POST["candidate_list"];}
  $js_candidates = fill_candidate_names ($selected, "1999");
?>
          </select>
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 4);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions to a particular candidate\'s campaign(s).">
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "office") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"select_cands\" name=\"contrib_types\" value=\"office\" class=\"caps_radio3\" {$checked}>";
?>
          <select id="office_list" name="office_list" class="caps_select3">
<?php
  $selected = "";
  if (isset ($_POST["office_list"])) {$selected = $_POST["office_list"];}
  fill_offices_sought ($selected);
?>
          </select>
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 5);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions to all candidates running for a particular office.">
          <hr class="caps_hr2">

<!-- Contributions To Ballot Measures -->
          <div class="caps_header2">Ballot Measures
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 6);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions to committees formed to support or oppose ballot measures. Your results may return duplicate contributions if a contributor gave money to a committee supporting or opposing multiple ballot measures.">
          </div>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "ballots") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"props_to\" name=\"contrib_types\" value=\"ballots\" class=\"caps_radio3\" {$checked}>";
  $text = "Search propositions";
  if (isset ($_POST["search_propositions"])) {$text = $_POST["search_propositions"];}
  echo "<input type=\"text\" id=\"search_propositions\" name=\"search_propositions\" value=\"{$text}\" class=\"caps_text1\" onkeyup=\"filter_propositions_list();\" onFocus=\"if(this.value == 'Search propositions') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Search propositions';}\">";
?>
          <select id="propositions_list" name="proposition_list" class="caps_select2">
<?php
  $selected = "";
  if (isset ($_POST["proposition_list"])) {$selected = $_POST["proposition_list"];}
  $js_propositions = fill_propositions ($selected);
?>
          </select>
          <select id="position" name="position" class="caps_select2">
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
          <hr class="caps_hr2">

<!-- Contributions To Committees -->
          <div class="caps_header2">Committees
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 7);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions to other committees, such as candidate office holder and legal defense committees.">
          </div>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "committees") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"search_cands\" name=\"contrib_types\" value=\"committees\" class=\"caps_radio3\" {$checked}>";
  $text = "Just these committees";
  if (isset ($_POST["committee_search"])) {$text = $_POST["committee_search"];}
  echo "<input type=\"text\" id=\"committee_search\" name=\"committee_search\" value=\"{$text}\" class=\"caps_text1\" onFocus=\"if(this.value == 'Just these committees') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Just these committees';}\">";
?>
          <hr class="caps_hr3">

<!-- Dates -->
          <h2 class="caps_header1">Dates:
          <img src="img/infotool.png" class="info" onMouseOver="this.src='img/infotool-hover.png'; display_tooltip(event, 8);" onMouseOut="this.src='img/infotool.png'; document.getElementById('tooltip').style.display = 'none';" alt="Search contributions by the date range in which they were made.">
          </h2>
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
  fill_election_cycles ($cycles, "");
?>
          </div> <!-- cycles_box -->
          <input type="submit" name="search_btn"  value="Search" id="caps_search_btn">

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
    </div> <!-- # columns -->
  </div> <!-- #containter -->
</div> <!-- #wrapper-->
</form>

</body>
</html>
