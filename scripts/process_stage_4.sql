DROP TABLE IF EXISTS smry_candidates;
RENAME TABLE smry_candidates_temp TO smry_candidates;

DROP TABLE IF EXISTS smry_committees;
RENAME TABLE smry_committees_temp TO smry_committees;

DROP TABLE IF EXISTS smry_cycles;
RENAME TABLE smry_cycles_temp TO smry_cycles;

DROP TABLE IF EXISTS smry_offices;
RENAME TABLE smry_offices_temp TO smry_offices;

DROP TABLE IF EXISTS smry_propositions;
RENAME TABLE smry_propositions_temp TO smry_propositions;

DROP TABLE IF EXISTS contributions_search;
RENAME TABLE contributions_search_temp TO contributions_search; 

DROP TABLE IF EXISTS contributions;
RENAME TABLE contributions_temp TO contributions;
