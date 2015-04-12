<?php
  require("../connect.php");

  $search_text = $_POST["search_text"];
  $committees = array();

  $committee_id = intval($search_text);
  if ($committee_id == 0) {
    $result = $web_db->prepare("SELECT RecipientCommitteeNameNormalized, RecipientCommitteeID FROM smry_committees WHERE RecipientCommitteeNameNormalized LIKE ? ORDER BY RecipientCommitteeNameNormalized LIMIT 200");
    $result->bindValue(1, "%{$search_text}%", PDO::PARAM_STR);
  } else {
    $result = $web_db->prepare("SELECT RecipientCommitteeNameNormalized, RecipientCommitteeID FROM smry_committees WHERE RecipientCommitteeNameNormalized LIKE ? OR RecipientCommitteeID = ? ORDER BY RecipientCommitteeNameNormalized LIMIT 200");
    $result->bindValue(1, "%{$search_text}%", PDO::PARAM_STR);
    $result->bindValue(2, $committee_id, PDO::PARAM_INT);
  }
  $result->execute();
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {$committees[] = $row;}

  echo json_encode($committees);

