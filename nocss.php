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
  <li><a href="nocss.php">Quick Search</a></li>
  <li><a href="nocss-advanced.php">Advanced Search</a></li>
  <li><a href="index.php">Graphical Power Search</a></li>
</ul>

<b>NOTE: This search is in BETA. Please do not cite.</b><p>

<h1>CAL-ACCESS Campaign Power Search</h1>
<strong>Search political contributions to candidates, ballot measures, and other committees from 2001 through the present.</strong><p>

<form action="nocss-advanced.php" method="post">
  <input type="hidden" name="quick_search" value="true">

  <h2>Quick Search</h2>
  <hr>

  <b>Candidates</b><br>
  How much has <input type="text" name="search_candidates" value="Search candidates"> received?<br>

  <select name="candidate_list">
<?php
  $result = my_query ("SELECT * FROM smry_cycles ORDER BY ElectionCycle DESC LIMIT 2");
  while ($row = $result->fetch_assoc()) {$last_cycle = $row["ElectionCycle"];}
  fill_candidate_names ("", "1999");
?>
  </select><p>
  <input type="submit" name="qs_button" value="Search Candidates">
  <hr>

  <b>Ballot Measures</b><br>
  How much has been raised for all measures on the <select name="proposition_list">
<?php
  fill_qs_elections ();
?>
  </select> ballot?<p>
  <input type="submit" name="qs_button" value="Search Ballot Measures">
  <hr>

  <b>Contributors</b><br>
  How much has <input type="text" name="contributor" value="company, organization, or person"> contributed?<p>
  <input type="submit" name="qs_button" value="Search Contributors">
  <hr>

  <a href="advanced.php">Advanced Search</a> Search by date, committee name, and more.<p>

</form>

  Power Search software by <A HREF=http://www.maplight.org class="maplight_link">MapLight</A>

</body>
</html>
