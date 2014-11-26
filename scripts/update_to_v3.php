<?php
//ini_set('auto_detect_line_endings', true);
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
