<?php
  require ("../connect.php");

  $search_text = $_POST["search_text"];
  $committees = array ();

  $committee_id = intval ($search_text);
  if ($committee_id == 0) {
    $result = my_query ("SELECT RecipientCommitteeNameNormalized, RecipientCommitteeID FROM smry_committees WHERE RecipientCommitteeNameNormalized LIKE '%{$search_text}%' ORDER BY RecipientCommitteeNameNormalized LIMIT 200;");
  } else {
    $result = my_query ("SELECT RecipientCommitteeNameNormalized, RecipientCommitteeID FROM smry_committees WHERE RecipientCommitteeNameNormalized LIKE '%{$search_text}%' OR RecipientCommitteeID = {$committee_id} ORDER BY RecipientCommitteeNameNormalized LIMIT 200;");
  }
  while ($row = $result->fetch_assoc()) {$committees[] = $row;}

  echo json_encode ($committees);
?>
