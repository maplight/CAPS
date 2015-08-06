<?php
  $web_login = "caps"; $web_pwd = "##_caps_password_##";
  $script_login = "caps_script"; $script_pwd = "##_caps_script_password_##";
  $error_email = "##_your_error_email_address_##";
  $hostname = "localhost";

  $web_db = new PDO("mysql:host={$hostname};dbname=ca_search;charset=utf8", $web_login, $web_pwd);
  $web_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $web_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

