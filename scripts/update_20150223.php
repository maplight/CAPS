<?php
ALTER TABLE `ca_process`.`cal_access_sessions` 
ADD COLUMN `run_cycle` ENUM('D','W','M') NULL AFTER `session`,
ADD COLUMN `last_ran` TIMESTAMP NULL AFTER `run_cycle`;


INSERT INTO `ca_process`.`cal_access_sessions` (`session`, `run_cycle`) VALUES ('2015', 'D');
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='D' WHERE `session`='2013';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='W' WHERE `session`='2011';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='M' WHERE `session`='1999';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='M' WHERE `session`='2001';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='M' WHERE `session`='2003';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='M' WHERE `session`='2005';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='M' WHERE `session`='2007';
UPDATE `ca_process`.`cal_access_sessions` SET `run_cycle`='W' WHERE `session`='2009';


DELETE FROM ca_process.cal_access_elections;


?>