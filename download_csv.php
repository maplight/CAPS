<?php
  require ("connect.php");
  $where = $_GET["w"];
  $where_data = unserialize ($_GET["d"]);
  $sidebar_criteria = unserialize ($_GET["c"]);
  ksort ($sidebar_criteria);
  $filename = "data-" . date ("Y-m-d-H-i") . ".csv";

  $search_join = "";
  if (strpos ($where, "contributions_search_donors") !== false) {$search_join .= "INNER JOIN contributions_search_donors  ON (contributions_search.id = contributions_search_donors.id) ";}
  if (strpos ($where, "smry_candidates") !== false) {$search_join .= "INNER JOIN smry_candidates USING (MapLightCandidateNameID) ";}
  if (strpos ($where, "smry_offices") !== false) {$search_join .= "INNER JOIN smry_offices USING (MapLightCandidateOfficeID) ";}
  if (strpos ($where, "smry_committees") !== false) {$search_join .= "INNER JOIN smry_committees USING (MapLightCommitteeID) ";}
  if (strpos ($where, "smry_propositions") !== false) {$search_join .= "INNER JOIN smry_propositions USING (PropositionID) ";}

  $fields = array ("contributions.TransactionType|Transaction Type",
                   "contributions.ElectionCycle|Cycle",
                   "contributions.Election|Election",
                   "contributions.TransactionDateStart|Start Date",
                   "contributions.TransactionDateEnd|End Date",
                   "contributions.TransactionAmount|Amount",
                   "contributions.RecipientCandidateNameNormalized|Recipient Name",
                   "contributions.RecipientCommitteeNameNormalized|Recipient Committee",
                   "contributions.RecipientCommitteeID|Recipient Committee ID",
                   "contributions.RecipientCandidateOffice|Office",
                   "contributions.RecipientCandidateDistrict|District",
                   "contributions_grouped.ballot_measures|Ballot Measure(s)",
                   "contributions.DonorNameNormalized|Contributor Name",
                   "contributions.DonorCommitteeID|Contributor ID",
                   "contributions.DonorCity|Contributor City",
                   "contributions.DonorState|Contributor State",
                   "contributions.DonorZipCode|Contributor Zip Code",
                   "contributions.DonorEmployerNormalized|Contributor Employer",
                   "contributions.DonorOccupationNormalized|Contributor Occupation",
                   "contributions.DonorOrganization|Contributor Organization",
                   "contributions.CandidateContribution|Candidate Contribution",
                   "contributions.BallotMeasureContribution|Ballot Measure Contribution",
                   "contributions.AlliedCommittee|Allied Committee");
  
  # Build the header and criteria data
  $select_fields = "";
  $header_line = "";
  foreach ($fields as $field) {
    $field_info = explode ("|", $field);
    $select_fields .= $field_info[0] . ",";
    $header_line .= "\"" . $field_info[1] . "\",";
  }
  $select_fields = substr ($select_fields, 0, -1);
  $header_line = substr ($header_line, 0, -1);

  # Build the data
  $data = "";
  $result = $web_db->prepare("SELECT {$select_fields} FROM contributions LEFT JOIN contributions_grouped USING (ContributionID) INNER JOIN contributions_search ON (contributions.id = contributions_search.id) {$search_join} {$where} GROUP BY contributions_grouped.ContributionID");
  $result->execute($where_data);
  foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $data_line = "";
    foreach ($row as $value) {
      $data_line .= "\"" . trim (str_replace ("\"", "\"\"", $value)) . "\",";
    }
    $data .= substr ($data_line, 0, -1) . "\n";
  }
  $data = str_replace ("\r", "", $data);

  $criteria_data = "\n\"Search criteria used to create this report:\"\n";
  foreach ($sidebar_criteria as $criteria=>$selection) {
    $criteria_data .= "\"" . substr (str_replace ("_", " ", $criteria), 2) . ": " . $selection . "\"\n";
  }

  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename={$filename}");
  header("Pragma: no-cache");
  header("Expires: 0");
  echo "$header_line\n$data\n$criteria_data";
?>