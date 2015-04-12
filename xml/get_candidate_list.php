<?php
  require("../connect.php");

  $candidates = array();
  $result = $web_db->prepare("SELECT RecipientCandidateNameNormalized FROM smry_candidates WHERE RecipientCandidateNameNormalized LIKE ? ORDER BY RecipientCandidateNameNormalized LIMIT 50");
  $result->bindValue(1, "%{$_POST["search_text"]}%", PDO::PARAM_STR);
  $result->execute();
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {$candidates[] = $row;}
  echo json_encode($candidates);

