<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>California Secretary of State</title>
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

      if ($_POST["qs_button"] == "Search Candidates") {
        $_POST["contrib_select"] = "all";
        $_POST["contributor"] = "Just these contributors";
        $_POST["contrib_types"] = "candidates";
        $_POST["cand_select"] = "search";
        $_POST["date_select"] = "cycle";
        $cycles = array ();
        $result = my_query ("SELECT * FROM smry_cycles ORDER BY ElectionCycle DESC LIMIT 2");
         while ($row = $result->fetch_assoc()) {$cycles[] = $row["ElectionCycle"];}
        $_POST["cycles"] = $cycles;      
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
        $_POST["date_select"] = "cycle";
        $cycles = array ();
        $result = my_query ("SELECT * FROM smry_cycles ORDER BY ElectionCycle DESC LIMIT 2");
         while ($row = $result->fetch_assoc()) {$cycles[] = $row["ElectionCycle"];}
        $_POST["cycles"] = $cycles;      
      }
    }
?>

<div id="caps_header">
<!-- California SOS header page -->
    <div class="frme" id="top">
    <!-- utility navigation -->
    <div id="utl" class="clearfix">
        <ul>
        <li><a href="http://registertovote.ca.gov/#mainCont">Skip to Main Content</a></li>
        <li class="listInlineLastChild"><a href="http://registertovote.ca.gov/#footer">Skip to Footer</a></li>
        </ul>
    </div>
    <!-- // utility navigation -->
    <!-- banner -->
    <div id="bnrCtnr" role="banner">
    <div id="bnrLgoTxt">California Secretary of State Debra Bowen</div>
    </div>
    <!-- // banner -->
    <!-- mobile menu(s) -->
    <div class="mainNavTogBtn" aria-labeledby="nav" aria-describedby="mainNavTogCont"><a href="#"><span class="visuallyhidden">Navigation Menu</span></a></div>
    <!-- //mobile menu(s) -->
    </div><!-- end of "frme" -->
    <!-- SITE NAVIGATION -->
    <div id="mainNavCtnr">
    <div id="mainNavCtnd">
    <nav id="nav" aria-label="Main">
    <div class="nav-grid-75">
    <div id="mainNavTogCont" aria-hidden="false">
        <!-- main navigation -->
        <ul id="utl" class="clearfix">
        <li><b>CAL-ACCESS Campaign Power Search</b></li>
        <li><a href="index.php">Quick Search</a></li>
        <li><a href="advanced.php">Advanced Search</a></li>
        <li><a href="http://registertovote.ca.gov//help/">Website Help</a></li>
        </ul>
        <!-- // main navigation -->
    </div>
    </div><!-- end of .nav-grid-75 -->
    <div class="clear"></div><!-- clear needed to maintain the full height of nav bar -->
    </nav><!--end of #nav-->
    </div><!--end of .mainNavCtnd-->
    </div><!--end of .mainNavCtnr-->
<!-- End California SOS header page -->
</div> <!-- caps_header -->

<div id="wrapper">
  <div id="container">
    <div id="columns">
      <form method="post">
        <div id="sidebar">
          <h1 class="caps_title1">Advanced Search</h1>
          <input type="submit" value="Search" id="caps_search_btn">
          <a href="advanced.php" id="caps_reset_btn">Reset</a>

<!-- Contributions From -->
          <h2 class="caps_header1">Contributions From:</h2>
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
  echo "<input type=\"radio\" id=\"all_contribs\" name=\"contrib_select\" value=\"all\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="all_contribs" class="caps_label1">All contributors</label>
          <div class="info" title="Search contributions from all contributors"></div>
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"select_contribs\" name=\"contrib_select\" value=\"search\" class=\"caps_radio1\" {$checked}>";
  $text = "Just these contributors";
  if (isset ($_POST["contributor"])) {$text = $_POST["contributor"];}
  echo "<input type=\"text\" id=\"search_contribs\" name=\"contributor\" value=\"{$text}\" class=\"caps_text1\">";
?>
          <label for="select_location" class="caps_label2">Contributor Location</label>
          <div class="info" title="Search contributions from a particular state"></div>
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
          <hr class="caps_hr1">

<!-- Contributions To Candidates -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "candidates") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"contrib_to\" name=\"contrib_types\" value=\"candidates\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="contrib_to" class="caps_label1">Candidates</label>
          <div class="info" title="Search contributions to candidate campaigns only"></div>
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
  $js_candidates = fill_candidate_names ($selected);
?>
          </select>
          <div class="info" title="Search contributions to a particular candidate's campaign(s)"></div>
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
          <div class="info" title="Search contributions to all candidates running for a particular office"></div>
          <hr class="caps_hr1">

<!-- Contributions To Ballot Measures -->
<?php
  $checked = "";
  if (isset ($_POST["contrib_types"])) {if ($_POST["contrib_types"] == "ballots") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"props_to\" name=\"contrib_types\" value=\"ballots\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="props_to" class="caps_label1">Ballot Measures</label>
          <div class="info" title="Search contributions to committees formed to support or oppose ballot measures. Your results may return duplicate contributions if a contributor gave money to a committee supporting or opposing multiple ballot measures."></div>
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
          <div class="info" title="Search contributions to other committees, such as candidate office holder and legal defense committees"></div>
<?php
  $text = "Just these committees";
  if (isset ($_POST["committee_search"])) {$text = $_POST["committee_search"];}
  echo "<input type=\"text\" id=\"committee_search\" name=\"committee_search\" value=\"{$text}\" class=\"caps_text5\">";
?>
          <hr class="caps_hr1">

<!-- Dates -->
          <h2 class="caps_header1">Dates:
          <div class="info" title="Search contributions by the date range in which they were made"></div>
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


<!-- California SOS footer page -->
    <footer id="footer" class="grid-100 grid-parent" lang="en-US">
	<!-- footer navigation -->
	<div class="clearfix">
    <div class="grid-20">
    <h2 class="togLnk">Agency</h2>
    <div class="togCont clearfix">
    <ul class="btns">
    <li><a href="//www.sos.ca.gov/">Secretary of State Main Website</a></li>
    <li><a href="//www.sos.ca.gov/elections/">Elections &amp; Voter Information</a></li>
	<li><a href="//www.sos.ca.gov/maintenance-schedule.htm">Site Maintenance Schedule</a></li>
    </ul>
    </div>
    </div>
    <div class="grid-25">
    <h2 class="togLnk">Resources</h2>
    <div class="togCont clearfix">
    <ul class="btns">
	<li><a href="/help/">Website Help</a></li>
	<li><a href="/privacy/">Privacy</a></li>
	<li><a href="/accessibility/">Accessibility</a></li>
    </ul>
    </div>
    </div>
    <div class="grid-25">
    <h2 class="togLnk">Voter Assistance Hotline</h2>
    <div class="togCont clearfix">
    <ul class="btns">
    <li><a href="tel:18003458683">(800) 345-VOTE (8683)</a></li>
    </ul>
    </div>
    </div>
    <div class="grid-20">
    <h2 class="togLnk">Connect</h2>
    <div class="togCont clearfix">
     <ul class="btns">
    <li><a href="//www.sos.ca.gov/multimedia/"><img src="//5d0cf7df673ab6a14d51-01fbb794ac405944f26ec8749fe8fe7b.r7.cf1.rackcdn.com/img/rss-32.png" width="32" height="32" alt="Subscribe to Secretary of State RSS Feeds" /> RSS</a></li>
    <li><a href="https://www.facebook.com/CaliforniaSOS/"><img src="//5d0cf7df673ab6a14d51-01fbb794ac405944f26ec8749fe8fe7b.r7.cf1.rackcdn.com/img/facebook-32.png" width="32" height="32" alt="Like the Secretary of State Facebook page" /> Facebook</a></li>
    <li><a href="https://twitter.com/sosnews/"><img src="//5d0cf7df673ab6a14d51-01fbb794ac405944f26ec8749fe8fe7b.r7.cf1.rackcdn.com/img/twitter-32.png" width="32" height="32" alt="Follow Secretary of State on Twitter" /> Twitter</a></li>
    </ul>    </div>
    </div>
	<!-- // footer navigation -->
    </div>
	<!-- copyright -->
    <div id="cpyrght" role="contentinfo">
    <ul>
    <li>Copyright &#169; 2013 California Secretary of State</li>
    <li>1500 11th Street, Sacramento, California 95814</li>
    <li class="listInlineLastChild">(916) 653-6814</li>
    </ul>
    <ul>
    <li><a href="http://registertovote.ca.gov/#top">Back to Top</a></li>
    <li><a href="http://registertovote.ca.gov/">Homepage</a></li>
    <li class="listInlineLastChild"><a href="//www.sos.ca.gov/free-doc-readers.htm">Free Document Readers</a></li>
    </ul>
	<!-- // COPYRIGHT -->
    </div>
    </footer>

</body>
</html>
