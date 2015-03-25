<?php
  require_once ("cal_access_scraper.inc");

  $query = "ALTER TABLE cal_access_sessions 
              ADD COLUMN run_days VARCHAR(100) NOT NULL AFTER session,
              ADD COLUMN last_ran DATETIME NOT NULL AFTER run_days;";
  script_query ($query);

  script_query ("INSERT INTO cal_access_sessions (session, run_days) VALUES ('2015', '');");
  script_query ("UPDATE cal_access_sessions SET run_days = '' WHERE session = '2013';");
  script_query ("UPDATE cal_access_sessions SET run_days = 'S' WHERE session = '2011';");
  script_query ("UPDATE cal_access_sessions SET run_days = 'S' WHERE session = '2009';");
  script_query ("UPDATE cal_access_sessions SET run_days = '1-S' WHERE session = '2007';");
  script_query ("UPDATE cal_access_sessions SET run_days = '1-S' WHERE session = '2005';");
  script_query ("UPDATE cal_access_sessions SET run_days = '2-S' WHERE session = '2003';");
  script_query ("UPDATE cal_access_sessions SET run_days = '2-S' WHERE session = '2001';");
  script_query ("UPDATE cal_access_sessions SET run_days = '3-S' WHERE session = '1999';");

  $query = "DELETE cal_access_elections.* FROM cal_access_elections LEFT JOIN cal_access_candidates_races USING (election_id) WHERE ISNULL(cal_access_candidates_races.election_id);";
  script_query ($query);
?>