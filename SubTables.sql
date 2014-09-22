CREATE TABLE sub_candidate_names (
  RecipientCandidateNameNormalized VARCHAR(250) NOT NULL,
  KEY RecipientCandidateNameNormalized(RecipientCandidateNameNormalized(10))
);

INSERT INTO sub_candidate_names SELECT DISTINCT RecipientCandidateNameNormalized FROM pwr_ca_contrib_cand_committees WHERE RecipientCandidateNameNormalized <> '' ORDER BY RecipientCandidateNameNormalized;
