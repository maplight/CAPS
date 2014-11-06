<?php
  $my_conn = mysqli_init ();
  mysqli_options ($my_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect ($my_conn, "localhost", "caps", "caps-dev14", "ca_search");


#===============================================================================================
# process query
  function my_query ($query) {
    global $my_conn;
    $ret = $my_conn->query ($query);
    return $ret;
  }
?>