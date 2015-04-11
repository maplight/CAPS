<?php
require("cal_access_scraper.inc");

#===============================================================================================
# Initilize errot_text and data to process arrays
$data_directory = "files/CalAccess/DATA";
$error_text = "";
$good_tables = array();
$good_files = array();

# get a list of all the ftp_* tables in the database
$db_tables = array();
  
$result = $script_db->query("SHOW TABLES");
foreach ($result->fetchAll() as $row) {if (substr($row[0], 0, 4) == "ftp_") {$db_tables[] = $row[0];}}

# download the file and unzip it
#file_put_contents("files/dbwebexport.zip", fopen("http://campaignfinance.cdn.sos.ca.gov/dbwebexport.zip", "r"));
#exec("unzip files/dbwebexport.zip -d files");

# process each table
foreach ($db_tables as $db_table) {
  # get array of table field names
  $table_fields = array();
  $result = $script_db->query("DESCRIBE $db_table");
  foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {$table_fields[] = $row["Field"];}

  $tab_file = $data_directory . "/" . strtoupper(substr($db_table, 4)) . "_CD.TSV";
  if (file_exists($tab_file)) {
    # get array of file field names
    $df = fopen($tab_file, "r"); $file_header = fgetcsv($df, 0, "\t", "\"");

    if (count($table_fields) == count($file_header)) {
      $fields_match = true;
      for ($i = 0; $i < count($table_fields); $i++) {if (strtolower($table_fields[$i]) != strtolower($file_header[$i])) {$fields_match = false;}}
      if ($fields_match) {
        $good_tables[] = $db_table;
        $good_files[] = $tab_file;
      } else {
        $error_text .= "The fields in $db_table do not match the data file.\n";
        unlink("$tab_file");
      }
    } else {
      $error_text .= "The fields in $db_table do not match the data file.\n";
      unlink("$tab_file");
    }
  } else {
    $error_text .= "No data file was found for $db_table.\n";
  }
}

# check for missed files in the files directory
$files = scandir($data_directory);
foreach ($files as $file) {if (substr($file, -3) == "TSV") {
  $fsize = filesize($data_directory . "/" . $file);
  $db_table = "ftp_" . strtolower(substr ($file, 0, -7));
  if ($fsize > 0 && ! in_array($db_table, $good_tables)) {$error_text .= "A data file ($file) exists, but there is no table for $db_table for it.\n";}
}}

if ($error_text != "") {
  # send error message out if there was a data error
  mail($error_email, "California Access FTP Errors", $error_text, "", "");
} else {
  # Process good data
  for ($i = 0; $i < count($good_tables); $i++) {
    $result = $script_db->query("TRUNCATE TABLE $good_tables[$i]");
    # the PDO functions don't handle load data infile, so these are ran via that mysqli system
    process_mysqli_query("LOAD DATA LOCAL INFILE '" . str_replace('\\', '/', getcwd()) . "/{$good_files[$i]}' INTO TABLE {$good_tables[$i]} FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES");
#    unlink($good_files[$i]);
  }
}

# remove all downloaded files
#exec("rm -rf files/*");

