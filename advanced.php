<!DOCTYPE html>

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>Campaign Finances Power Search</title>
  <link rel="stylesheet" type="text/css" href="css/caps.css" media="all">
  <script src="js/jquery.js"></script>
  <script src="js/caps.js"></script>
</head>

<body>
<?php
    # Campaign Finance Power Search Project
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
      $_POST["office_list"] = "All Offices";

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
        $_POST["exclude"] = "on";
      }

      if ($_POST["qs_button"] == "Search Contributors") {
        $_POST["contrib_select"] = "search";
        $_POST["contrib_types"] = "all";
        $_POST["proposition_list"] = "ALL";
      }
    }
?>

<!-- default header, replace with a custom header -->
<div id="caps_header">
  <div><img id="maplight_logo" src="img/MapLight_Demo.jpg" style="margin-left:10px; margin-bottom:6px;"></div>
  <div id="caps_menu"><ul>
    <li><b>Campaign Finance Power Search</b></li>
    <li><a href="index.php">Quick Search</a></li>
    <li><a href="advanced.php">Advanced Search</a></li>
  </ul></div>
  <div style="border:2px solid #FF0000; background:#FFCCCC; margin:2px; color:red; text-align:center;"><b>NOTE: This search is in BETA. Please do not cite.</b></div>
</div> <!-- end caps_header -->

<div id="tooltip" class="caps_tooltip"><span id="tooltip_text"></span></div>

<div id="caps_wrapper">
  <div id="caps_container">
    <div id="caps_columns">

      <form method="post">
        <div id="caps_sidebar">
          <h1 class="font_large_header">Advanced Search</h1>
          <a href="advanced.php" id="caps_reset_btn">Clear Form</a>
          <input type="submit" name="search_btn" value="Search" id="caps_search_btn1">

          <!-- Contributions From -->
          <h2 class="clear_both font_title caps_option_title">Contributions From:</h2>
          <?php
            $checked = "";
            if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
            echo "<input type=\"radio\" id=\"all_contribs\" name=\"contrib_select\" value=\"all\" class=\"clear_both left caps_radio1\" {$checked}>";
            echo "<label for=\"all_contribs\" class=\"font_input caps_label1\">All contributors</label>";
            display_tooltip ("Search contributions from all contributors.", 20, -20, 160, "right");

            $checked = "";
            if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"select_contribs\" name=\"contrib_select\" value=\"search\" class=\"clear_both left caps_radio1\" {$checked}>";
            $text = "Just these contributors";
            if (isset ($_POST["contributor"])) {$text = htmlspecialchars($_POST["contributor"]);}
            echo "<input type=\"text\" id=\"search_contribs\" name=\"contributor\" value=\"{$text}\" onFocus=\"document.getElementById('select_contribs').checked=true; if(this.value == 'Just these contributors') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Just these contributors';}\" class=\"left font_input input_border caps_text1\" alt=\"Just These Contributors\">";
            display_tooltip ("Search for a contributor name. Multiple terms can be separated by semicolons.", 20, -20, 160, "right");

            echo "<label for=\"select_location\" class=\"clear_both left font_input caps_label2\">Contributor Location</label>";
            display_tooltip ("Search contributions from a particular state.", 20, -20, 160, "right");
            echo "<select id=\"select_location\" name=\"state_list\" class=\"clear_both left font_input input_border caps_select1\" alt=\"Contributor Location\">";
            $selected = "";
            if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
            fill_state_list ($selected);
            echo "</select>";
            echo "<hr class=\"clear_both caps_hr1\">";
          ?>

          <!-- Contributions To -->
          <h2 class="clear_both font_title caps_option_title">Contributions To:</h2>
          <?php
            # Contributions To Everything
            $checked = "";
            if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "all") {$checked= "checked";}} else {$checked = "checked";}  
            echo "<input type=\"radio\" id=\"comms_to\" name=\"contrib_types\" value=\"all\" class=\"clear_both left caps_radio1\" {$checked} alt=\"Everything (Candidates, Ballot Measures & Other Committees)\">";
            echo "<div class=\"font_input caps_everything_box\">Everything (Candidates, Ballot Measures & Other Committees)</div>";
            echo "<hr class=\"clear_both caps_hr2\">";

            # Contributions To Candidates
            echo "<div class=\"clear_both input_font caps_sidebar_title\">Candidates";
            display_tooltip ("Search contributions to candidate-controlled campaign committees at the state level.", 20, -20, 160, "right");
            echo "</div>";

            $checked = "";
            if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "candidates") {$checked = "checked";}} 
            echo "<input type=\"radio\" id=\"all_cands\" name=\"contrib_types\" value=\"candidates\" class=\"clear_both left caps_radio2\" {$checked} alt=\"All Candidates\">";
            echo "<label for=\"all_cands\" class=\"caps_label1\">All candidates</label>";

            $checked = "";
            if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "search_candidates") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"search_cands\" name=\"contrib_types\" value=\"search_candidates\" class=\"clear_both left caps_radio3\" {$checked} alt=\"Search Candidates\">";
            $text = "Search candidates";
            if (isset ($_POST["search_candidates"])) {$text = $_POST["search_candidates"];}
            echo "<div class=\"left\">";
            echo "<input type=\"hidden\" id=\"match_candidate\" name=\"match_candidate\" value=\"no\">";
            echo "<input type=\"text\" id=\"search_candidates\" name=\"search_candidates\" value=\"{$text}\" onkeyup=\"fill_candidate_list(event);\" onFocus=\"document.getElementById('search_cands').checked=true; if(this.value == 'Search candidates') {this.value = '';} fill_candidate_list(event);\" onBlur=\"if(this.value == '') {this.value = 'Search candidates';}\" class=\"font_input input_border caps_text1\" alt=\"Search Candidates Text\">";
            echo "<div id=\"candidates\" class=\"input_border caps_search_dropbox\"></div>";
            echo "</div>";
            display_tooltip ("Search contributions to a particular candidate\'s campaign committee(s).", 20, -20, 160, "right");

            echo "<select id=\"office_list\" name=\"office_list\" onFocus=\"if(!document.getElementById('all_cands').checked && !document.getElementById('search_cands').checked) {document.getElementById('all_cands').checked=true;}\" class=\"clear_both left font_input input_border caps_select3\" alt=\"Select Office\">";
            $selected = "";
            if (isset ($_POST["office_list"])) {$selected = $_POST["office_list"];}
            fill_offices_sought ($selected);
            echo "</select>";
            display_tooltip ("Search contributions to a candidate(s) running for a particular office.", 20, -20, 160, "right");
            echo "<hr class=\"clear_both caps_hr2\">";

            # Contributions To Ballot Measures
            echo "<div class=\"clear_both input_font caps_sidebar_title\">Ballot Measures";
            display_tooltip ("Search contributions to committees formed to support or oppose ballot measures.", 20, -20, 160, "right");
            echo "</div>";

            $checked = "";
            if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "ballots") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"props_to\" name=\"contrib_types\" value=\"ballots\" class=\"clear_both left caps_radio3\" {$checked} alt=\"Search ballot measures\">";
            $text = "Search ballot measures";
            if (isset ($_POST["search_propositions"])) {$text = $_POST["search_propositions"];}
            echo "<input type=\"text\" id=\"search_propositions\" name=\"search_propositions\" value=\"{$text}\" onFocus=\"document.getElementById('props_to').checked=true; if(this.value == 'Search ballot measures') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Search ballot measures';}\" class=\"font_input input_border caps_text1\" alt=\"Search ballot measures text\">";
            echo "<select id=\"propositions_list\" name=\"proposition_list\" onFocus=\"document.getElementById('props_to').checked=true;\" class=\"left font_input input_border caps_select3\" alt=\"Select ballot measure or election\">";
            $selected = "";
            if (isset ($_POST["proposition_list"])) {$selected = $_POST["proposition_list"];}
            fill_propositions ($selected);
            echo "</select>";

            echo "<select id=\"position\" name=\"position\" onFocus=\"document.getElementById('props_to').checked=true;\" class=\"left font_input input_border caps_select3\" alt=\"Select Proposition or Election\">";
            echo "<option value=\"B\">Both support &amp; oppose</option>";
            if ($_POST["position"] == "S") {echo "<option value=\"S\" SELECTED>Support</option>";} else {echo "<option value=\"S\">Support</option>";}
            if ($_POST["position"] == "O") {echo "<option value=\"O\" SELECTED>Oppose</option>";} else {echo "<option value=\"O\">Oppose</option>";}
            echo "</select>";

            $checked = "";
            if (isset ($_POST["exclude"])) {if ($_POST["exclude"] == "on") {$checked = "checked";}}
            echo "<input type=\"checkbox\" id=\"exclude\" name=\"exclude\" onFocus=\"document.getElementById('props_to').checked=true;\" {$checked} class=\"clear_both left caps_radio4\" alt=\"Exclude contributions between allied committees\">";
            echo "<label for=\"exclude\" class=\"font_input caps_label3\">Exclude contributions between allied committees</label>";
            echo "<hr class=\"clear_both caps_hr2\">";

            # Contributions To Committees
            echo "<div class=\"clear_both input_font caps_sidebar_title\">Committees";
            display_tooltip ("Search contributions to any recipient committee.", 20, -20, 160, "right");
            echo "</div>";

            $checked = "";
            if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "committees") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"search_comms\" name=\"contrib_types\" value=\"committees\" class=\"clear_both left caps_radio3\" {$checked} alt=\"Search Committees\">";
            $text = "Just these committees";
            if (isset ($_POST["search_committees"])) {$text = htmlspecialchars($_POST["search_committees"]);}
            echo "<div class=\"left\">";
            echo "<input type=\"hidden\" id=\"match_committee\" name=\"match_committee\" value=\"no\">";
            echo "<input type=\"text\" id=\"search_committees\" name=\"search_committees\" value=\"{$text}\" onkeyup=\"fill_committee_list(event);\" onFocus=\"document.getElementById('search_comms').checked=true; if(this.value == 'Just these committees') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'Just these committees';}\" class=\"font_input input_border caps_text1\" alt=\"Just These Committees\">";
            echo "<div id=\"committees\" class=\"input_border caps_search_dropbox\"></div>";
            echo "</div>";
            echo "<hr class=\"caps_hr1\">";
          ?>

          <!-- Dates -->
          <h2 class="clear_both font_title caps_option_title">Dates:
          <?php
            display_tooltip ("Search contributions by the date range in which they were made.", 20, -20, 160, "right");
            echo "</h2>";

            $checked = "";
            if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
            echo "<input type=\"radio\" id=\"all_dates\" name=\"date_select\" value=\"all\" class=\"clear_both left caps_radio1\" {$checked} alt=\"All Dates and Election Cycles\">";
            echo "<label for=\"all_dates\" class=\"font_input caps_label1\">All dates and election cycles</label>";

            $checked = "";
            if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "range") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"range_dates\" name=\"date_select\" value=\"range\" class=\"clear_both left caps_radio5\" {$checked} alt=\"Select Date Range\">";
            echo "<div class=\"left font_input caps_label4\"><b>Date range</b></div>";
            $text = "mm/dd/yyyy";
            if (isset ($_POST["start_date"])) {$text = $_POST["start_date"];}
            echo "<input type=\"text\" id=\"start_date\" name=\"start_date\" value=\"{$text}\" onFocus=\"document.getElementById('range_dates').checked=true; if(this.value == 'mm/dd/yyyy') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'mm/dd/yyyy';}\" class=\"clear_both left font_input input_border caps_text2\" alt=\"Enter Start Date\">";
            echo "<span class=\"left\">&nbsp;<b>to</b>&nbsp;</span>";
            if (isset ($_POST["end_date"])) {$text = $_POST["end_date"];}
            echo "<input type=\"text\" name=\"end_date\" value=\"{$text}\" onFocus=\"document.getElementById('range_dates').checked=true; if(this.value == 'mm/dd/yyyy') {this.value = '';}\" onBlur=\"if(this.value == '') {this.value = 'mm/dd/yyyy';}\" class=\"left font_input input_border caps_text3\" alt=\"Enter Ending Date\">";

            $checked = "";
            if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "cycle") {$checked = "checked";}}  
            echo "<input type=\"radio\" id=\"cycle_dates\" name=\"date_select\" value=\"cycle\" class=\"clear_both left caps_radio5\" {$checked} alt=\"Election Cycles\">";
            echo "<div class=\"left font_input caps_label4\">Election cycles</div>";
            echo "<div id=\"caps_cycles_box\">";
            if (isset ($_POST["cycles"])) {$cycles = $_POST["cycles"];} else {$cycles = array ("");}
            fill_election_cycles ($cycles, "");
            echo "</div> <!-- end caps_cycles_box -->";

            echo "<input type=\"submit\" name=\"search_btn\" value=\"Search\" id=\"caps_search_btn2\">";
          ?>
        </div> <!-- end caps_sidebar -->

        <div id="caps_content"> 
          <?php build_results_table (); ?>
        </div> <!-- end caps_content -->
      </form>

    </div> <!-- end caps_columns -->
  </div> <!-- end caps_containter -->

  <div id="maplight_info">Power Search software by <a href="http://www.maplight.org">MapLight</a><br><center><img src="img/MapLight_Logo.png" width="80"></center></div>

</div> <!-- end caps_wrapper-->

<!-- Place custom page footer here -->
<footer style="display:none;"></footer>

</body>
</html>
