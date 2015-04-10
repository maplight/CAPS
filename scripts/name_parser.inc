<?php
function parse_name($name)
{
  $name = clean_names($name);
  $name = split_names($name);
  return $name;
}


function eexplode($separator, $string)
{
  $in_array = explode($separator, $string);
  $out_array = array();
  foreach ($in_array as $val) {if (! empty($val)) {$out_array[] = $val;}}
  return $out_array;
}


function clean_names ($name)
{
  $full_name = str_replace("''", "\"", $name);

  if (strpos($full_name, "(") !== false) {
    $s = strpos($full_name, "(");
    $e = strpos($full_name, ")", $s + 1);
    $full_name = substr($full_name, 0, $s) . str_replace(array(" ", ","), "_", substr($full_name, $s, $e - $s + 1)) . substr($full_name, $e + 1);
  }

  if (strpos($full_name, "\"") !== false) {
    $s = strpos($full_name, "\"");
    $e = strpos($full_name, "\"", $s + 1);
    $full_name = substr($full_name, 0, $s) . str_replace(array(" ", ","), "_", substr($full_name, $s, $e - $s + 1)) . substr($full_name, $e + 1);
  }

  $comma_split = eexplode(",", str_ireplace(" and ", " & ", $full_name));
  $cleaned_name = "";
  for ($i = count($comma_split) - 1; $i >= 0; $i--) {$cleaned_name .= trim($comma_split[$i]) . " ";}
  $name = trim($cleaned_name);
  return $name;
}


function split_names($name)
{
  global $script_db;

  $name_split = eexplode(" ", $name);
  $prefix = ""; $first_name = ""; $middle_name = ""; $last_name = ""; $suffix = ""; $nick_name = ""; $error_message = ""; $start_name = -1; $end_name = -1; $unset = array ();

  if (count($name_split) > 2) {
    for ($i = 0; $i < count($name_split); $i++) {
      $tmp_name = trim(strtoupper(str_replace(".", "", $name_split[$i])));

      $result = script_query ("SELECT COUNT(*) AS count FROM nparser_titles WHERE Title = '" . str_replace("'", "", $tmp_name) . "'");
      $row = $result->fetch_assoc();

    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));
$result->fetchAll()

      if ($row["count"] != 0) {
        $prefix = $prefix . " " . $name_split[$i];
        $unset[] = $i;
     } else {
        $result = script_query ("SELECT COUNT(*) AS count FROM nparser_suffixes WHERE Suffix = '" . str_replace("'", "", $tmp_name) . "'");
        $row = $result->fetch_assoc();

    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));
$result->fetchAll()

        if ($row["count"] != 0) {
          $suffix = $name_split[$i] . " " . $suffix;
          $unset[] = $i;
        } else {
          if (substr(trim($name_split[$i]), 0, 1) == "(" || substr(trim($name_split[$i]), 0, 1) == "'" || substr(trim($name_split[$i]), 0, 1) == "\"") {
            $nick_name = $nick_name . " " . $name_split[$i];
            $unset[] = $i;
          }
          $name_split[$i] = str_replace(".", ". ", $name_split[$i]);
        }
      }
    }
    $nick_name = str_replace(array("(", ")", "'", "\""), "", str_replace("_", " ", $nick_name));
  }

  foreach ($unset AS $unset_idx) {unset ($name_split[$unset_idx]);}
  $name_split = eexplode(" ", implode(" ", $name_split));

  if (count($name_split) < 2) {
    $error_message = "UNABLE TO PARSE - NOT FULL NAME";
  }  else if (count($name_split) == 2) {
    $first_name = $name_split[0];
    $last_name = $name_split[1];
  } else {
    $end_name = count($name_split) - 1;
    $first_name = $name_split[0];
    $last_name = $name_split[$end_name];
    for ($i = $end_name; $i >= 0; $i--) {
      $tmp_name = trim(strtoupper(str_replace(".", "", $name_split[$i])));

      $result = script_query ("SELECT COUNT(*) AS count FROM nparser_surname_prefixes WHERE SurnamePrefix = '" . str_replace("'", "", $tmp_name) . "'");
      $row = $result->fetch_assoc();

    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));
$result->fetchAll()

      if ($row["count"] != 0) {
        $last_name = $name_split[$i] . " " . $last_name; $end_name = $i - 1;
      } else {
        if ($i == $end_name && $i > 0 && $i < count($name_split) - 1) {$middle_name .= $tmp_name . " ";}
      }
    }
    if ($end_name > 0) {for ($i = 1; $i < $end_name; $i++) {$middle_name .= $name_split[$i] . " ";}}
  }

  $result = script_query ("SELECT Gender, Rank FROM nparser_names WHERE Name = '" . str_replace("'", "", $first_name) . "'");
  $row = $result->fetch_assoc();

    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));
$result->fetchAll()

  $gender = $row["Gender"]; $name_rank = $row["Rank"];
  if ($gender == "") {
    $result = script_query ("SELECT Gender FROM nparser_names WHERE Name = '" . str_replace("'", "", $middle_name) . "'");
    $row = $result->fetch_assoc();

    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));
$result->fetchAll()

    $gender = $row["Gender"];
  }

  $result = script_query ("SELECT Rank FROM nparser_surnames WHERE Surname = REPLACE('" . str_replace("'", "", $last_name) . "', ' ', '')");
  $row = $result->fetch_assoc();

    $result = $script_db->prepare("UPDATE cal_access_committees SET phone = ?, status = ?, last_date = ?, report_start = ?, report_end = ?, total_start = ?, total_end = ?, report_contributions = ?, report_expenditures = ?, total_contributions = ?, total_expenditures = ?, ending_cash = ?, general_information = 1 WHERE session = ? AND filer_id = ?");
    $result->execute(array($phone, $status, $last_date, $report_start, $report_end, $total_start, $total_end, $report_contributions, $report_expenditures, $total_contributions, $total_expenditures, $ending_cash, $session, $filer_id));
$result->fetchAll()

  $surname_rank = $row["Rank"];

  $names = array();
  $names["prefix"] = trim($prefix);
  $names["suffix"] = trim($suffix);
  $names["first_name"] = trim($first_name);
  $names["last_name"] = trim($last_name);
  $names["middle_name"] = trim($middle_name);
  $names["nick_name"] = trim($nick_name);
  $names["gender"] = $gender;
  $names["name_rank"] = $name_rank;
  $names["surname_rank"] = $surname_rank;
  $names["error_message"] = $error_message;

  return $names;
}

