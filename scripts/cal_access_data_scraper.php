<?php
require("cal_access_scraper.inc");

# Get current day of week, day of month
$day_of_month = intval(date("j"));
switch (date("w")) {
  case 0: $day_of_week = "U"; break;
  case 1: $day_of_week = "M"; break;
  case 2: $day_of_week = "T"; break;
  case 3: $day_of_week = "W"; break;
  case 4: $day_of_week = "H"; break;
  case 5: $day_of_week = "F"; break;
  case 6: $day_of_week = "S"; break;
}
$week_of_month = intval(($day_of_month - 1) / 7) + 1; 

# Make sure cal_access_elections has all the known elections
get_elections_list();

# Process data for each session
$result = $script_db->prepare("SELECT * FROM cal_access_sessions ORDER BY session DESC");
$result->execute();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $session = $row["session"];
  $run_days = explode(",", str_replace(" ", "", $row["run_days"]));

  # Check if the session should run.
  $process_session = false;
  foreach ($run_days AS $day) {
    if (strpos($day, "-") === false) {
      if (intval($day) == $day_of_month || $day == $day_of_week || $day == "") {$process_session = true;}
    } else {
      $week = substr($day, 0, strpos($day, "-"));
      $dow = substr($day, strpos($day, "-") + 1, 1);
      if ($week == $week_of_month && $dow = $day_of_week) {$process_session = true;}
    }
  }

echo "$day_of_month - $day_of_week - $week_of_month - {$row["session"]} - {$row["run_days"]}\n";

  # Scrape the session if the schedule is met
  if ($process_session) {
#    get_propositions($session, 1);
#    get_propositions_committees($session, 1);
#    get_candidate_names($session, 1);
#    get_candidate_data($session, 1);
#    $result = $script_db->prepare("UPDATE cal_access_sessions SET last_ran = NOW() WHERE session = ?");
#    $result->execute(array($session));
  }
}

# check for missing proposition committes
$query = "SELECT cal_access_propositions_committees.session, cal_access_propositions_committees.filer_id FROM cal_access_propositions_committees
          LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_propositions_committees.filer_id AND cal_access_committees.session = cal_access_propositions_committees.session)
          WHERE ISNULL(cal_access_committees.filer_id)";
$result = $script_db->prepare($query);
$result->execute();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $session = $row["session"];
  $filer_id = $row["filer_id"];
  get_committee_information($session, $filer_id, 1);
}
   
# check for missing candidate committes
$query = "SELECT cal_access_candidates_committees.session, cal_access_candidates_committees.filer_id FROM cal_access_candidates_committees
          LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_candidates_committees.filer_id AND cal_access_committees.session = cal_access_candidates_committees.session)
          WHERE ISNULL(cal_access_committees.filer_id)";
$result = $script_db->prepare($query);
$result->execute();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $session = $row["session"];
  $filer_id = $row["filer_id"];
  get_committee_information($session, $filer_id, 1);
}

# delete unused committees
$query = "DELETE cal_access_committees.* FROM cal_access_committees
          LEFT JOIN (SELECT cal_access_propositions_committees.session, cal_access_propositions_committees.filer_id FROM cal_access_propositions_committees
                     UNION SELECT cal_access_candidates_committees.session, cal_access_candidates_committees.filer_id FROM cal_access_candidates_committees) AS used_committees 
                    ON (cal_access_committees.filer_id = used_committees.filer_id AND cal_access_committees.session = used_committees.session)
          WHERE ISNULL(cal_access_committees.filer_id);";
$result = $script_db->prepare($query);
$result->execute();

