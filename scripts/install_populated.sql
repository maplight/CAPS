DROP TABLE IF EXISTS `cal_access_sessions`;
CREATE TABLE `cal_access_sessions` (
  `session` smallint(6) NOT NULL,
  PRIMARY KEY (`session`)
);

INSERT INTO `cal_access_sessions` VALUES (1999),(2001),(2003),(2005),(2007),(2009),(2011),(2013);

DROP TABLE IF EXISTS `names_to_remove`;
CREATE TABLE `names_to_remove` (
  `removal_word` varchar(100) NOT NULL,
  PRIMARY KEY (`removal_word`(10))
);

INSERT INTO `names_to_remove` VALUES ('-'),('.'),('ACT'),('ADDRESS'),('AIRPORT'),('ASSEMBLY'),('ASSENBLY'),('AUTOMOBILE'),('BBQ'),('CALIFORNIA'),('CAMPAIGN'),('CITY'),('COMMITTEE'),('COUNCIL'),('COUNTY'),('ELECT'),('FOR'),('FRIENDS'),('FUND'),('GOV'),('GOVERNOR'),('INITIATIVE'),('MEASURE'),('MEMBERSHIP'),('N/A'),('NA'),('NO'),('OF'),('PAC'),('POLITICAL'),('PROPOSITION'),('RE-ELECT'),('SCHOOL'),('SENATE'),('STATE'),('THE'),('UNION'),('YES');
