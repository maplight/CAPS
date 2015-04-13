<?php
require("cal_access_scraper.inc");

$query = "ALTER TABLE cal_access_sessions 
            ADD COLUMN run_days VARCHAR(100) NOT NULL AFTER session,
            ADD COLUMN last_ran DATETIME NOT NULL AFTER run_days;";
$script_db->query($query);

$script_db->query("INSERT INTO cal_access_sessions (session, run_days) VALUES ('2015', '');");
$script_db->query("UPDATE cal_access_sessions SET run_days = '' WHERE session = '2013';");
$script_db->query("UPDATE cal_access_sessions SET run_days = 'S' WHERE session = '2011';");
$script_db->query("UPDATE cal_access_sessions SET run_days = 'S' WHERE session = '2009';");
$script_db->query("UPDATE cal_access_sessions SET run_days = '1-S' WHERE session = '2007';");
$script_db->query("UPDATE cal_access_sessions SET run_days = '1-S' WHERE session = '2005';");
$script_db->query("UPDATE cal_access_sessions SET run_days = '2-S' WHERE session = '2003';");
$script_db->query("UPDATE cal_access_sessions SET run_days = '2-S' WHERE session = '2001';");
$script_db->query("UPDATE cal_access_sessions SET run_days = '3-S' WHERE session = '1999';");

$query = "DELETE cal_access_elections.* FROM cal_access_elections LEFT JOIN cal_access_candidates_races USING (election_id) WHERE ISNULL(cal_access_candidates_races.election_id);";
$script_db->query($query);

$query = "ALTER TABLE contributions_full
            CHANGE COLUMN RecipientCommitteeID BIGINT NOT NULL DEFAULT 0";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions
            ADD COLUMN RecipientCommitteeID BIGINT NOT NULL DEFAULT 0 AFTER TransactionAmount,
            ADD COLUMN DonorCommitteeID BIGINT NOT NULL DEFAULT 0 AFTER DonorOrganization";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_candidates 
            CHANGE COLUMN RecipientCandidateNameID MapLightCandidateNameID BIGINT NOT NULL AUTO_INCREMENT";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_committees 
            CHANGE COLUMN RecipientCommitteeID MapLightCommitteeID BIGINT NOT NULL AUTO_INCREMENT";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_offices 
            CHANGE COLUMN RecipientCandidateOfficeID MapLightCandidateOfficeID BIGINT NOT NULL AUTO_INCREMENT";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions_search 
            CHANGE COLUMN RecipientCandidateNameID MapLightCandidateNameID BIGINT NOT NULL,
            CHANGE COLUMN RecipientCandidateOfficeID MapLightCandidateOfficeID BIGINT NOT NULL,
            CHANGE COLUMN RecipientCommitteeID MapLightCommitteeID BIGINT NOT NULL";
$script_db->query($query);

$query = "ALTER TABLE contributions_full
            ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions
            ADD COLUMN IsEmployee ENUM('Y','N') DEFAULT 'N' AFTER Unitemized";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions_search 
            DROP COLUMN DonorWords,
            DROP COLUMN DonorState,
            CHANGE COLUMN AlliedCommittee AlliedCommittee ENUM('Y','N') NOT NULL,
            CHANGE COLUMN CandidateContribution CandidateContribution ENUM('Y','N') NOT NULL,
            CHANGE COLUMN BallotMeasureContribution BallotMeasureContribution ENUM('Y','N') NOT NULL,
            ADD COLUMN IsEmployee ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER BallotMeasureContribution,
            DROP INDEX DonorWords,
            DROP INDEX DonorState,
            ADD INDEX IsEmployee(IsEmployee)";
$script_db->query($query);

$query = "CREATE TABLE ca_search.contributions_search_donors (
            id BIGINT NOT NULL PRIMARY KEY,
            DonorState CHAR(2) NOT NULL,
            DonorCommitteeID BIGINT NOT NULL,
            DonorWords VARCHAR(250) NOT NULL,
            KEY DonorState(DonorState),
            KEY DonorCommitteeID(DonorCommitteeID),
            FULLTEXT DonorWords(DonorWords)
          ) ENGINE=MyISAM";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_committees 
            ADD COLUMN RecipientCommitteeID BIGINT NOT NULL AFTER RecipientCommitteeNameNormalized,
            ADD INDEX RecipientCommitteeID(RecipientCommitteeID)";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions 
            DROP INDEX DonorNameNormalized,
            ADD INDEX DonorNameNormalized(DonorNameNormalized(20)),
            DROP INDEX DonorEmployerNormalized,
            ADD INDEX DonorEmployerNormalized(DonorEmployerNormalized(20)),
            DROP INDEX RecipientCandidateOffice,
            ADD INDEX RecipientCandidateOffice(RecipientCandidateOffice(20)),
            DROP INDEX RecipientCandidateDistrict,
            ADD INDEX RecipientCandidateDistrict(RecipientCandidateDistrict(20)),
            DROP INDEX Target,
            ADD INDEX Target(Target(20)),
            DROP INDEX RecipientCommitteeNameNormalized,
            ADD INDEX RecipientCommitteeNameNormalized(RecipientCommitteeNameNormalized(20)),
            DROP INDEX RecipientCandidateNameNormalized,
            ADD INDEX RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(20)),
            DROP INDEX DonorOccupationNormalized,
            ADD INDEX DonorOccupationNormalized(DonorOccupationNormalized(20)),
            ADD INDEX CandidateContribution(CandidateContribution),
            ADD INDEX ElectionCycle(ElectionCycle),
            ADD INDEX MultiIndex1(Election, Target(20)),
            ADD INDEX MultiIndex2(RecipientCommitteeID, RecipientCommitteeNameNormalized(20))";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions_grouped
            DROP INDEX ballot_measures,
            ADD INDEX ballot_measures(ballot_measures(20))";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_propositions 
            ADD INDEX MultiIndex1(Election, Target(20))";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_committees
            ADD INDEX MultiIndex1(RecipientCommitteeID, RecipientCommitteeNameNormalized(20))";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions_search_donors 
            CHANGE COLUMN DonorCommitteeID DonorCommitteeID BIGINT NULL";
$script_db->query($query);

$query = "UPDATE ca_search.contributions_search_donors SET DonorCommitteeID = NULL WHERE DonorCommitteeID = 0";
$script_db->query($query);

$query = "ALTER TABLE ca_search.smry_committees 
            CHANGE COLUMN RecipientCommitteeID RecipientCommitteeID BIGINT NULL";
$script_db->query($query);

$query = "UPDATE ca_search.smry_committees SET RecipientCommitteeID = NULL WHERE RecipientCommitteeID = 0";
$script_db->query($query);

$query = "ALTER TABLE ca_search.contributions 
            CHANGE COLUMN ElectionCycle ElectionCycle SMALLINT NOT NULL,
            CHANGE COLUMN RecipientCommitteeID RecipientCommitteeID BIGINT NULL,
            CHANGE COLUMN DonorCommitteeID DonorCommitteeID BIGINT NULL,
            CHANGE COLUMN Unitemized Unitemized ENUM('Y','N') NOT NULL DEFAULT 'N',
            CHANGE COLUMN IsEmployee IsEmployee ENUM('Y','N') NOT NULL DEFAULT 'N',
            CHANGE COLUMN AlliedCommittee AlliedCommittee ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'formerly Flag',
            CHANGE COLUMN CandidateContribution CandidateContribution ENUM('Y','N') NOT NULL DEFAULT 'N',
            CHANGE COLUMN BallotMeasureContribution BallotMeasureContribution ENUM('Y','N') NOT NULL DEFAULT 'N',
            CHANGE COLUMN ContributionID ContributionID BIGINT NOT NULL";
$script_db->query($query);

$query = "UPDATE ca_search.contributions SET DonorCommitteeID = NULL WHERE DonorCommitteeID = 0";
$script_db->query($query);

$query = "UPDATE ca_search.contributions SET RecipientCommitteeID = NULL WHERE RecipientCommitteeID = 0";
$script_db->query($query);
