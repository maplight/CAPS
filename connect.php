<?php
  $my_conn = new mysqli ("localhost", "caps", "caps-dev14", "CAPS");


#===============================================================================================
# process query
  function my_query ($query) {
    global $my_conn;
    $ret = $my_conn->query ($query);
    return $ret;
  }  


#===============================================================================================
# process data row
  function my_fetch_row ($ret) {
    return $ret->fetch_assoc();  	  
  }
?>