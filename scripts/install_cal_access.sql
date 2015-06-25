DROP TABLE IF EXISTS cal_access_candidates;~
CREATE TABLE cal_access_candidates (
  session smallint(6) NOT NULL,
  name varchar(100) NOT NULL,
  id bigint(20) NOT NULL,
  link varchar(250) NOT NULL,
  party varchar(100) NOT NULL,
  KEY session (session),
  KEY name (name(10)),
  KEY id (id),
  KEY party (party(10))
);~

DROP TABLE IF EXISTS cal_access_candidates_committees;~
CREATE TABLE cal_access_candidates_committees (
  session smallint(6) NOT NULL,
  id bigint(20) NOT NULL,
  filer_id bigint(20) NOT NULL,
  KEY session (session),
  KEY id (id),
  KEY filer_id (id),
  KEY multi_01 (filer_id,session)
);~

DROP TABLE IF EXISTS cal_access_candidates_names;~
CREATE TABLE cal_access_candidates_names (
  session smallint(6) NOT NULL,
  id bigint(20) NOT NULL,
  name varchar(100) NOT NULL,
  KEY session (session),
  KEY id (id),
  KEY name (name(10))
);~

DROP TABLE IF EXISTS cal_access_candidates_races;~
CREATE TABLE cal_access_candidates_races (
  session smallint(6) NOT NULL,
  id bigint(20) NOT NULL,
  election_id bigint(20) NOT NULL,
  office char(3) NOT NULL,
  result varchar(10) NOT NULL,
  parsed varchar(200) NOT NULL,
  KEY session (session),
  KEY id (id),
  KEY election_id (election_id),
  KEY office (office),
  KEY result (result)
);~

DROP TABLE IF EXISTS cal_access_candidates_spending_limits;~
CREATE TABLE cal_access_candidates_spending_limits (
  session smallint(6) NOT NULL,
  id bigint(20) NOT NULL,
  election varchar(100) NOT NULL,
  accepted varchar(10) NOT NULL,
  KEY session (session),
  KEY id (id),
  KEY election (election(10))
);~

DROP TABLE IF EXISTS cal_access_committees;~
CREATE TABLE cal_access_committees (
  session smallint(6) NOT NULL,
  name varchar(250) NOT NULL,
  link varchar(250) NOT NULL,
  type varchar(100) NOT NULL,
  filer_id bigint(20) NOT NULL,
  phone varchar(20) NOT NULL,
  status varchar(50) NOT NULL,
  last_date date NOT NULL,
  report_start date NOT NULL,
  report_end date NOT NULL,
  report_contributions double NOT NULL,
  report_expenditures double NOT NULL,
  total_start date NOT NULL,
  total_end date NOT NULL,
  total_contributions double NOT NULL,
  total_expenditures double NOT NULL,
  ending_cash double NOT NULL,
  general_information tinyint(4) NOT NULL,
  contributions_received tinyint(4) NOT NULL,
  contributions_made tinyint(4) NOT NULL,
  expenditures_made tinyint(4) NOT NULL,
  late_contributions_received tinyint(4) NOT NULL,
  late_contributions_made tinyint(4) NOT NULL,
  late_expenditures_made tinyint(4) NOT NULL,
  KEY session (session),
  KEY name (name(10)),
  KEY filer_id (filer_id),
  KEY general_information (general_information),
  KEY contributions_received (contributions_received),
  KEY contributions_made (contributions_made),
  KEY late_contributions_received (late_contributions_received),
  KEY late_contributions_made (late_contributions_made),
  KEY expenditures_made (expenditures_made),
  KEY late_expenditures_made (late_expenditures_made),
  KEY status (status(10)),
  KEY last_date (last_date)
);~

DROP TABLE IF EXISTS cal_access_committees_names;~
CREATE TABLE cal_access_committees_names (
  session smallint(6) NOT NULL,
  filer_id bigint(20) NOT NULL,
  name varchar(250) NOT NULL,
  KEY session (session),
  KEY name (name(10)),
  KEY filer_id (filer_id)
);~

DROP TABLE IF EXISTS cal_access_elections;~
CREATE TABLE cal_access_elections (
  election_id bigint(20) NOT NULL AUTO_INCREMENT,
  date date NOT NULL,
  description varchar(250) NOT NULL,
  special tinyint(4) NOT NULL,
  election_type char(1) NOT NULL,
  office varchar(3) NOT NULL,
  PRIMARY KEY (election_id),
  KEY date (date),
  KEY special (special),
  KEY election_type (election_type),
  KEY office (office)
);~

DROP TABLE IF EXISTS cal_access_propositions;~
CREATE TABLE cal_access_propositions (
  session smallint(6) NOT NULL,
  proposition_id bigint(20) NOT NULL,
  election_date date NOT NULL,
  name varchar(250) NOT NULL,
  link varchar(250) NOT NULL,
  KEY session (session),
  KEY proposition_id (proposition_id),
  KEY election_date (election_date),
  KEY name (name(10))
);~

DROP TABLE IF EXISTS cal_access_propositions_committees;~
CREATE TABLE cal_access_propositions_committees (
  session smallint(6) NOT NULL,
  proposition_id bigint(20) NOT NULL,
  filer_id bigint(20) NOT NULL,
  position varchar(100) NOT NULL,
  KEY session (session),
  KEY proposition_id (proposition_id),
  KEY filer_id (filer_id)
);~
