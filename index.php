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
    # Cal-Access Power Search Project
    # MapLight
    # Mike Krejci

    # Load required libraries
    require ("connect.php");
    require ("sidebar.php");
    require ("results.php");
?>

  <div id="wrapper">
    <div id="container">
      <div id="header" a>


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
        <ul>
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

      </div> <!-- end id=header -->

      <div id="two-columns">
        <form action="" method="post" class="search-form">
        <div id="sidebar">
            <fieldset>
              <legend class="hidden">search-form</legend>
              <h1 class="caps">Advanced Search</h1>
              <input type="submit" value="Search" tabindex="1">
              <div class="holder-form">
                <div class="form-section">
                  <h2 class="caps">Contributions From:</h2>
                  <div class="radio-holder">
                    <div class="row info">
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
  echo "<input type=\"radio\" id=\"all\" name=\"contrib_select\" value=\"all\" tabindex=\"2\" {$checked}>";
?>
                      <label for="all" class="caps">All contributors</label>
                      <a href="#" class="info" tabindex="3">info</a>
                    </div> <!-- end class=row info -->
                    <div class="row">
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"contrib_select\" value=\"search\" tabindex=\"4\" id=\"for1\" {$checked}>";
?>
                      <label for="for1" class="hidden">label</label>
                      <label for="for2" class="hidden">label</label>
<?php
  $text = "Just these contributor";
  if (isset ($_POST["contributor"])) {$text = $_POST["contributor"];}
  echo "<input type=\"text\" id=\"for2\" name=\"contributor\" value=\"{$text}\" tabindex=\"5\" accesskey=\"s\">";
?>
                    </div> <!-- end class=row -->
                  </div> <!-- end class=radio-holder -->
                  <div class="contry-select">
                    <label for="contr" class="caps">Contributor Location</label>
                    <a href="#" class="info" tabindex="6">info</a>
                    <select tabindex="7" id="contr" name="state_list" class="caps">
<?php
  $selected = "";
  if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
  fill_state_list ($selected);
?>
                    </select>
                  </div> <!-- end class=contry-select -->
                </div> <!-- end class=form-section -->

              <!-- section of search form -->
              <div class="form-section middel">
                <h2 class="caps">Contributions To:</h2>
                <div class="frame">
                  <div class="sub-section">
                  <div class="check-part">
<?php
  $checked = "";
  if (isset ($_POST["candidates"])) {if ($_POST["candidates"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"cand\" name=\"candidates\" tabindex=\"8\" {$checked}>";
?>
                    <label for="cand" class="caps">Candidates</label>
                    <a href="#" class="info" tabindex="9">info</a>
                  </div>
                  <div class="holder-b">
                    <div class="sub-row">
<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" id=\"all\" name=\"cand_select\" value=\"all\" tabindex=\"10\" {$checked}>";
?>
                      <label for="all1" class="caps">All candidates</label>
                    </div>
                    <div class="sub-row info">
<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"for3\" name=\"cand_select\" value=\"search\" tabindex=\"11\" {$checked}>";
?>
                      <label for="for3" class="hidden">label</label>
                      <label for="candidates_list" class="hidden">label</label>
                      <label for="search_candidates" class="hidden">label</label>
<?php
  $text = "Search candidates";
  if (isset ($_POST["search_candidates"])) {$text = $_POST["search_candidates"];}
  echo "<input type=\"text\" id=\"search_candidates\" name=\"search_candidates\" value=\"{$text}\" tabindex=\"\" accesskey=\"s\" onkeyup=\"filter_candidates_list();\">";
?>
                      <select tabindex="12" id="candidate_list" name="candidate_list" class="caps">
<?php
  $selected = "";
  if (isset ($_POST["candidate_list"])) {$selected = $_POST["candidate_list"];}
  $js_candidates = fill_candidate_names ($selected);
?>
                      </select>
                      <a href="#" class="info" tabindex="13">info</a>
                    </div>
                    <div class="sub-row info">
<?php
  $checked = "";
  if (isset ($_POST["cand_select"])) {if ($_POST["cand_select"] == "office") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"for5\" name=\"cand_select\" value=\"office\" tabindex=\"14\" {$checked}>";
?>
                      <label for="for5" class="hidden">label</label>
                      <label for="for6" class="hidden">label</label>
                      <select tabindex="15" id="for6" name="office_list" class="caps_nomargin">
<?php
  $selected = "";
  if (isset ($_POST["office_list"])) {$selected = $_POST["office_list"];}
  fill_offices_sought ($selected);
?>
                      </select>
                      <a href="#" class="info" tabindex="16">info</a>
                    </div>
                  </div>
                </div>

                  <!--sub section of search form -->
                  <div class="sub-section">
                  <div class="check-part">
<?php
  $checked = "";
  if (isset ($_POST["propositions"])) {if ($_POST["propositions"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"cand1\" name=\"propositions\" tabindex=\"17\" {$checked}>";
?>
                    <label for="cand1" class="caps">Ballot Measures</label>
                    <a href="#" class="info" tabindex="18">info</a>
                  </div>
                  <div class="holder-b add">
                    <div class="sub-row">
                      <label for="for8" class="hidden">label</label>
<?php
  $text = "Search propositions";
  if (isset ($_POST["search_propositions"])) {$text = $_POST["search_propositions"];}
  echo "<input type=\"text\" id=\"search_propositions\" name=\"search_propositions\" value=\"{$text}\" tabindex=\"\" accesskey=\"s\" onkeyup=\"filter_propositions_list();\">";
?>
                      <select id="propositions_list" name="proposition_list" tabindex="20">
<?php
  $selected = "";
  if (isset ($_POST["proposition_list"])) {$selected = $_POST["proposition_list"];}
  $js_propositions = fill_propositions ($selected);
?>
                      </select>
                    </div>
                    <div class="sub-row">
                      <label for="for9" class="hidden">label</label>
                      <select id="for9" name="position" tabindex="21">
                        <option value="B">Both support &amp; oppose</option>
<?php
  if ($_POST["position"] == "S") {echo "<option value=\"S\" SELECTED>Support</option>";} else {echo "<option value=\"S\">Support</option>";}
  if ($_POST["position"] == "O") {echo "<option value=\"O\" SELECTED>Oppose</option>";} else {echo "<option value=\"O\">Oppose</option>";}
?>
                      </select>
                    </div>
                    <div class="check-b-area">
<?php
  $checked = "";
  if (isset ($_POST["exclude"])) {if ($_POST["exclude"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"alli\" name=\"exclude\" tabindex=\"22\" {$checked}>";
?>
                      <label for="alli" class="caps">Exclude contnbutions between allied committees</label>
                    </div>
                  </div>
                </div>
                  <!-- sub section of search form -->
                  <div class="sub-section last">
                  <div class="check-part">
<?php
  $checked = "";
  if (isset ($_POST["committees"])) {if ($_POST["committees"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"com\" name=\"committees\" {$checked}>";
?>
                    <label for="com" class="caps">Committees</label>
                    <a href="#" class="info" tabindex="23">info</a>
                  </div>
                  <div class="holder-b add">
                    <div class="sub-row">
<?php
  $checked = "";
  if (isset ($_POST["comm_select"])) {if ($_POST["comm_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" id=\"allc\" name=\"comm_select\" value=\"all\" {$checked}>";
?>
                      <label for="allc" class="caps">All committees</label>
                    </div>
                    <div class="sub-row">
<?php
  $checked = "";
  if (isset ($_POST["comm_select"])) {if ($_POST["comm_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" name=\"comm_select\" value=\"search\" id=\"for10\" {$checked}>";
?>
                      <label for="for10" class="hidden">label</label>
                      <label for="for11" class="hidden">label</label>
<?php
  $text = "Just these committees";
  if (isset ($_POST["committee_search"])) {$text = $_POST["committee_search"];}
  echo "<input type=\"text\" value=\"{$text}\" id=\"for11\" name=\"committee_search\">";
?>
                    </div>
                  </div>
                </div>
                </div>
              </div>
                <!-- section of search form -->
                <div class="form-section info">
                  <h2 class="caps">Dates:</h2>
                  <a href="#" class="info">info</a>
                  <div class="radio-row">
<?php
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button 
  echo "<input type=\"radio\" id=\"cyc\" name=\"date_select\" value=\"all\" {$checked}>";
?>
                    <label for="cyc" class="caps">All dates and election cycles</label>
                  </div>
                  <div class="radio-row">
<?php
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "range") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"cyc1\" name=\"date_select\" value=\"range\" {$checked}>"
?>
                    <label for="cyc1" class="caps">Date range</label>
                    <div class="date-range">
<?php
  $text = "mm/dd/yyyy";
  if (isset ($_POST["start_date"])) {$text = $_POST["start_date"];}
  echo "<input type=\"text\" id=\"for12\" name=\"start_date\" value=\"{$text}\" class=\"caps\"> to"; 
?>
                      <label for="for12" class="hidden">label</label>
<?php
  $text = "mm/dd/yyyy";
  if (isset ($_POST["end_date"])) {$text = $_POST["end_date"];}
  echo "<input type=\"text\" value=\"{$text}\" id=\"to\" name=\"end_date\" class=\"caps\">";
?>
                      <label for="to" class="hidden">label</label>
                    </div>
                  </div>
                  <div class="radio-row">
<?php
  $checked = "";
  if (isset ($_POST["date_select"])) {if ($_POST["date_select"] == "cycle") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"cyc2\" name=\"date_select\" value=\"cycle\" {$checked}>";
?>
                    <label for="cyc2" class="caps">Election cycles</label>
                    <div class="year-row">
<?php
  if (isset ($_POST["cycles"])) {$cycles = $_POST["cycles"];} else {$cycles = array ("");}
  fill_election_cycles ($cycles);
?>
                    </div>
                  </div>
                </div>
               </div>
              <input type="submit" value="Search">
            </fieldset>

<?php
  echo "<SCRIPT type=text/javascript>";
  echo "var candidates = [{$js_candidates}\"\"];\n";
  echo "var propositions = [{$js_propositions}\"\"];\n";
  echo "</SCRIPT>";
?>
        </div>


        <!-- contain the main content of the page -->
        <div id="content">
<?php
  build_results_table ();
?>
        </div>
        </form>
      </div>
      <!-- footer of the page -->
      <div id="footer">

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

      </div>
    </div>
    <div class="skip"><a href="#header">back to top</a></div>
  </div>


</body>
</html>
