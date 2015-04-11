<?php
  $web_login = "caps"; $web_pwd = "caps_dev14";
  $script_login = "caps_script"; $script_pwd = "97_caps_45";
  $error_email = "mike@maplight.org";
  $hostname = "localhost";

  $web_db = new PDO("mysql:host={$hostname};dbname=ca_search;charset=utf8", $web_login, $web_pwd);
  $web_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $web_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

