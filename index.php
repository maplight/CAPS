<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <TITLE>CAPS Project Development</TITLE>
  <LINK rel="stylesheet" type="text/css" href="style.css"/>
</HEAD>
<BODY>

<?php
  # Cal-Access Power Search Project
  # MapLight
  # Mike Krejci

  # Connect to mysql database
  require ("connect.php");
  require ("sidebar.php");

  # Set up the page div and inside wrapper
  echo "<DIV ID=page><DIV ID=inside>";

  # Display any header here <-- user will set this
  echo "<DIV ID=header>";
  echo "<H1>SEARCH FORM HEADER</H1>";
  echo "</DIV>";

  # Create the sidebar search form div
  echo "<DIV ID=sidebar>";
  build_sidebar_form ();
  echo "</DIV>";

  # end of page and inside wrapper
  echo "</DIV><DIV STYLE=\"clear: both;\"></DIV></DIV>";
?>

</BODY></HTML>
