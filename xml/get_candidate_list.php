<?php
  require ("../connect.php");

  $search_text = $_POST["search_text"];
  $candidates = array ();

  $result = $web_db->prepare("SELECT RecipientCandidateNameNormalized FROM smry_candidates WHERE RecipientCandidateNameNormalized LIKE ? ORDER BY RecipientCandidateNameNormalized LIMIT 50");
  $result->bindValue(1, "%{$search_text}%", PDO::PARAM_STR);
  $result->execute();
  foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {$candidates[] = $row;}

  echo json_encode ($candidates);
?>
