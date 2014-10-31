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



DROP TABLE IF EXISTS `mpdb_nparser_titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpdb_nparser_titles` (
  `Title` varchar(15) NOT NULL,
  PRIMARY KEY (`Title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpdb_nparser_titles`
--

LOCK TABLES `mpdb_nparser_titles` WRITE;
/*!40000 ALTER TABLE `mpdb_nparser_titles` DISABLE KEYS */;
INSERT INTO `mpdb_nparser_titles` VALUES ('&'),('ALDERMAN'),('ALDERWOMAN'),('AMBASSADOR'),('ASSESSOR'),('BRIG'),('CANDIDATE'),('CAPT'),('CAPTAIN'),('COL'),('COLONEL'),('COMADORE'),('COMMANDER'),('COMMISSIONER'),('COMMODOR'),('COMMODORE'),('DOC'),('DOCTOR'),('DR'),('GEN'),('GENERAL'),('HON'),('HONORABLE'),('JUDGE'),('LIEUTENANT'),('LT'),('MR'),('MRS'),('MS'),('REP'),('REPRESENTATIVE'),('RET'),('REV'),('REVERAND'),('SEN'),('SENATOR'),('SIR'),('SUPERVISOR'),('THE'),('USAF');
/*!40000 ALTER TABLE `mpdb_nparser_titles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

mpdb_nparser_titles
mpdb_nparser_suffixes
mpdb_nparser_surname_prefixes
mpdb_nparser_names
mpdb_nparser_surnames
