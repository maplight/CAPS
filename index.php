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
?>

<!-- default header, replace with sites header -->
<div id="caps_header">
  <div><img id="maplight_logo" src="img/MapLight_Demo.jpg" style="margin-left:10px; margin-bottom:6px;"></div>
  <ul id="utl">
        <li><b>CAL-ACCESS Campaign Power Search</b></li>
        <li><a href="index.php">Quick Search</a></li>
        <li><a href="advanced.php">Advanced Search</a></li>
  </ul>
  <div style="border:2px solid #FF0000; background:#FFCCCC; margin:2px; color:red; text-align:center;"><b>NOTE: This search is in BETA. Please do not cite.</b></div>
</div> <!-- end caps_header -->


<div id="caps_wrapper">
  <div id="caps_container">
    <div id="qs_page">

      <form action="advanced.php" method="post">
      <input type="hidden" name="quick_search" value="true">
 
        <div id="qs_title"> 
          <h1 class="font_large_header">CAL-ACCESS Campaign Power Search</h1>
          <strong><p id="caps_description">Search political contributions to candidates, ballot measures, and other committees from 2001 through the present.</p></strong>
        </div> <!-- end qs_title -->

        <a id="advanced_button" href="advanced.php" class="right font_btn btn_border qs_link1">Go to Advanced Search</a>
         
        <div id="qs_search">
          <div class="font_title">Quick Search</div>
          <hr class="caps_hr1">

          <img src="img/qs_candidate.jpg" width="50" class="caps_img" alt="Candidate Option Icon">
          <div id="qs_box">
            <div class="font_title qs_option_title">Candidates</div>
              <div class="left">How much has&nbsp;</div> 
              <div class="left">
                <input type="hidden" id="match_candidate" name="match_candidate" value="no">
                <input type="text" id="search_candidates" name="search_candidates" value="Search candidates" onkeyup="fill_candidate_list(event);" onFocus="if(this.value == 'Search candidates') {this.value = '';} fill_candidate_list(event);" onBlur="if(this.value == '') {this.value = 'Search candidates';}" class="font_input input_border qs_text1" alt="Search Candidates"><br>
                <div id="candidates" class="input_border caps_search_dropbox"></div>
              </div>
              <div class="left">&nbsp;received?</div><br>
              <input type="submit" id="qs_btn" name="qs_button" value="Search Candidates">
          </div> <!-- end qs_box (Candidates) -->
          <hr class="caps_hr1">

          <img src="img/qs_ballot.jpg" width="50" class="caps_img" alt="Ballot Measures Option Icon">
          <div id="qs_box">
            <div class="font_title qs_option_title">Ballot Measures</div>
              How much has been raised for all measures on the <select id="propositions_list" name="proposition_list" class="font_input input_border qs_select" alt="Select Ballot Measure Election">
<?php
  # Fill election dates dropdown
  fill_qs_elections ();
?>
              </select> ballot?<br>
              <input type="submit" id="qs_btn" name="qs_button" value="Search Ballot Measures">
           </div> <!-- end qs_box -->
           <hr class="caps_hr1">

          <img src="img/qs_contributor.jpg" width="50" class="caps_img" alt="Contributors Option Icon">
          <div id="qs_box">
            <div class="font_title qs_option_title">Contributors</div>
            How much has <input type="text" id="contributor" name="contributor" value="company, organization, or person" onFocus="if(this.value == 'company, organization, or person') {this.value = '';}" onBlur="if(this.value == '') {this.value = 'company, organization, or person';}" class="font_input input_border qs_text2" alt="Search Contributors"> contributed?<br>
            <input type="submit" id="qs_btn" name="qs_button" value="Search Contributors">
          </div> <!-- end qs_box -->
          <hr class="caps_hr1">

          <img src="img/qs_advanced.jpg" width="50" class="caps_img" alt="Advanced Search Option Icon">
          <div id="qs_box">
            <div class="font_title qs_option_title"><a href="advanced.php">Advanced Search</a></div>Search by date, committee name, and more.
          </div> <!-- end qs_box -->
        </div> <!-- end qs_search -->
      </form>

    <div id="maplight_info">Power Search software by <A HREF=http://www.maplight.org class="maplight_link">MapLight</A></div>

    </div> <!-- end qs_page -->
  </div> <!-- end caps_containter -->
</div> <!-- end caps_wrapper-->

<!-- Place custom page footer here -->
<footer style="display:none;"></footer>

</body>
</html>
