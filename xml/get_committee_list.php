<?php
  require ("../connect.php");

  $search_text = sql_clean_str ($_POST["search_text"]);
  $committees = array ();

  $result = my_query ("SELECT RecipientCommitteeNameNormalized FROM smry_committees WHERE RecipientCommitteeNameNormalized LIKE '%{$search_text}%' ORDER BY RecipientCommitteeNameNormalized LIMIT 200;");
  while ($row = $result->fetch_assoc()) {$committees[] = $row;}

  echo json_encode ($committees);
?>
