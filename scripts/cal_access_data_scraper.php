<?php
  require_once ("cal_access_scraper.inc");

  get_elections_list ();

function x() {
  $query = "SELECT session FROM cal_access_sessions ORDER BY session DESC LIMIT 1";
  $result = script_query ($query);
  while ($row = $result->fetch_assoc()) {
    $session = $row["session"];

    get_propositions ($session, 1);
    get_propositions_committees ($session, 1);
    get_candidate_names ($session, 1);
    get_candidate_data ($session, 1);
  }

  # check for missing proposition committes
  $query = "SELECT cal_access_propositions_committees.session, cal_access_propositions_committees.filer_id FROM cal_access_propositions_committees
            LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_propositions_committees.filer_id AND cal_access_committees.session = cal_access_propositions_committees.session)
            WHERE ISNULL(cal_access_committees.filer_id)";
  $result = script_query ($query);
  while ($row = $result->fetch_assoc()) {
    $session = $row["session"];
    $filer_id = $row["filer_id"];
    get_committee_information ($session, $filer_id, 1);
  }
   
  # check for missing candidate committes
  $query = "SELECT cal_access_candidates_committees.session, cal_access_candidates_committees.filer_id FROM cal_access_candidates_committees
            LEFT JOIN cal_access_committees ON (cal_access_committees.filer_id = cal_access_candidates_committees.filer_id AND cal_access_committees.session = cal_access_candidates_committees.session)
            WHERE ISNULL(cal_access_committees.filer_id)";
  $result = script_query ($query);
  while ($row = $result->fetch_assoc()) {
    $session = $row["session"];
    $filer_id = $row["filer_id"];
    get_committee_information ($session, $filer_id, 1);
  }
}
?>
