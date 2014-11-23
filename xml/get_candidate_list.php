<?php
  require ("../connect.php");

  $search_text = sql_clean_str ($_POST["search_text"]);
  $candidates = array ();

  $result = my_query ("SELECT RecipientCandidateNameNormalized FROM smry_candidates WHERE RecipientCandidateNameNormalized LIKE '%{$search_text}%' ORDER BY RecipientCandidateNameNormalized LIMIT 30;");
  while ($row = $result->fetch_assoc()) {$candidates[] = $row;}

  echo json_encode ($candidates);
?>
