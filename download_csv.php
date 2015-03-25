<?php
  require ("connect.php");
  $where = stripslashes ($web_conn->real_escape_string ($_GET["w"]));

  $filename = "data-" . date ("Y-m-d-H-i") . ".csv";

  $search_join = "";
  if (strpos ($where, "smry_candidates") !== false) {$search_join .= "INNER JOIN smry_candidates USING (MapLightCandidateNameID) ";}
  if (strpos ($where, "smry_offices") !== false) {$search_join .= "INNER JOIN smry_offices USING (RecipientCandidateOfficeID) ";}
  if (strpos ($where, "smry_committees") !== false) {$search_join .= "INNER JOIN smry_committees USING (RecipientCommitteeID) ";}
  if (strpos ($where, "smry_propositions") !== false) {$search_join .= "INNER JOIN smry_propositions USING (PropositionID) ";}

  $fields = array ("contributions.TransactionType|Transaction Type",
                   "contributions.ElectionCycle|Cyle",
                   "contributions.Election|Election",
                   "contributions.TransactionDateStart|Start Date",
                   "contributions.TransactionDateEnd|End Date",
                   "contributions.TransactionAmount|Amount",
                   "contributions.RecipientCommitteeNameNormalized|Recipient Committee",
                   "contributions.RecipientCandidateNameNormalized|Recipient Name",
                   "contributions.RecipientCandidateOffice|Office",
                   "contributions.RecipientCandidateDistrict|District",
                   "contributions_grouped.ballot_measures|Ballot Measure(s)",
                   "contributions.DonorNameNormalized|Contributor Name",
                   "contributions.DonorCity|Contributor City",
                   "contributions.DonorState|Contributor State",
                   "contributions.DonorZipCode|Contributor ZipCode",
                   "contributions.DonorEmployerNormalized|Contributor Employer",
                   "contributions.DonorOccupationNormalized|Contributor Occupation",
                   "contributions.DonorOrganization|Contributor Organization");

  $select_fields = "";
  $header_line = "";
  foreach ($fields as $field) {
    $field_info = explode ("|", $field);
    $select_fields .= $field_info[0] . ",";
    $header_line .= "\"" . $field_info[1] . "\",";
  }
  $select_fields = substr ($select_fields, 0, -1);
  $header_line = substr ($header_line, 0, -1);

  $data = "";
  $query = "SELECT {$select_fields} FROM contributions LEFT JOIN contributions_grouped USING (ContributionID) INNER JOIN contributions_search ON (contributions.id = contributions_search.id) {$search_join} {$where} GROUP BY contributions_grouped.ContributionID";
  $result = my_query ($query);
  while ($row = $result->fetch_row()) {
    $data_line = "";
    foreach ($row as $value) {
      $data_line .= "\"" . trim (str_replace ("\"", "\"\"", $value)) . "\",";
    }
    $data .= substr ($data_line, 0, -1) . "\n";
  }
  $data = str_replace ("\r", "", $data);

  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename={$filename}");
  header("Pragma: no-cache");
  header("Expires: 0");
  echo "$header_line\n$data";
?>