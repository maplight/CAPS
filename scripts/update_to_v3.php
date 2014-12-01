<?php
  require ("../connect.php");
  //ini_set('memory_limit', '2028M');
  $script_conn = mysqli_init ();
  mysqli_options ($script_conn, MYSQLI_OPT_LOCAL_INFILE, true);
  mysqli_real_connect ($script_conn, $hostname, $script_login, $script_pwd, "ca_process");

Echo "Update started ...\n";

echo "Updating processing_tables.sql... \n";
# Create tables used to process the data
process_sql_file("install_processing_tables.sql");

echo "Updating smry_tables.sql... \n";
# Create tables used for fast web searches
process_sql_file("install_smry_tables.sql");

echo "Updating populated.sql... \n";
# Create populated tables (name parse tables and state name table)
process_sql_file("install_populated.sql");

# Process an update - the processes the ftp data
system("php update_data.php");

echo "Update complete....";


#===============================================================================================
# process script query
  function script_query ($query) {
    global $script_conn;
    $ret = $script_conn->query ($query);
    return $ret;
  }


#===============================================================================================
# load an sql file
function process_sql_file($filename)
{
    $sql_contents = file_get_contents($filename);
    $sql_contents = rtrim(rtrim($sql_contents), ";");
    $sql_contents = preg_split("/;(\n|\r)/", $sql_contents);

    foreach ($sql_contents as $query) {
        if($query){
            $result = script_query (trim($query));
            if (!$result)
                echo "Error on import of " . $query . "\n";
        }
    }
}

?>
