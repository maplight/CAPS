<?php
  $web_login = "caps"; $web_pwd = "caps_dev14";
  $script_login = "caps_script"; $script_pwd = "97_caps_45";
  $error_email = "mike@maplight.org";
  $hostname = "localhost";

  $web_conn = mysqli_init ();
  mysqli_options ($web_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect ($web_conn, $hostname, $web_login, $web_pwd, "ca_search");


#===============================================================================================
# process query
  function my_query ($query) {
    global $web_conn;
    $ret = $web_conn->query (str_replace (array ("\\'", "\\\"", "\\n"), array ("'", "\"", "\n"), mysqli_real_escape_string ($web_conn, $query)));
    return $ret;
  }
?>