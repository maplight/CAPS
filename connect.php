<?php
  $web_login = "caps"; $web_pwd = "caps_dev14";
  $script_login = "caps_script"; $scripts_pwd = "97_caps_45";
  $error_email = "mike@maplight.org";


  $my_conn = mysqli_init ();
  mysqli_options ($my_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect ($my_conn, "localhost", $login, $pwd, "ca_search");


#===============================================================================================
# process query
  function my_query ($query) {
    global $my_conn;
    $ret = $my_conn->query ($query);
    return $ret;
  }
?>