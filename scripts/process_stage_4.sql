OPTIMIZE TABLE ca_search.smry_candidates_temp;
OPTIMIZE TABLE ca_search.smry_committees_temp;
OPTIMIZE TABLE ca_search.smry_cycles_temp;
OPTIMIZE TABLE ca_search.smry_offices_temp;
OPTIMIZE TABLE ca_search.smry_propositions_temp;
OPTIMIZE TABLE ca_search.contributions_search_temp;
OPTIMIZE TABLE ca_search.contributions_search_donors_temp;
OPTIMIZE TABLE ca_search.contributions_temp;
OPTIMIZE TABLE ca_search.contributions_grouped_temp;

DROP TABLE IF EXISTS ca_search.smry_candidates;
RENAME TABLE ca_search.smry_candidates_temp TO ca_search.smry_candidates;

DROP TABLE IF EXISTS ca_search.smry_committees;
RENAME TABLE ca_search.smry_committees_temp TO ca_search.smry_committees;

DROP TABLE IF EXISTS ca_search.smry_cycles;
RENAME TABLE ca_search.smry_cycles_temp TO ca_search.smry_cycles;

DROP TABLE IF EXISTS ca_search.smry_offices;
RENAME TABLE ca_search.smry_offices_temp TO ca_search.smry_offices;

DROP TABLE IF EXISTS ca_search.smry_propositions;
RENAME TABLE ca_search.smry_propositions_temp TO ca_search.smry_propositions;

DROP TABLE IF EXISTS ca_search.contributions_search;
RENAME TABLE ca_search.contributions_search_temp TO ca_search.contributions_search;

DROP TABLE IF EXISTS ca_search.contributions_search_donors;
RENAME TABLE ca_search.contributions_search_donors_temp TO ca_search.contributions_search_donors;

DROP TABLE IF EXISTS ca_search.contributions;
RENAME TABLE ca_search.contributions_temp TO ca_search.contributions;

DROP TABLE IF EXISTS ca_search.contributions_grouped;
RENAME TABLE ca_search.contributions_grouped_temp TO ca_search.contributions_grouped;

