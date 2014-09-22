<?php
  # Cal-Access Power Search Project
  # MapLight
  # Mike Krejci

  # Connect to mysql database
  require ("connect.php");

  # Create the sidebar search form div
  require ("sidebar.php");
  echo "<DIV ID=side_search>";
  build_sidebar_form ();
  echo "</DIV>";
?>