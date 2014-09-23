CREATE TABLE smry_candidates (
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10))
);
INSERT INTO smry_candidates SELECT DISTINCT RecipientCandidateNameNormalized FROM pwr_ca_contrib_cand_committees WHERE RecipientCandidateNameNormalized <> '' ORDER BY RecipientCandidateNameNormalized;


CREATE TABLE smry_states (
  StateName VARCHAR(50) NOT NULL,
  StateCode CHAR(2) NOT NULL,
  IsState SMALLINT NOT NULL,
  KEY StateName(StateName(10)),
  KEY StateCode(StateCode),
  KEY IsState(IsState)
);
INSERT INTO smry_states VALUES ('Alaska','AK',1),('Alabama','AL',1),('Arkansas','AR',1),('Arizona','AZ',1),('California','CA',1),('Colorado','CO',1),('Connecticut','CT',1),('Delaware','DE',1),('Florida','FL',1),('Georgia','GA',1),('Hawaii','HI',1),('Iowa','IA',1),('Idaho','ID',1),('Illinois','IL',1),('Indiana','IN',1),('Kansas','KS',1),('Kentucky','KY',1),('Louisiana','LA',1),('Massachusetts','MA',1),('Maryland','MD',1),('Maine','ME',1),('Michigan','MI',1),('Minnesota','MN',1),('Missouri','MO',1),('Mississippi','MS',1),('Montana','MT',1),('North Carolina','NC',1),('North Dakota','ND',1),('Nebraska','NE',1),('New Hampshire','NH',1),('New Jersey','NJ',1),('New Mexico','NM',1),('Nevada','NV',1),('New York','NY',1),('Ohio','OH',1),('Oklahoma','OK',1),('Oregon','OR',1),('Pennsylvania','PA',1),('Rhode Island','RI',1),('South Carolina','SC',1),('South Dakota','SD',1),('Tennessee','TN',1),('Texas','TX',1),('Utah','UT',1),('Virginia','VA',1),('Vermont','VT',1),('Washington','WA',1),('Wisconsin','WI',1),('West Virginia','WV',1),('Wyoming','WY',1),('Armed Forces Pacific','AP',0),('American Samoa','AS',0),('District of Columbia','DC',0),('Guam','GU',0),('Northern Mariana','MP',0),('Virgin Islands','VI',0),('Puerto Rico','PR',0),('Foreign Countries','ZZ',0),('Federal Level','US',0);


CREATE TABLE smry_offices (
  RecipientCandidateOffice VARCHAR(50) NOT NULL,
  RecipientCandidateDistrict VARCHAR(50) NOT NULL,
  KEY RecipientCandidateOffice(RecipientCandidateOffice(10))
);
INSERT INTO smry_offices SELECT DISTINCT RecipientCandidateOffice, RecipientCandidateDistrict FROM pwr_ca_contrib_cand_committees;

