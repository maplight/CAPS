<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

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
    # Cal-Access Campaign Power Search Project
    # MapLight
    # Mike Krejci

    # Load required libraries
    require ("connect.php");
    require ("sidebar.php");
    require ("results.php");
?>

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
    <div id="qs_page">
      <form action="advanced.php" method="post">
      <input type="hidden" name="quick_search" value="true">
 
        <div id="qs_title">    
          <h1 class="caps_title3">CAL-ACCESS Campaign Power Search</h1>
          <strong>Search political contributions to candidates, ballot measures, and other committees from 2001 through the present.</strong>
        </div> <!-- #qs_title -->
        <a href="advanced.php" class="qs_link1">Go to Advanced Search</a>
         
        <div id="qs_search">
          <div class="qs_title1">Quick Search</div>
          <hr class="caps_hr1">

          <img src="img/qs_candidate.jpg" width=50 class="qs_img">
          <div id=qs_box>
            <div class="qs_title2">Candidates</div>
              How much has <input type="text" id="search_candidates" name="search_candidates" value="Search candidates" class="qs_text1" onkeyup="filter_candidates_list();"> received?<br>
              <select id="candidate_list" name="candidate_list" class="qs_select1">
<?php
  $result = my_query ("SELECT * FROM smry_cycles ORDER BY ElectionCycle DESC LIMIT 2");
  while ($row = $result->fetch_assoc()) {$last_cycle = $row["ElectionCycle"];}
#  $js_candidates = fill_candidate_names ("", $last_cycle);
  $js_candidates = fill_candidate_names ("", "1999");
?>
              </select><br>
              <input type="submit" name="qs_button" value="Search Candidates" id="qs_btn1">
          </div> <!-- #qs_box -->
          <hr class="caps_hr1">

          <img src="img/qs_ballot.jpg" width=50 class="qs_img">
          <div id=qs_box>
            <div class="qs_title2">Ballot Measures</div>
              How much has been raised for all measures on the
              <select id="propositions_list" name="proposition_list" class="qs_select2">
<?php
  fill_qs_elections ();
?>
              </select> ballot?<br>
              <input type="submit" name="qs_button" value="Search Ballot Measures" id="qs_btn1">
           </div> <!-- #qs_box -->
           <hr class="caps_hr1">

<img src="img/qs_contributor.jpg" width=50 class="qs_img">
          <div id=qs_box>
            <div class="qs_title2">Contributors</div>
            How much has <input type="text" id="contributor" name="contributor" value="company, organization, or person" class="qs_text2"> contributed?<br>
            <input type="submit" name="qs_button" value="Search Contributors" id="qs_btn1">
          </div> <!-- #qs_box -->
          <hr class="caps_hr1">

          <img src="img/qs_advanced.jpg" width=50 class="qs_img">
          <div id=qs_box>
            <div class="qs_title2"><a href="advanced.php">Advanced Search</a></div><br>
            Search by date, committee name, and more.
          </div> <!-- #qs_box -->
        </div> <!-- #qs_search -->
      </form>

<?php
  # Data for javascript to filter select boxes
  echo "<SCRIPT type=text/javascript>";
  echo "var candidates = [{$js_candidates}\"\"];\n";
  echo "</SCRIPT>";
?>
    <div id="maplight_info">Power Search software by <A HREF=http://www.maplight.org class="maplight_link">MapLight</A></div>

    </div> <!-- #qs_container -->
  </div> <!-- #containter -->
</div> <!-- #wrapper-->

</body>
</html>
