<?php
  $script_db = new PDO("mysql:host={$hostname};dbname=ca_process;charset=utf8", $script_login, $script_pwd);
  $script_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $script_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $script_db->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);

