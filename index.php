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

    # Field selection list
    # Selected field elements:
    #   Database Field Name
    #   Table Header Name
    #   Default as checked ("checked", or "")
    #   Data type ("Integer", "Currency", "Date", "") 
    $select_fields = array (array ("RecipientCandidateNameNormalized", "Recipient Name", "checked", ""),
                            array ("RecipientCommitteeNameNormalized", "Recipient Committee", "checked", ""),
                            array ("Target", "Proposition", "", ""),
                            array ("Election", "Election", "", "Date"),
                            array ("Position", "Position", "", ""),
                            array ("RecipientCandidateOffice", "Office", "checked", ""),
                            array ("DonorNameNormalized", "Contributor Name", "checked", ""),
                            array ("DonorEmployerNormalized", "Contributor Employer", "checked", ""),
                            array ("DonorOccupationNormalized", "Contributor Occupation", "checked", ""),
                            array ("DonorOrganization", "Contributor Organization", "checked", ""),
                            array ("TransactionDate", "Date", "checked", "Date"),
                            array ("TransactionAmount", "Amount", "checked", "Currency"));
  ?>

  <!-- main container of all the page elements -->
  <div id="wrapper">
    <!-- container of the page -->
    <div id="container">

      <!-- header of the page -->
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

      </div>

      <!-- two columns of the page -->
      <div id="two-columns">
        <!-- contain sidebar of the page -->
        <div id="sidebar">
          <!-- search form of the page -->
          <form action="" method="post" class="search-form">
            <fieldset>
              <legend class="hidden">search-form</legend>
              <h1 class="caps">Advanced Search</h1>
              <input type="submit" value="Search" tabindex="1">
              <div class="holder-form">
                <!-- section of search form -->
                <div class="form-section">
                <h2 class="caps">Contributions From:</h2>
                <div class="radio-holder">
                  <div class="row info">
                    <input type="radio" id="all" name="contrib_select" value="all" tabindex="2" checked>
                    <label for="all" class="caps">All contributors</label>
                    <a href="#" class="info" tabindex="3">info</a>
                  </div>
                  <div class="row">
                    <input type="radio" name="contrib_select" value="search" tabindex="4" id="for1">
                    <label for="for1" class="hidden">label</label>
                    <label for="for2" class="hidden">label</label>
                    <input type="text" id="for2" name="contributor" value="Just these contributors" tabindex="5" accesskey="s">
                  </div>
                </div>
                <div class="contry-select">
                  <label for="contr" class="caps">Contributor Location</label>
                  <a href="#" class="info" tabindex="6">info</a>
                  <select tabindex="7" id="contr" name="state_list">
<?php
  fill_state_list ();
?>
                  </select>
                </div>
              </div>
              <!-- section of search form -->
              <div class="form-section middel">
                <h2 class="caps">Contributions To:</h2>
                <div class="frame">
                  <div class="sub-section">
                  <div class="check-part">
                    <input type="checkbox" id="cand" name="candidates" tabindex="8">
                    <label for="cand" class="caps">Candidates</label>
                    <a href="#" class="info" tabindex="9">info</a>
                  </div>
                  <div class="holder-b">
                    <div class="sub-row">
                      <input type="radio" id="all1" name="cand_select" value="all" tabindex="10" checked>
                      <label for="all1" class="caps">All candidates</label>
                    </div>
                    <div class="sub-row info">
                      <input type="radio" id="for3" name="cand_select" value="search" tabindex="11">
                      <label for="for3" class="hidden">label</label>
                      <label for="candidates_list" class="hidden">label</label>
                      <label for="search_candidates" class="hidden">label</label>
                      <input type="text" id="search_candidates" name="search_candidates" value="Search candidates" tabindex="" accesskey="s" onkeyup="filter_candidates_list();">
                      <select tabindex="12" id="candidate_list" name="candidate_list">
<?php
  $js_candidates = fill_candidate_names ();
?>
                      </select>
                      <a href="#" class="info" tabindex="13">info</a>
                    </div>
                    <div class="sub-row info">
                      <input type="radio" name="cand_select" value="office" tabindex="14" id="for5">
                      <label for="for5" class="hidden">label</label>
                      <label for="for6" class="hidden">label</label>
                      <select tabindex="15" id="for6" name="office_list">
<?php
  fill_offices_sought ();
?>
                      </select>
                      <a href="#" class="info" tabindex="16">info</a>
                    </div>
                  </div>
                </div>
                  <!--sub section of search form -->
                  <div class="sub-section">
                  <div class="check-part">
                    <input type="checkbox" id="cand1" name="propositions" tabindex="17">
                    <label for="cand1" class="caps">Ballot Measures</label>
                    <a href="#" class="info" tabindex="18">info</a>
                  </div>
                  <div class="holder-b add">

<!--
                    <div class="sub-row">
                      <label for="for7" class="hidden">label</label>
                      <select tabindex="19" id="for7">
<?php
#  fill_elections ();
?>
                      </select>
                    </div>
-->

                    <div class="sub-row">
                      <label for="for8" class="hidden">label</label>
                      <input type="text" id="search_propositions" name="search_propositions" value="Search propositions" tabindex="" accesskey="s" onkeyup="filter_propositions_list();">
                      <select id="propositions_list" name="proposition_list" tabindex="20">
<?php
  $js_propositions = fill_propositions ();
?>
                      </select>
                    </div>
                    <div class="sub-row">
                      <label for="for9" class="hidden">label</label>
                      <select id="for9" name="position" tabindex="21">
                        <option value="B">Both support &amp; oppose</option>
                        <option value="S">Support</option>
                        <option value="O">Oppose</option>
                      </select>
                    </div>
                    <div class="check-b-area">
                      <input type="checkbox" id="alli" name="exclude" tabindex="22">
                      <label for="alli" class="caps">Exclude contnbutions between allied committees</label>
                    </div>
                  </div>
                </div>
                  <!-- sub section of search form -->
                  <div class="sub-section last">
                  <div class="check-part">
                    <input type="checkbox" id="com" name="committees">
                    <label for="com" class="caps">Committees</label>
                    <a href="#" class="info" tabindex="23">info</a>
                  </div>
                  <div class="holder-b add">
                    <div class="sub-row">
                      <input type="radio" id="allc" name="comm_select" value="all" checked>
                      <label for="allc" class="caps">All committees</label>
                    </div>
                    <div class="sub-row">
                      <input type="radio" name="comm_select" value="search" id="for10">
                      <label for="for10" class="hidden">label</label>
                      <label for="for11" class="hidden">label</label>
                      <input type="text" value="Just these committees" id="for11" name="committee_search">
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
                    <input type="radio" id="cyc" name="date_select" value="all" checked>
                    <label for="cyc" class="caps">All dates and election cycles</label>
                  </div>
                  <div class="radio-row">
                    <input type="radio" id="cyc1" name="date_select" value="range">
                    <label for="cyc1" class="caps">Date range</label>
                    <div class="date-range">
                      <label for="for12" class="hidden">label</label>
                      <input type="text" id="for12" name="start_date" value="mm/dd/yyyy" class="small">
                      <label for="to" class="to-label">to</label>
                      <input type="text" value="mm/dd/yyyy" id="to" name="end_date" class="small">
                    </div>
                  </div>
                  <div class="radio-row">
                    <input type="radio" id="cyc2" name="date_select" value="cycle">
                    <label for="cyc2" class="caps">Election cycles</label>
                    <div class="year-row">
<?php
  fill_election_cycles ();
?>
                    </div>
                  </div>
                </div>

                <div class="form-section">
                  <h2 class="caps">Select Fields:</h2>
                  <div class="frame">
                    <div class="sub-section">
                      <div class="check-part">
<?php 
  $field_count = 0;
  foreach ($select_fields as $field_data) {
    echo "<input type=\"checkbox\" id=\"field{$field_count}\" name=\"fields[]\" value=\"{$field_data[0]}|{$field_data[1]}\" {$field_data[2]}>";
    echo "<label for=\"field{$field_count}\" class=\"caps\">{$field_data[1]}</label><a href=\"#\" class=\"info\" tabindex=\"\">info</a><br>";
    $field_count++;
  }
?>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <input type="submit" value="Search">
            </fieldset>
          </form>
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
