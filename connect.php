<?php
  $my_conn = new mysqli ("localhost", "caps", "caps-dev14", "ca_search");
  mysqli_options ($my_conn, MYSQLI_OPT_LOCAL_INFILE, true);


#===============================================================================================
# process query
  function my_query ($query) {
    global $my_conn;
    $ret = $my_conn->query ($query);
    return $ret;
  }  
?>