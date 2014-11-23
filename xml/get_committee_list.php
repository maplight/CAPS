<?php
  require ("../connect.inc");
  
  if ($valid) {
    $user_id = intval ($_POST["user_id"]);
    $user_data = my_fetch_row (my_query ("SELECT * FROM users WHERE user_id = {$user_id}"));

    if (has_access ("13,14,15,16")) {
      if (has_access ("13,15")) {
        $albums = "";
        $result = my_query ("SELECT * FROM albums INNER JOIN users ON (user_id = album_user_id) WHERE user_id = {$user_id} ORDER BY album_name");
        while ($row = my_fetch_row ($result)) {
          $row["password"] = "";
          $albums["list"][] = $row;
        }  	  
      }

      if (has_access ("14,16")) {
        $albums = "";
        $result = my_query ("SELECT * FROM albums INNER JOIN users ON (user_id = album_user_id) WHERE account_id = {$user_data["account_id"]} ORDER BY album_name");
        while ($row = my_fetch_row ($result)) {
          $row["password"] = "";
          $albums["list"][] = $row;
        }  	  
      }

      $user_data = json_decode (api_get_data ("get_data/user_account_data", array ("key" => $key, "user_id" => $user_id)));
      $albums["account_album_count"] = $user_data->account_album_count;
      $albums["maximum_albums_allowed"] = $user_data->account_maximums->max_albums;
    } else {
      $albums = "";
    }

    echo json_encode ($albums);
  } else {
    echo $invalid_key;  	  
  }  	  
?>
