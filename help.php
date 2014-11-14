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
?>

<img src="img/MapLight_Demo.jpg" style="margin-left:10px; margin-bottom:6px;">

<?php
  $tooltip = intval ($_GET["tip"]);

  switch ($tooltip) {
    case 1:
      echo "<h2>Search contributions from all contributors.</h2>";
      break;
  }
?>

</body>
</html>
