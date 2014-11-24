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
<?php
    # CAL-ACCESS Campaign Power Search Project
    # Written by Mike Krejci for MapLight

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

<!-- default header, replace with sites header -->
<div id="caps_header">
  <div><img src="img/MapLight_Demo.jpg" style="margin-left:10px; margin-bottom:6px;"></div>
  <ul id="utl">
        <li><b>CAL-ACCESS Campaign Power Search</b></li>
        <li><a href="index.php">Quick Search</a></li>
        <li><a href="advanced.php">Advanced Search</a></li>
  </ul>
  <div style="border:2px solid #FF0000; background:#FFCCCC; margin:2px; color:red; text-align:center;"><b>NOTE: This search is in BETA. Please do not cite.</b></div>
</div> <!-- end caps_header -->


<div id="tooltip" class="caps_tooltip"><span id="tooltip_text"></span></div>

<div id="caps_wrapper">
  <div id="caps_container">
    <div id="caps_columns">

      <form method="post">
        <div id="caps_sidebar">
          <h1 class="font_large_header">Advanced Search</h1><br>
          <a href="advanced.php" id="caps_reset_btn">Clear Form</a>
          <input type="submit" name="search_btn" value="Search" id="caps_search_btn">

<!-- Contributions From -->
          <h2 class="font_title caps_option_title">Contributions From:</h2>
          <?php
            $checked = "";
            if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
            echo "<input type=\"radio\" id=\"all_contribs\" name=\"contrib_select\" value=\"all\" class=\"clear_both left caps_radio1\" {$checked}>";
            echo "<label for=\"all_contribs\" class=\"font_input caps_label1\">All contributors</label>";
            display_tooltip ("Search contributions from all contributors.", 20, -20, 150);

            $checked = "";
            if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"select_contribs\" name=\"contrib_select\" value=\"search\" class=\"clear_both left caps_radio1\" {$checked}>";
            $text = "Just these contributors";
            if (isset ($_POST["contributor"])) {$text = htmlspecialchars($_POST["contributor"]);}
            echo "<input type=\"text\" id=\"search_contribs\" name=\"contributor\" value=\"{$text}\" onFocus=\"document.getElementById('select_contribs').checked=true; if(this.value == 'Just these contributors') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Just these contributors';}\" class=\"font_input input_border caps_text1\" alt=\"Just These Contributors\">";

            echo "<label for=\"select_location\" class=\"clear_both left font_input caps_label2\">Contributor Location</label>";
            display_tooltip ("Search contributions from a particular state.", 20, -20, 160);
            echo "<select id=\"select_location\" name=\"state_list\" class=\"clear_both left font_input input_border caps_select1\" alt=\"Contributor Location\">";
            $selected = "";
            if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
            fill_state_list ($selected);
            echo "</select>";
            echo "<hr class=\"caps_hr1\">";
          ?>

<!-- Contributions To -->
          <h2 class="font_title caps_option_title">Contributions To:</h2>
          <?php
            # Contributions To Everything
            $checked = "";
            if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "all") {$checked= "checked";}} else {$checked = "checked";}  
            echo "<input type=\"radio\" id=\"comms_to\" name=\"contrib_types\" value=\"all\" class=\"clear_both left caps_radio1\" {$checked} alt=\"Everything (Candidates, Ballot Measures & Other Committees)\">";
            echo "<div class=\"font_input caps_everything_box\">Everything (Candidates, Ballot Measures & Other Committees)</div>";
            echo "<hr class=\"caps_hr2\">";

            # Contributions To Candidates

?>
<!-- Contributions To Candidates -->
          <div class="clear_both input_font caps_sidebar_title">Candidates

<?php
  display_tooltip ("Search contributions to candidate campaigns only.", 20, -20, 160);
?>

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
  echo "<div style=\"float:left\">";
  echo "<input type=\"hidden\" id=\"match_candidate\" name=\"match_candidate\" value=\"no\">";
  echo "<input type=\"text\" id=\"search_candidates\" name=\"search_candidates\" value=\"{$text}\" class=\"caps_text1\" onkeyup=\"fill_candidate_list(event);\" onFocus=\"document.getElementById('search_cands').checked=true; if(this.value == 'Search candidates') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Search candidates';}\">";
  echo "<div id=\"candidates\" class=\"search_dropbox\"></div>";
  echo "</div>";
?>

<?php
  display_tooltip ("Search contributions to a particular candidate\'s campaign(s).", 20, -20, 160);
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "office") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"select_cands\" name=\"contrib_types\" value=\"office\" class=\"caps_radio3\" {$checked}>";
?>

          <select id="office_list" name="office_list" class="caps_select3" onFocus="document.getElementById('select_cands').checked=true;">
<?php
  $selected = "";
  if (isset ($_POST["office_list"])) {$selected = $_POST["office_list"];}
  fill_offices_sought ($selected);
?>
          </select>

<?php
  display_tooltip ("Search contributions to all candidates running for a particular office.", 20, -20, 160);
?>

          <hr class="caps_hr1">

<!-- Contributions To Ballot Measures -->
          <div class="caps_header2">Ballot Measures

<?php
  display_tooltip ("Search contributions to committees formed to support or oppose ballot measures. Your results may return duplicate contributions if a contributor gave money to a committee supporting or opposing multiple ballot measures.", 20, -20, 240);
?>

          </div>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "ballots") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"props_to\" name=\"contrib_types\" value=\"ballots\" class=\"caps_radio3\" {$checked}>";
  $text = "Search propositions";
  if (isset ($_POST["search_propositions"])) {$text = $_POST["search_propositions"];}
  echo "<input type=\"text\" id=\"search_propositions\" name=\"search_propositions\" value=\"{$text}\" class=\"caps_text1\" onkeyup=\"filter_propositions_list();\" onFocus=\"document.getElementById('props_to').checked=true; if(this.value == 'Search propositions') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Search propositions';}\">";
?>
          <select id="propositions_list" name="proposition_list" class="caps_select2" onFocus="document.getElementById('props_to').checked=true;">
<?php
  $selected = "";
  if (isset ($_POST["proposition_list"])) {$selected = $_POST["proposition_list"];}
  $js_propositions = fill_propositions ($selected);
?>
          </select>
          <select id="position" name="position" class="caps_select2" onFocus="document.getElementById('props_to').checked=true;">
          <option value="B">Both support &amp; oppose</option>
<?php
  if ($_POST["position"] == "S") {echo "<option value=\"S\" SELECTED>Support</option>";} else {echo "<option value=\"S\">Support</option>";}
  if ($_POST["position"] == "O") {echo "<option value=\"O\" SELECTED>Oppose</option>";} else {echo "<option value=\"O\">Oppose</option>";}
?>
          </select>
<?php
  $checked = "";
  if (isset ($_POST["exclude"])) {if ($_POST["exclude"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"exclude\" name=\"exclude\" class=\"caps_radio4\" onFocus=\"document.getElementById('props_to').checked=true;\" {$checked}>";
?>
          <label for="exclude" class="caps_label3">Exclude contributions between allied committees</label>
          <hr class="caps_hr2">

<!-- Contributions To Committees -->
          <div class="caps_header2">Committees

<?php
  display_tooltip ("Search contributions to any recipient committee(s) by name.", 20, -20, 160);
?>

          </div>

<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "committees") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"search_comms\" name=\"contrib_types\" value=\"committees\" class=\"caps_radio3\" {$checked}>";
  $text = "Just these committees";
  if (isset ($_POST["search_committees"])) {$text = $_POST["search_committees"];}
  echo "<div style=\"float:left\">";
  echo "<input type=\"hidden\" id=\"match_committee\" name=\"match_committee\" value=\"no\">";
  echo "<input type=\"text\" id=\"search_committees\" name=\"search_committees\" value=\"{$text}\" class=\"caps_text1\" onkeyup=\"fill_committee_list(event);\" onFocus=\"document.getElementById('search_comms').checked=true; if(this.value == 'Just these committees') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Just these committees';}\">";
  echo "<div id=\"committees\" class=\"search_dropbox\"></div>";
  echo "</div>";
?>
          <hr class="caps_hr3">

<!-- Dates -->
          <h2 class="caps_header1">Dates:

<?php
  display_tooltip ("Search contributions by the date range in which they were made.", 20, -20, 160);
?>

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
  echo "<input type=\"text\" id=\"start_date\" name=\"start_date\" value=\"{$text}\" class=\"caps_text3\" onFocus=\"document.getElementById('range_dates').checked=true;\">";
  echo "<label for=\"start_date\" class=\"caps_label5\">to</label>";
  if (isset ($_POST["end_date"])) {$text = $_POST["end_date"];}
  echo "<input type=\"text\" name=\"end_date\" value=\"{$text}\" class=\"caps_text4\" onFocus=\"document.getElementById('range_dates').checked=true;\">";
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
  echo "var propositions = [{$js_propositions}\"\"];\n";
  echo "</SCRIPT>";
?>
        </div> <!-- end caps_sidebar -->

        <div id="caps_content"> 
          <?php build_results_table (); ?>
        </div> <!-- end caps_content -->
      </form>

    </div> <!-- end caps_columns -->
  </div> <!-- end caps_containter -->
</div> <!-- end caps_wrapper-->

<!-- Place custom page footer here -->
<footer style="display:none;"></footer>

</body>
</html>
