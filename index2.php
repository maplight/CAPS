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
  <link rel="stylesheet" type="text/css" href="css/caps2.css" media="all">
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
</div> <!-- caps_header -->

<div id="wrapper">
  <div id="container">
    <div id="columns">
      <form method="post">
        <div id="sidebar">
          <h1 class="caps_title1">Advanced Search</h1>
          <input type="submit" value="Search" class="caps_search_btn1">

          <h2 class="caps_header1">Contributions From:</h2>
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "all") {$checked = "checked";}} else {$checked = "checked";} # This is the default option for this radio button
  echo "<input type=\"radio\" id=\"all_contribs\" name=\"contrib_select\" value=\"all\" class=\"caps_radio1\" {$checked}>";
?>
          <label for="all_contribs" class="caps_label1">All contributors</label>
          <a href="#" class="info"></a>
<?php
  $checked = "";
  if (isset ($_POST["contrib_select"])) {if ($_POST["contrib_select"] == "search") {$checked = "checked";}}  
  echo "<input type=\"radio\" id=\"select_contribs\" name=\"contrib_select\" value=\"search\" class=\"caps_radio1\" {$checked}>";
  $text = "Just these contributor";
  if (isset ($_POST["contributor"])) {$text = $_POST["contributor"];}
  echo "<input type=\"text\" id=\"search_contribs\" name=\"contributor\" value=\"{$text}\" class=\"caps_text1\">";
?>
          <label for="select_location" class="caps_label2">Contributor Location</label>
          <a href="#" class="info"></a>
          <select id="select_location" name="state_list" class="caps_select1">
<?php
  $selected = "";
  if (isset ($_POST["state_list"])) {$selected = $_POST["state_list"];}
  fill_state_list ($selected);
?>
          </select>
          <hr class="caps_hr1">

          <h2 class="caps_header1">Contributions To:</h2>
<?php
  $checked = "";
  if (isset ($_POST["candidates"])) {if ($_POST["candidates"] == "on") {$checked = "checked";}}  
  echo "<input type=\"checkbox\" id=\"contrib_to\" name=\"candidates\" {$checked} class=\"caps_radio1\">";
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
  $js_candidates = fill_candidate_names ($selected);
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


        </div> <!-- #sidebar -->

        <div id="content">

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