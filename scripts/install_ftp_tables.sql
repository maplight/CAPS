DROP TABLE IF EXISTS `ftp_acronyms`;
CREATE TABLE `ftp_acronyms` (
  `acronym` varchar(255) DEFAULT NULL,
  `stands_for` varchar(255) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL,
  `a_desc` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_address`;
CREATE TABLE `ftp_address` (
  `adrid` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `st` varchar(255) DEFAULT NULL,
  `zip4` varchar(255) DEFAULT NULL,
  `phon` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_ballot_measures`;
CREATE TABLE `ftp_ballot_measures` (
  `election_date` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `measure_no` varchar(255) DEFAULT NULL,
  `measure_name` varchar(255) DEFAULT NULL,
  `measure_short_name` varchar(255) DEFAULT NULL,
  `jurisdiction` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr2_campaign_disclosure`;
CREATE TABLE `ftp_cvr2_campaign_disclosure` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `mail_city` varchar(255) DEFAULT NULL,
  `mail_st` varchar(255) DEFAULT NULL,
  `mail_zip4` varchar(255) DEFAULT NULL,
  `f460_part` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `enty_naml` varchar(255) DEFAULT NULL,
  `enty_namf` varchar(255) DEFAULT NULL,
  `enty_namt` varchar(255) DEFAULT NULL,
  `enty_nams` varchar(255) DEFAULT NULL,
  `enty_city` varchar(255) DEFAULT NULL,
  `enty_st` varchar(255) DEFAULT NULL,
  `enty_zip4` varchar(255) DEFAULT NULL,
  `enty_phon` varchar(255) DEFAULT NULL,
  `enty_fax` varchar(255) DEFAULT NULL,
  `enty_email` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `control_yn` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr2_lobby_disclosure`;
CREATE TABLE `ftp_cvr2_lobby_disclosure` (
  `amend_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `entity_id` varchar(255) DEFAULT NULL,
  `enty_namf` varchar(255) DEFAULT NULL,
  `enty_naml` varchar(255) DEFAULT NULL,
  `enty_nams` varchar(255) DEFAULT NULL,
  `enty_namt` varchar(255) DEFAULT NULL,
  `enty_title` varchar(255) DEFAULT NULL,
  `filing_id` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr2_registration`;
CREATE TABLE `ftp_cvr2_registration` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `entity_id` varchar(255) DEFAULT NULL,
  `enty_naml` varchar(255) DEFAULT NULL,
  `enty_namf` varchar(255) DEFAULT NULL,
  `enty_namt` varchar(255) DEFAULT NULL,
  `enty_nams` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr2_so`;
CREATE TABLE `ftp_cvr2_so` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `enty_naml` varchar(255) DEFAULT NULL,
  `enty_namf` varchar(255) DEFAULT NULL,
  `enty_namt` varchar(255) DEFAULT NULL,
  `enty_nams` varchar(255) DEFAULT NULL,
  `item_cd` varchar(255) DEFAULT NULL,
  `mail_city` varchar(255) DEFAULT NULL,
  `mail_st` varchar(255) DEFAULT NULL,
  `mail_zip4` varchar(255) DEFAULT NULL,
  `day_phone` varchar(255) DEFAULT NULL,
  `fax_phone` varchar(255) DEFAULT NULL,
  `email_adr` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `ind_group` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `non_pty_cb` varchar(255) DEFAULT NULL,
  `party_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  `year_elect` varchar(255) DEFAULT NULL,
  `pof_title` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr3_verification_info`;
CREATE TABLE `ftp_cvr3_verification_info` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `sig_date` varchar(255) DEFAULT NULL,
  `sig_loc` varchar(255) DEFAULT NULL,
  `sig_naml` varchar(255) DEFAULT NULL,
  `sig_namf` varchar(255) DEFAULT NULL,
  `sig_namt` varchar(255) DEFAULT NULL,
  `sig_nams` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr_campaign_disclosure`;
CREATE TABLE `ftp_cvr_campaign_disclosure` (
  `filing_id` varchar(255) NOT NULL DEFAULT '',
  `amend_id` varchar(255) NOT NULL DEFAULT '',
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `filer_id` bigint(20) NOT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `filer_naml` varchar(255) DEFAULT NULL,
  `filer_namf` varchar(255) DEFAULT NULL,
  `filer_namt` varchar(255) DEFAULT NULL,
  `filer_nams` varchar(255) DEFAULT NULL,
  `report_num` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `stmt_type` varchar(255) DEFAULT NULL,
  `late_rptno` varchar(255) DEFAULT NULL,
  `from_date` varchar(255) DEFAULT NULL,
  `thru_date` varchar(255) DEFAULT NULL,
  `elect_date` varchar(255) DEFAULT NULL,
  `filer_city` varchar(255) DEFAULT NULL,
  `filer_st` varchar(255) DEFAULT NULL,
  `filer_zip4` varchar(255) DEFAULT NULL,
  `filer_phon` varchar(255) DEFAULT NULL,
  `filer_fax` varchar(255) DEFAULT NULL,
  `file_email` varchar(255) DEFAULT NULL,
  `mail_city` varchar(255) DEFAULT NULL,
  `mail_st` varchar(255) DEFAULT NULL,
  `mail_zip4` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `tres_city` varchar(255) DEFAULT NULL,
  `tres_st` varchar(255) DEFAULT NULL,
  `tres_zip4` varchar(255) DEFAULT NULL,
  `tres_phon` varchar(255) DEFAULT NULL,
  `tres_fax` varchar(255) DEFAULT NULL,
  `tres_email` varchar(255) DEFAULT NULL,
  `cmtte_type` varchar(255) DEFAULT NULL,
  `control_yn` varchar(255) DEFAULT NULL,
  `sponsor_yn` varchar(255) DEFAULT NULL,
  `primfrm_yn` varchar(255) DEFAULT NULL,
  `brdbase_yn` varchar(255) DEFAULT NULL,
  `amendexp_1` varchar(255) DEFAULT NULL,
  `amendexp_2` varchar(255) DEFAULT NULL,
  `amendexp_3` varchar(255) DEFAULT NULL,
  `rpt_att_cb` varchar(255) DEFAULT NULL,
  `cmtte_id` varchar(255) DEFAULT NULL,
  `reportname` varchar(255) DEFAULT NULL,
  `rptfromdt` varchar(255) DEFAULT NULL,
  `rptthrudt` varchar(255) DEFAULT NULL,
  `emplbus_cb` varchar(255) DEFAULT NULL,
  `bus_name` varchar(255) DEFAULT NULL,
  `bus_city` varchar(255) DEFAULT NULL,
  `bus_st` varchar(255) DEFAULT NULL,
  `bus_zip4` varchar(255) DEFAULT NULL,
  `bus_inter` varchar(255) DEFAULT NULL,
  `busact_cb` varchar(255) DEFAULT NULL,
  `busactvity` varchar(255) DEFAULT NULL,
  `assoc_cb` varchar(255) DEFAULT NULL,
  `assoc_int` varchar(255) DEFAULT NULL,
  `other_cb` varchar(255) DEFAULT NULL,
  `other_int` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `cand_city` varchar(255) DEFAULT NULL,
  `cand_st` varchar(255) DEFAULT NULL,
  `cand_zip4` varchar(255) DEFAULT NULL,
  `cand_phon` varchar(255) DEFAULT NULL,
  `cand_fax` varchar(255) DEFAULT NULL,
  `cand_email` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  `employer` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `selfemp_cb` varchar(255) DEFAULT NULL,
  `bal_id` varchar(255) DEFAULT NULL,
  `cand_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`filing_id`(10),`amend_id`(10)),
  KEY `filing_id` (`filing_id`(10)),
  KEY `amend_id` (`amend_id`(10)),
  KEY `filer_id` (`filer_id`),
  KEY `form_type` (`form_type`(10)),
  KEY `office_cd` (`office_cd`(10)),
  KEY `entity_cd` (`entity_cd`(10))
);

DROP TABLE IF EXISTS `ftp_cvr_e530`;
CREATE TABLE `ftp_cvr_e530` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `filer_naml` varchar(255) DEFAULT NULL,
  `filer_namf` varchar(255) DEFAULT NULL,
  `filer_namt` varchar(255) DEFAULT NULL,
  `filer_nams` varchar(255) DEFAULT NULL,
  `report_num` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `filer_city` varchar(255) DEFAULT NULL,
  `filer_st` varchar(255) DEFAULT NULL,
  `filer_zip4` varchar(255) DEFAULT NULL,
  `occupation` varchar(400) DEFAULT NULL,
  `employer` varchar(400) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `district_cd` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `pmnt_dt` varchar(255) DEFAULT NULL,
  `pmnt_amount` varchar(255) DEFAULT NULL,
  `type_literature` varchar(255) DEFAULT NULL,
  `type_printads` varchar(255) DEFAULT NULL,
  `type_radio` varchar(255) DEFAULT NULL,
  `type_tv` varchar(255) DEFAULT NULL,
  `type_it` varchar(255) DEFAULT NULL,
  `type_billboards` varchar(255) DEFAULT NULL,
  `type_other` varchar(255) DEFAULT NULL,
  `other_desc` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr_lobby_disclosure`;
CREATE TABLE `ftp_cvr_lobby_disclosure` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `sender_id` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `filer_naml` varchar(255) DEFAULT NULL,
  `filer_namf` varchar(255) DEFAULT NULL,
  `filer_namt` varchar(255) DEFAULT NULL,
  `filer_nams` varchar(255) DEFAULT NULL,
  `report_num` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `from_date` varchar(255) DEFAULT NULL,
  `thru_date` varchar(255) DEFAULT NULL,
  `cum_beg_dt` varchar(255) DEFAULT NULL,
  `firm_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(255) DEFAULT NULL,
  `firm_city` varchar(255) DEFAULT NULL,
  `firm_st` varchar(255) DEFAULT NULL,
  `firm_zip4` varchar(255) DEFAULT NULL,
  `firm_phon` varchar(255) DEFAULT NULL,
  `mail_city` varchar(255) DEFAULT NULL,
  `mail_st` varchar(255) DEFAULT NULL,
  `mail_zip4` varchar(255) DEFAULT NULL,
  `mail_phon` varchar(255) DEFAULT NULL,
  `sig_date` varchar(255) DEFAULT NULL,
  `sig_loc` varchar(255) DEFAULT NULL,
  `sig_naml` varchar(255) DEFAULT NULL,
  `sig_namf` varchar(255) DEFAULT NULL,
  `sig_namt` varchar(255) DEFAULT NULL,
  `sig_nams` varchar(255) DEFAULT NULL,
  `prn_naml` varchar(255) DEFAULT NULL,
  `prn_namf` varchar(255) DEFAULT NULL,
  `prn_namt` varchar(255) DEFAULT NULL,
  `prn_nams` varchar(255) DEFAULT NULL,
  `sig_title` varchar(255) DEFAULT NULL,
  `nopart1_cb` varchar(255) DEFAULT NULL,
  `nopart2_cb` varchar(255) DEFAULT NULL,
  `part1_1_cb` varchar(255) DEFAULT NULL,
  `part1_2_cb` varchar(255) DEFAULT NULL,
  `ctrib_n_cb` varchar(255) DEFAULT NULL,
  `ctrib_y_cb` varchar(255) DEFAULT NULL,
  `lby_actvty` varchar(255) DEFAULT NULL,
  `lobby_n_cb` varchar(255) DEFAULT NULL,
  `lobby_y_cb` varchar(255) DEFAULT NULL,
  `major_naml` varchar(255) DEFAULT NULL,
  `major_namf` varchar(255) DEFAULT NULL,
  `major_namt` varchar(255) DEFAULT NULL,
  `major_nams` varchar(255) DEFAULT NULL,
  `rcpcmte_nm` varchar(255) DEFAULT NULL,
  `rcpcmte_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr_registration`;
CREATE TABLE `ftp_cvr_registration` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `sender_id` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `filer_naml` varchar(255) DEFAULT NULL,
  `filer_namf` varchar(255) DEFAULT NULL,
  `filer_namt` varchar(255) DEFAULT NULL,
  `filer_nams` varchar(255) DEFAULT NULL,
  `report_num` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `ls_beg_yr` varchar(255) DEFAULT NULL,
  `ls_end_yr` varchar(255) DEFAULT NULL,
  `qual_date` varchar(255) DEFAULT NULL,
  `eff_date` varchar(255) DEFAULT NULL,
  `bus_city` varchar(255) DEFAULT NULL,
  `bus_st` varchar(255) DEFAULT NULL,
  `bus_zip4` varchar(255) DEFAULT NULL,
  `bus_phon` varchar(255) DEFAULT NULL,
  `bus_fax` varchar(255) DEFAULT NULL,
  `bus_email` varchar(255) DEFAULT NULL,
  `mail_city` varchar(255) DEFAULT NULL,
  `mail_st` varchar(255) DEFAULT NULL,
  `mail_zip4` varchar(255) DEFAULT NULL,
  `mail_phon` varchar(255) DEFAULT NULL,
  `sig_date` varchar(255) DEFAULT NULL,
  `sig_loc` varchar(255) DEFAULT NULL,
  `sig_naml` varchar(255) DEFAULT NULL,
  `sig_namf` varchar(255) DEFAULT NULL,
  `sig_namt` varchar(255) DEFAULT NULL,
  `sig_nams` varchar(255) DEFAULT NULL,
  `prn_naml` varchar(255) DEFAULT NULL,
  `prn_namf` varchar(255) DEFAULT NULL,
  `prn_namt` varchar(255) DEFAULT NULL,
  `prn_nams` varchar(255) DEFAULT NULL,
  `sig_title` varchar(255) DEFAULT NULL,
  `stmt_firm` varchar(255) DEFAULT NULL,
  `ind_cb` varchar(255) DEFAULT NULL,
  `bus_cb` varchar(255) DEFAULT NULL,
  `trade_cb` varchar(255) DEFAULT NULL,
  `oth_cb` varchar(255) DEFAULT NULL,
  `a_b_name` varchar(255) DEFAULT NULL,
  `a_b_city` varchar(255) DEFAULT NULL,
  `a_b_st` varchar(255) DEFAULT NULL,
  `a_b_zip4` varchar(255) DEFAULT NULL,
  `descrip_1` varchar(255) DEFAULT NULL,
  `descrip_2` varchar(255) DEFAULT NULL,
  `c_less50` varchar(255) DEFAULT NULL,
  `c_more50` varchar(255) DEFAULT NULL,
  `ind_class` varchar(255) DEFAULT NULL,
  `ind_descr` varchar(255) DEFAULT NULL,
  `bus_class` varchar(255) DEFAULT NULL,
  `bus_descr` varchar(255) DEFAULT NULL,
  `auth_name` varchar(255) DEFAULT NULL,
  `auth_city` varchar(255) DEFAULT NULL,
  `auth_st` varchar(255) DEFAULT NULL,
  `auth_zip4` varchar(255) DEFAULT NULL,
  `lobby_int` varchar(255) DEFAULT NULL,
  `influen_yn` varchar(255) DEFAULT NULL,
  `firm_name` varchar(255) DEFAULT NULL,
  `newcert_cb` varchar(255) DEFAULT NULL,
  `rencert_cb` varchar(255) DEFAULT NULL,
  `complet_dt` varchar(255) DEFAULT NULL,
  `lby_reg_cb` varchar(255) DEFAULT NULL,
  `lby_604_cb` varchar(255) DEFAULT NULL,
  `st_leg_yn` varchar(255) DEFAULT NULL,
  `st_agency` varchar(255) DEFAULT NULL,
  `lobby_cb` varchar(255) DEFAULT NULL,
  `l_firm_cb` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_cvr_so`;
CREATE TABLE `ftp_cvr_so` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `filer_naml` varchar(255) DEFAULT NULL,
  `filer_namf` varchar(255) DEFAULT NULL,
  `filer_namt` varchar(255) DEFAULT NULL,
  `filer_nams` varchar(255) DEFAULT NULL,
  `report_num` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `qual_cb` varchar(255) DEFAULT NULL,
  `qualfy_dt` varchar(255) DEFAULT NULL,
  `term_date` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `st` varchar(255) DEFAULT NULL,
  `zip4` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `county_res` varchar(255) DEFAULT NULL,
  `county_act` varchar(255) DEFAULT NULL,
  `mail_city` varchar(255) DEFAULT NULL,
  `mail_st` varchar(255) DEFAULT NULL,
  `mail_zip4` varchar(255) DEFAULT NULL,
  `cmte_fax` varchar(255) DEFAULT NULL,
  `cmte_email` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `tres_city` varchar(255) DEFAULT NULL,
  `tres_st` varchar(255) DEFAULT NULL,
  `tres_zip4` varchar(255) DEFAULT NULL,
  `tres_phon` varchar(255) DEFAULT NULL,
  `actvty_lvl` varchar(255) DEFAULT NULL,
  `com82013yn` varchar(255) DEFAULT NULL,
  `com82013nm` varchar(255) DEFAULT NULL,
  `com82013id` varchar(255) DEFAULT NULL,
  `control_cb` varchar(255) DEFAULT NULL,
  `bank_nam` varchar(255) DEFAULT NULL,
  `bank_adr1` varchar(255) DEFAULT NULL,
  `bank_adr2` varchar(255) DEFAULT NULL,
  `bank_city` varchar(255) DEFAULT NULL,
  `bank_st` varchar(255) DEFAULT NULL,
  `bank_zip4` varchar(255) DEFAULT NULL,
  `bank_phon` varchar(255) DEFAULT NULL,
  `acct_opendt` varchar(255) DEFAULT NULL,
  `surplusdsp` varchar(255) DEFAULT NULL,
  `primfc_cb` varchar(255) DEFAULT NULL,
  `genpurp_cb` varchar(255) DEFAULT NULL,
  `gpc_descr` varchar(255) DEFAULT NULL,
  `sponsor_cb` varchar(255) DEFAULT NULL,
  `brdbase_cb` varchar(255) DEFAULT NULL,
  `smcont_qualdt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_debt`;
CREATE TABLE `ftp_debt` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `payee_naml` varchar(255) DEFAULT NULL,
  `payee_namf` varchar(255) DEFAULT NULL,
  `payee_namt` varchar(255) DEFAULT NULL,
  `payee_nams` varchar(255) DEFAULT NULL,
  `payee_city` varchar(255) DEFAULT NULL,
  `payee_st` varchar(255) DEFAULT NULL,
  `payee_zip4` varchar(255) DEFAULT NULL,
  `beg_bal` varchar(255) DEFAULT NULL,
  `amt_incur` varchar(255) DEFAULT NULL,
  `amt_paid` varchar(255) DEFAULT NULL,
  `end_bal` varchar(255) DEFAULT NULL,
  `expn_code` varchar(255) DEFAULT NULL,
  `expn_dscr` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `tres_city` varchar(255) DEFAULT NULL,
  `tres_st` varchar(255) DEFAULT NULL,
  `tres_zip4` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL,
  `xref_schnm` varchar(255) DEFAULT NULL,
  `xref_match` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_efs_filing_log`;
CREATE TABLE `ftp_efs_filing_log` (
  `filing_date` varchar(255) DEFAULT NULL,
  `filingstatus` varchar(255) DEFAULT NULL,
  `vendor` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `error_no` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_expn`;
CREATE TABLE `ftp_expn` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `payee_naml` varchar(255) DEFAULT NULL,
  `payee_namf` varchar(255) DEFAULT NULL,
  `payee_namt` varchar(255) DEFAULT NULL,
  `payee_nams` varchar(255) DEFAULT NULL,
  `payee_city` varchar(255) DEFAULT NULL,
  `payee_st` varchar(255) DEFAULT NULL,
  `payee_zip4` varchar(255) DEFAULT NULL,
  `expn_date` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `cum_ytd` varchar(255) DEFAULT NULL,
  `cum_oth` varchar(255) DEFAULT NULL,
  `expn_chkno` varchar(255) DEFAULT NULL,
  `expn_code` varchar(255) DEFAULT NULL,
  `expn_dscr` varchar(255) DEFAULT NULL,
  `agent_naml` varchar(255) DEFAULT NULL,
  `agent_namf` varchar(255) DEFAULT NULL,
  `agent_namt` varchar(255) DEFAULT NULL,
  `agent_nams` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `tres_city` varchar(255) DEFAULT NULL,
  `tres_st` varchar(255) DEFAULT NULL,
  `tres_zip4` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL,
  `g_from_e_f` varchar(255) DEFAULT NULL,
  `xref_schnm` varchar(255) DEFAULT NULL,
  `xref_match` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_f495p2`;
CREATE TABLE `ftp_f495p2` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `elect_date` varchar(255) DEFAULT NULL,
  `electjuris` varchar(255) DEFAULT NULL,
  `contribamt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_f501_502`;
CREATE TABLE `ftp_f501_502` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `committee_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `report_num` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `stmt_type` varchar(255) DEFAULT NULL,
  `from_date` varchar(255) DEFAULT NULL,
  `thru_date` varchar(255) DEFAULT NULL,
  `elect_date` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `can_namm` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `moniker_pos` varchar(255) DEFAULT NULL,
  `moniker` varchar(255) DEFAULT NULL,
  `cand_city` varchar(255) DEFAULT NULL,
  `cand_st` varchar(255) DEFAULT NULL,
  `cand_zip4` varchar(255) DEFAULT NULL,
  `cand_phon` varchar(255) DEFAULT NULL,
  `cand_fax` varchar(255) DEFAULT NULL,
  `cand_email` varchar(255) DEFAULT NULL,
  `fin_naml` varchar(255) DEFAULT NULL,
  `fin_namf` varchar(255) DEFAULT NULL,
  `fin_namt` varchar(255) DEFAULT NULL,
  `fin_nams` varchar(255) DEFAULT NULL,
  `fin_city` varchar(255) DEFAULT NULL,
  `fin_st` varchar(255) DEFAULT NULL,
  `fin_zip4` varchar(255) DEFAULT NULL,
  `fin_phon` varchar(255) DEFAULT NULL,
  `fin_fax` varchar(255) DEFAULT NULL,
  `fin_email` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `agency_nam` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `party` varchar(255) DEFAULT NULL,
  `yr_of_elec` varchar(255) DEFAULT NULL,
  `elec_type` varchar(255) DEFAULT NULL,
  `execute_dt` varchar(255) DEFAULT NULL,
  `can_sig` varchar(255) DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `acct_op_dt` varchar(255) DEFAULT NULL,
  `party_cd` varchar(255) DEFAULT NULL,
  `district_cd` varchar(255) DEFAULT NULL,
  `accept_limit_yn` varchar(255) DEFAULT NULL,
  `did_exceed_dt` varchar(255) DEFAULT NULL,
  `cntrb_prsnl_fnds_dt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_f690p2`;
CREATE TABLE `ftp_f690p2` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `exec_date` varchar(255) DEFAULT NULL,
  `from_date` varchar(255) DEFAULT NULL,
  `thru_date` varchar(255) DEFAULT NULL,
  `chg_parts` varchar(255) DEFAULT NULL,
  `chg_sects` varchar(255) DEFAULT NULL,
  `amend_txt1` varchar(330) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_acronyms`;
CREATE TABLE `ftp_filer_acronyms` (
  `acronym` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_address`;
CREATE TABLE `ftp_filer_address` (
  `filer_id` varchar(255) DEFAULT NULL,
  `adrid` varchar(255) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL,
  `add_type` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_ethics_class`;
CREATE TABLE `ftp_filer_ethics_class` (
  `filer_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `ethics_date` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_filings`;
CREATE TABLE `ftp_filer_filings` (
  `filer_id` bigint(20) NOT NULL,
  `filing_id` varchar(255) NOT NULL DEFAULT '',
  `period_id` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) NOT NULL DEFAULT '',
  `filing_sequence` varchar(255) NOT NULL DEFAULT '',
  `filing_date` varchar(255) DEFAULT NULL,
  `stmnt_type` varchar(255) DEFAULT NULL,
  `stmnt_status` varchar(255) DEFAULT NULL,
  `session_id` smallint(6) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `special_audit` varchar(255) DEFAULT NULL,
  `fine_audit` varchar(255) DEFAULT NULL,
  `rpt_start` varchar(255) DEFAULT NULL,
  `rpt_end` varchar(255) DEFAULT NULL,
  `rpt_date` varchar(255) DEFAULT NULL,
  `filing_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`filer_id`,`filing_id`(10),`form_id`(10),`filing_sequence`(10)),
  KEY `filer_id` (`filer_id`),
  KEY `filing_id` (`filing_id`(10)),
  KEY `filing_sequence` (`filing_sequence`(10)),
  KEY `form_id` (`form_id`(10)),
  KEY `rpt_end` (`rpt_end`(10)),
  KEY `session_id` (`session_id`),
  KEY `multi_01` (`filer_id`,`session_id`)
);

DROP TABLE IF EXISTS `ftp_filer_interests`;
CREATE TABLE `ftp_filer_interests` (
  `filer_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `interest_cd` varchar(255) DEFAULT NULL,
  `effect_date` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_links`;
CREATE TABLE `ftp_filer_links` (
  `filer_id_a` varchar(255) DEFAULT NULL,
  `filer_id_b` varchar(255) DEFAULT NULL,
  `active_flg` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `link_type` varchar(255) DEFAULT NULL,
  `link_desc` varchar(255) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL,
  `dominate_filer` varchar(255) DEFAULT NULL,
  `termination_dt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_status_types`;
CREATE TABLE `ftp_filer_status_types` (
  `status_type` varchar(255) DEFAULT NULL,
  `status_desc` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_to_filer_type`;
CREATE TABLE `ftp_filer_to_filer_type` (
  `filer_id` varchar(255) DEFAULT NULL,
  `filer_type` varchar(255) DEFAULT NULL,
  `active` varchar(255) DEFAULT NULL,
  `race` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `category_type` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL,
  `sub_category_type` varchar(255) DEFAULT NULL,
  `election_type` varchar(255) DEFAULT NULL,
  `sub_category_a` varchar(255) DEFAULT NULL,
  `nyq_dt` varchar(255) DEFAULT NULL,
  `party_cd` varchar(255) DEFAULT NULL,
  `county_cd` varchar(255) DEFAULT NULL,
  `district_cd` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_types`;
CREATE TABLE `ftp_filer_types` (
  `filer_type` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `grp_type` varchar(255) DEFAULT NULL,
  `calc_use` varchar(255) DEFAULT NULL,
  `grace_period` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filer_xref`;
CREATE TABLE `ftp_filer_xref` (
  `filer_id` bigint(20) DEFAULT NULL,
  `xref_id` bigint(20) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL,
  `migration_source` varchar(255) DEFAULT NULL,
  KEY `xref_id` (`xref_id`),
  KEY `filer_id` (`filer_id`)
);

DROP TABLE IF EXISTS `ftp_filername`;
CREATE TABLE `ftp_filername` (
  `xref_filer_id` varchar(255) DEFAULT NULL,
  `filer_id` varchar(255) DEFAULT NULL,
  `filer_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL,
  `naml` varchar(255) DEFAULT NULL,
  `namf` varchar(255) DEFAULT NULL,
  `namt` varchar(255) DEFAULT NULL,
  `nams` varchar(255) DEFAULT NULL,
  `adr1` varchar(255) DEFAULT NULL,
  `adr2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `st` varchar(255) DEFAULT NULL,
  `zip4` varchar(255) DEFAULT NULL,
  `phon` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filers`;
CREATE TABLE `ftp_filers` (
  `filer_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filing_period`;
CREATE TABLE `ftp_filing_period` (
  `period_id` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `period_type` varchar(255) DEFAULT NULL,
  `per_grp_type` varchar(255) DEFAULT NULL,
  `period_desc` varchar(255) DEFAULT NULL,
  `deadline` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_filings`;
CREATE TABLE `ftp_filings` (
  `filing_id` varchar(255) DEFAULT NULL,
  `filing_type` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_group_types`;
CREATE TABLE `ftp_group_types` (
  `grp_id` varchar(255) DEFAULT NULL,
  `grp_name` varchar(255) DEFAULT NULL,
  `grp_desc` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_hdr`;
CREATE TABLE `ftp_hdr` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `ef_type` varchar(255) DEFAULT NULL,
  `state_cd` varchar(255) DEFAULT NULL,
  `cal_ver` varchar(255) DEFAULT NULL,
  `soft_name` varchar(255) DEFAULT NULL,
  `soft_ver` varchar(255) DEFAULT NULL,
  `hdrcomment` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_header`;
CREATE TABLE `ftp_header` (
  `line_number` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `section_label` varchar(255) DEFAULT NULL,
  `comments1` varchar(255) DEFAULT NULL,
  `comments2` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `column_a` varchar(255) DEFAULT NULL,
  `column_b` varchar(255) DEFAULT NULL,
  `column_c` varchar(255) DEFAULT NULL,
  `show_c` varchar(255) DEFAULT NULL,
  `show_b` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_image_links`;
CREATE TABLE `ftp_image_links` (
  `img_link_id` varchar(255) DEFAULT NULL,
  `img_link_type` varchar(255) DEFAULT NULL,
  `img_id` varchar(255) DEFAULT NULL,
  `img_type` varchar(255) DEFAULT NULL,
  `img_dt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_latt`;
CREATE TABLE `ftp_latt` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `recip_naml` varchar(255) DEFAULT NULL,
  `recip_namf` varchar(255) DEFAULT NULL,
  `recip_namt` varchar(255) DEFAULT NULL,
  `recip_nams` varchar(255) DEFAULT NULL,
  `recip_city` varchar(255) DEFAULT NULL,
  `recip_st` varchar(255) DEFAULT NULL,
  `recip_zip4` varchar(255) DEFAULT NULL,
  `pmt_date` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `cum_amt` varchar(255) DEFAULT NULL,
  `cumbeg_dt` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lccm`;
CREATE TABLE `ftp_lccm` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `recip_naml` varchar(255) DEFAULT NULL,
  `recip_namf` varchar(255) DEFAULT NULL,
  `recip_namt` varchar(255) DEFAULT NULL,
  `recip_nams` varchar(255) DEFAULT NULL,
  `recip_city` varchar(255) DEFAULT NULL,
  `recip_st` varchar(255) DEFAULT NULL,
  `recip_zip4` varchar(255) DEFAULT NULL,
  `recip_id` varchar(255) DEFAULT NULL,
  `ctrib_naml` varchar(255) DEFAULT NULL,
  `ctrib_namf` varchar(255) DEFAULT NULL,
  `ctrib_namt` varchar(255) DEFAULT NULL,
  `ctrib_nams` varchar(255) DEFAULT NULL,
  `ctrib_date` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_legislative_sessions`;
CREATE TABLE `ftp_legislative_sessions` (
  `session_id` varchar(255) DEFAULT NULL,
  `begin_date` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lemp`;
CREATE TABLE `ftp_lemp` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `cli_naml` varchar(255) DEFAULT NULL,
  `cli_namf` varchar(255) DEFAULT NULL,
  `cli_namt` varchar(255) DEFAULT NULL,
  `cli_nams` varchar(255) DEFAULT NULL,
  `cli_city` varchar(255) DEFAULT NULL,
  `cli_st` varchar(255) DEFAULT NULL,
  `cli_zip4` varchar(255) DEFAULT NULL,
  `cli_phon` varchar(255) DEFAULT NULL,
  `eff_date` varchar(255) DEFAULT NULL,
  `con_period` varchar(255) DEFAULT NULL,
  `agencylist` varchar(255) DEFAULT NULL,
  `descrip` varchar(255) DEFAULT NULL,
  `subfirm_id` varchar(255) DEFAULT NULL,
  `sub_name` varchar(255) DEFAULT NULL,
  `sub_city` varchar(255) DEFAULT NULL,
  `sub_st` varchar(255) DEFAULT NULL,
  `sub_zip4` varchar(255) DEFAULT NULL,
  `sub_phon` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lexp`;
CREATE TABLE `ftp_lexp` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `recsubtype` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `payee_naml` varchar(255) DEFAULT NULL,
  `payee_namf` varchar(255) DEFAULT NULL,
  `payee_namt` varchar(255) DEFAULT NULL,
  `payee_nams` varchar(255) DEFAULT NULL,
  `payee_city` varchar(255) DEFAULT NULL,
  `payee_st` varchar(255) DEFAULT NULL,
  `payee_zip4` varchar(255) DEFAULT NULL,
  `credcardco` varchar(255) DEFAULT NULL,
  `bene_name` varchar(255) DEFAULT NULL,
  `bene_posit` varchar(255) DEFAULT NULL,
  `bene_amt` varchar(255) DEFAULT NULL,
  `expn_dscr` varchar(255) DEFAULT NULL,
  `expn_date` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_loan`;
CREATE TABLE `ftp_loan` (
  `filing_id` varchar(255) NOT NULL DEFAULT '',
  `amend_id` varchar(255) NOT NULL DEFAULT '',
  `line_item` varchar(255) NOT NULL DEFAULT '',
  `rec_type` varchar(255) NOT NULL DEFAULT '',
  `form_type` varchar(255) NOT NULL DEFAULT '',
  `tran_id` varchar(255) DEFAULT NULL,
  `loan_type` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `lndr_naml` varchar(255) DEFAULT NULL,
  `lndr_namf` varchar(255) DEFAULT NULL,
  `lndr_namt` varchar(255) DEFAULT NULL,
  `lndr_nams` varchar(255) DEFAULT NULL,
  `loan_city` varchar(255) DEFAULT NULL,
  `loan_st` varchar(255) DEFAULT NULL,
  `loan_zip4` varchar(255) DEFAULT NULL,
  `loan_date1` varchar(255) DEFAULT NULL,
  `loan_date2` varchar(255) DEFAULT NULL,
  `loan_amt1` varchar(255) DEFAULT NULL,
  `loan_amt2` varchar(255) DEFAULT NULL,
  `loan_amt3` varchar(255) DEFAULT NULL,
  `loan_amt4` varchar(255) DEFAULT NULL,
  `loan_rate` varchar(255) DEFAULT NULL,
  `loan_emp` varchar(255) DEFAULT NULL,
  `loan_occ` varchar(255) DEFAULT NULL,
  `loan_self` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `tres_city` varchar(255) DEFAULT NULL,
  `tres_st` varchar(255) DEFAULT NULL,
  `tres_zip4` varchar(255) DEFAULT NULL,
  `intr_naml` varchar(255) DEFAULT NULL,
  `intr_namf` varchar(255) DEFAULT NULL,
  `intr_namt` varchar(255) DEFAULT NULL,
  `intr_nams` varchar(255) DEFAULT NULL,
  `intr_city` varchar(255) DEFAULT NULL,
  `intr_st` varchar(255) DEFAULT NULL,
  `intr_zip4` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL,
  `xref_schnm` varchar(255) DEFAULT NULL,
  `xref_match` varchar(255) DEFAULT NULL,
  `loan_amt5` varchar(255) DEFAULT NULL,
  `loan_amt6` varchar(255) DEFAULT NULL,
  `loan_amt7` varchar(255) DEFAULT NULL,
  `loan_amt8` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`filing_id`(10),`amend_id`(10),`line_item`(10),`rec_type`(10),`form_type`(10)),
  KEY `filing_id` (`filing_id`(10)),
  KEY `amend_id` (`amend_id`(10)),
  KEY `form_type` (`form_type`(10))
);

DROP TABLE IF EXISTS `ftp_lobby_amendments`;
CREATE TABLE `ftp_lobby_amendments` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `exec_date` varchar(255) DEFAULT NULL,
  `from_date` varchar(255) DEFAULT NULL,
  `thru_date` varchar(255) DEFAULT NULL,
  `add_l_cb` varchar(255) DEFAULT NULL,
  `add_l_eff` varchar(255) DEFAULT NULL,
  `a_l_naml` varchar(255) DEFAULT NULL,
  `a_l_namf` varchar(255) DEFAULT NULL,
  `a_l_namt` varchar(255) DEFAULT NULL,
  `a_l_nams` varchar(255) DEFAULT NULL,
  `del_l_cb` varchar(255) DEFAULT NULL,
  `del_l_eff` varchar(255) DEFAULT NULL,
  `d_l_naml` varchar(255) DEFAULT NULL,
  `d_l_namf` varchar(255) DEFAULT NULL,
  `d_l_namt` varchar(255) DEFAULT NULL,
  `d_l_nams` varchar(255) DEFAULT NULL,
  `add_le_cb` varchar(255) DEFAULT NULL,
  `add_le_eff` varchar(255) DEFAULT NULL,
  `a_le_naml` varchar(255) DEFAULT NULL,
  `a_le_namf` varchar(255) DEFAULT NULL,
  `a_le_namt` varchar(255) DEFAULT NULL,
  `a_le_nams` varchar(255) DEFAULT NULL,
  `del_le_cb` varchar(255) DEFAULT NULL,
  `del_le_eff` varchar(255) DEFAULT NULL,
  `d_le_naml` varchar(255) DEFAULT NULL,
  `d_le_namf` varchar(255) DEFAULT NULL,
  `d_le_namt` varchar(255) DEFAULT NULL,
  `d_le_nams` varchar(255) DEFAULT NULL,
  `add_lf_cb` varchar(255) DEFAULT NULL,
  `add_lf_eff` varchar(255) DEFAULT NULL,
  `a_lf_name` varchar(255) DEFAULT NULL,
  `del_lf_cb` varchar(255) DEFAULT NULL,
  `del_lf_eff` varchar(255) DEFAULT NULL,
  `d_lf_name` varchar(255) DEFAULT NULL,
  `other_cb` varchar(255) DEFAULT NULL,
  `other_eff` varchar(255) DEFAULT NULL,
  `other_desc` varchar(255) DEFAULT NULL,
  `f606_yes` varchar(255) DEFAULT NULL,
  `f606_no` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbying_chg_log`;
CREATE TABLE `ftp_lobbying_chg_log` (
  `filer_id` varchar(255) DEFAULT NULL,
  `change_no` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `log_dt` varchar(255) DEFAULT NULL,
  `filer_type` varchar(255) DEFAULT NULL,
  `correction_flg` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `attribute_changed` varchar(255) DEFAULT NULL,
  `ethics_dt` varchar(255) DEFAULT NULL,
  `interests` varchar(255) DEFAULT NULL,
  `filer_full_name` varchar(380) DEFAULT NULL,
  `filer_city` varchar(255) DEFAULT NULL,
  `filer_st` varchar(255) DEFAULT NULL,
  `filer_zip` varchar(255) DEFAULT NULL,
  `filer_phone` varchar(255) DEFAULT NULL,
  `entity_type` varchar(255) DEFAULT NULL,
  `entity_name` varchar(380) DEFAULT NULL,
  `entity_city` varchar(255) DEFAULT NULL,
  `entity_st` varchar(255) DEFAULT NULL,
  `entity_zip` varchar(255) DEFAULT NULL,
  `entity_phone` varchar(255) DEFAULT NULL,
  `entity_id` varchar(255) DEFAULT NULL,
  `responsible_officer` varchar(380) DEFAULT NULL,
  `effect_dt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_contributions1`;
CREATE TABLE `ftp_lobbyist_contributions1` (
  `filer_id` varchar(255) DEFAULT NULL,
  `filing_period_start_dt` varchar(255) DEFAULT NULL,
  `filing_period_end_dt` varchar(255) DEFAULT NULL,
  `contribution_dt` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(300) DEFAULT NULL,
  `recipient_id` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_contributions2`;
CREATE TABLE `ftp_lobbyist_contributions2` (
  `filer_id` varchar(255) DEFAULT NULL,
  `filing_period_start_dt` varchar(255) DEFAULT NULL,
  `filing_period_end_dt` varchar(255) DEFAULT NULL,
  `contribution_dt` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(300) DEFAULT NULL,
  `recipient_id` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_contributions3`;
CREATE TABLE `ftp_lobbyist_contributions3` (
  `filer_id` varchar(255) DEFAULT NULL,
  `filing_period_start_dt` varchar(255) DEFAULT NULL,
  `filing_period_end_dt` varchar(255) DEFAULT NULL,
  `contribution_dt` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(300) DEFAULT NULL,
  `recipient_id` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_emp_lobbyist1`;
CREATE TABLE `ftp_lobbyist_emp_lobbyist1` (
  `lobbyist_id` varchar(255) DEFAULT NULL,
  `employer_id` varchar(255) DEFAULT NULL,
  `lobbyist_last_name` varchar(400) DEFAULT NULL,
  `lobbyist_first_name` varchar(400) DEFAULT NULL,
  `employer_name` varchar(400) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_emp_lobbyist2`;
CREATE TABLE `ftp_lobbyist_emp_lobbyist2` (
  `lobbyist_id` varchar(255) DEFAULT NULL,
  `employer_id` varchar(255) DEFAULT NULL,
  `lobbyist_last_name` varchar(400) DEFAULT NULL,
  `lobbyist_first_name` varchar(400) DEFAULT NULL,
  `employer_name` varchar(400) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_employer1`;
CREATE TABLE `ftp_lobbyist_employer1` (
  `employer_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `employer_name` varchar(300) DEFAULT NULL,
  `current_qtr_amt` varchar(255) DEFAULT NULL,
  `session_total_amt` varchar(255) DEFAULT NULL,
  `contributor_id` varchar(255) DEFAULT NULL,
  `interest_cd` varchar(255) DEFAULT NULL,
  `interest_name` varchar(300) DEFAULT NULL,
  `session_yr_1` varchar(255) DEFAULT NULL,
  `session_yr_2` varchar(255) DEFAULT NULL,
  `yr_1_ytd_amt` varchar(255) DEFAULT NULL,
  `yr_2_ytd_amt` varchar(255) DEFAULT NULL,
  `qtr_1` varchar(255) DEFAULT NULL,
  `qtr_2` varchar(255) DEFAULT NULL,
  `qtr_3` varchar(255) DEFAULT NULL,
  `qtr_4` varchar(255) DEFAULT NULL,
  `qtr_5` varchar(255) DEFAULT NULL,
  `qtr_6` varchar(255) DEFAULT NULL,
  `qtr_7` varchar(255) DEFAULT NULL,
  `qtr_8` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_employer2`;
CREATE TABLE `ftp_lobbyist_employer2` (
  `employer_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `employer_name` varchar(300) DEFAULT NULL,
  `current_qtr_amt` varchar(255) DEFAULT NULL,
  `session_total_amt` varchar(255) DEFAULT NULL,
  `contributor_id` varchar(255) DEFAULT NULL,
  `interest_cd` varchar(255) DEFAULT NULL,
  `interest_name` varchar(300) DEFAULT NULL,
  `session_yr_1` varchar(255) DEFAULT NULL,
  `session_yr_2` varchar(255) DEFAULT NULL,
  `yr_1_ytd_amt` varchar(255) DEFAULT NULL,
  `yr_2_ytd_amt` varchar(255) DEFAULT NULL,
  `qtr_1` varchar(255) DEFAULT NULL,
  `qtr_2` varchar(255) DEFAULT NULL,
  `qtr_3` varchar(255) DEFAULT NULL,
  `qtr_4` varchar(255) DEFAULT NULL,
  `qtr_5` varchar(255) DEFAULT NULL,
  `qtr_6` varchar(255) DEFAULT NULL,
  `qtr_7` varchar(255) DEFAULT NULL,
  `qtr_8` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_employer3`;
CREATE TABLE `ftp_lobbyist_employer3` (
  `employer_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `employer_name` varchar(300) DEFAULT NULL,
  `current_qtr_amt` varchar(255) DEFAULT NULL,
  `session_total_amt` varchar(255) DEFAULT NULL,
  `contributor_id` varchar(255) DEFAULT NULL,
  `interest_cd` varchar(255) DEFAULT NULL,
  `interest_name` varchar(300) DEFAULT NULL,
  `session_yr_1` varchar(255) DEFAULT NULL,
  `session_yr_2` varchar(255) DEFAULT NULL,
  `yr_1_ytd_amt` varchar(255) DEFAULT NULL,
  `yr_2_ytd_amt` varchar(255) DEFAULT NULL,
  `qtr_1` varchar(255) DEFAULT NULL,
  `qtr_2` varchar(255) DEFAULT NULL,
  `qtr_3` varchar(255) DEFAULT NULL,
  `qtr_4` varchar(255) DEFAULT NULL,
  `qtr_5` varchar(255) DEFAULT NULL,
  `qtr_6` varchar(255) DEFAULT NULL,
  `qtr_7` varchar(255) DEFAULT NULL,
  `qtr_8` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_employer_firms1`;
CREATE TABLE `ftp_lobbyist_employer_firms1` (
  `employer_id` varchar(255) DEFAULT NULL,
  `firm_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(400) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `termination_dt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_employer_firms2`;
CREATE TABLE `ftp_lobbyist_employer_firms2` (
  `employer_id` varchar(255) DEFAULT NULL,
  `firm_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(400) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `termination_dt` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm1`;
CREATE TABLE `ftp_lobbyist_firm1` (
  `firm_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(300) DEFAULT NULL,
  `current_qtr_amt` varchar(255) DEFAULT NULL,
  `session_total_amt` varchar(255) DEFAULT NULL,
  `contributor_id` varchar(255) DEFAULT NULL,
  `session_yr_1` varchar(255) DEFAULT NULL,
  `session_yr_2` varchar(255) DEFAULT NULL,
  `yr_1_ytd_amt` varchar(255) DEFAULT NULL,
  `yr_2_ytd_amt` varchar(255) DEFAULT NULL,
  `qtr_1` varchar(255) DEFAULT NULL,
  `qtr_2` varchar(255) DEFAULT NULL,
  `qtr_3` varchar(255) DEFAULT NULL,
  `qtr_4` varchar(255) DEFAULT NULL,
  `qtr_5` varchar(255) DEFAULT NULL,
  `qtr_6` varchar(255) DEFAULT NULL,
  `qtr_7` varchar(255) DEFAULT NULL,
  `qtr_8` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm2`;
CREATE TABLE `ftp_lobbyist_firm2` (
  `firm_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(300) DEFAULT NULL,
  `current_qtr_amt` varchar(255) DEFAULT NULL,
  `session_total_amt` varchar(255) DEFAULT NULL,
  `contributor_id` varchar(255) DEFAULT NULL,
  `session_yr_1` varchar(255) DEFAULT NULL,
  `session_yr_2` varchar(255) DEFAULT NULL,
  `yr_1_ytd_amt` varchar(255) DEFAULT NULL,
  `yr_2_ytd_amt` varchar(255) DEFAULT NULL,
  `qtr_1` varchar(255) DEFAULT NULL,
  `qtr_2` varchar(255) DEFAULT NULL,
  `qtr_3` varchar(255) DEFAULT NULL,
  `qtr_4` varchar(255) DEFAULT NULL,
  `qtr_5` varchar(255) DEFAULT NULL,
  `qtr_6` varchar(255) DEFAULT NULL,
  `qtr_7` varchar(255) DEFAULT NULL,
  `qtr_8` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm3`;
CREATE TABLE `ftp_lobbyist_firm3` (
  `firm_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(300) DEFAULT NULL,
  `current_qtr_amt` varchar(255) DEFAULT NULL,
  `session_total_amt` varchar(255) DEFAULT NULL,
  `contributor_id` varchar(255) DEFAULT NULL,
  `session_yr_1` varchar(255) DEFAULT NULL,
  `session_yr_2` varchar(255) DEFAULT NULL,
  `yr_1_ytd_amt` varchar(255) DEFAULT NULL,
  `yr_2_ytd_amt` varchar(255) DEFAULT NULL,
  `qtr_1` varchar(255) DEFAULT NULL,
  `qtr_2` varchar(255) DEFAULT NULL,
  `qtr_3` varchar(255) DEFAULT NULL,
  `qtr_4` varchar(255) DEFAULT NULL,
  `qtr_5` varchar(255) DEFAULT NULL,
  `qtr_6` varchar(255) DEFAULT NULL,
  `qtr_7` varchar(255) DEFAULT NULL,
  `qtr_8` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm_employer1`;
CREATE TABLE `ftp_lobbyist_firm_employer1` (
  `firm_id` varchar(255) DEFAULT NULL,
  `filing_id` varchar(255) DEFAULT NULL,
  `filing_sequence` varchar(255) DEFAULT NULL,
  `firm_name` varchar(400) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `rpt_start` varchar(255) DEFAULT NULL,
  `rpt_end` varchar(255) DEFAULT NULL,
  `per_total` varchar(255) DEFAULT NULL,
  `cum_total` varchar(255) DEFAULT NULL,
  `lby_actvty` varchar(255) DEFAULT NULL,
  `ext_lby_actvty` varchar(4000) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm_employer2`;
CREATE TABLE `ftp_lobbyist_firm_employer2` (
  `firm_id` varchar(255) DEFAULT NULL,
  `filing_id` varchar(255) DEFAULT NULL,
  `filing_sequence` varchar(255) DEFAULT NULL,
  `firm_name` varchar(400) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `rpt_start` varchar(255) DEFAULT NULL,
  `rpt_end` varchar(255) DEFAULT NULL,
  `per_total` varchar(255) DEFAULT NULL,
  `cum_total` varchar(255) DEFAULT NULL,
  `lby_actvty` varchar(255) DEFAULT NULL,
  `ext_lby_actvty` varchar(4000) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm_lobbyist1`;
CREATE TABLE `ftp_lobbyist_firm_lobbyist1` (
  `lobbyist_id` varchar(255) DEFAULT NULL,
  `firm_id` varchar(255) DEFAULT NULL,
  `lobbyist_last_name` varchar(400) DEFAULT NULL,
  `lobbyist_first_name` varchar(400) DEFAULT NULL,
  `firm_name` varchar(400) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lobbyist_firm_lobbyist2`;
CREATE TABLE `ftp_lobbyist_firm_lobbyist2` (
  `lobbyist_id` varchar(255) DEFAULT NULL,
  `firm_id` varchar(255) DEFAULT NULL,
  `lobbyist_last_name` varchar(400) DEFAULT NULL,
  `lobbyist_first_name` varchar(400) DEFAULT NULL,
  `firm_name` varchar(400) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lookup_codes`;
CREATE TABLE `ftp_lookup_codes` (
  `code_type` varchar(255) DEFAULT NULL,
  `code_id` varchar(255) DEFAULT NULL,
  `code_desc` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_loth`;
CREATE TABLE `ftp_loth` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `firm_name` varchar(255) DEFAULT NULL,
  `firm_city` varchar(255) DEFAULT NULL,
  `firm_st` varchar(255) DEFAULT NULL,
  `firm_zip4` varchar(255) DEFAULT NULL,
  `firm_phon` varchar(255) DEFAULT NULL,
  `subj_naml` varchar(255) DEFAULT NULL,
  `subj_namf` varchar(255) DEFAULT NULL,
  `subj_namt` varchar(255) DEFAULT NULL,
  `subj_nams` varchar(255) DEFAULT NULL,
  `pmt_date` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `cum_amt` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_lpay`;
CREATE TABLE `ftp_lpay` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `emplr_naml` varchar(255) DEFAULT NULL,
  `emplr_namf` varchar(255) DEFAULT NULL,
  `emplr_namt` varchar(255) DEFAULT NULL,
  `emplr_nams` varchar(255) DEFAULT NULL,
  `emplr_city` varchar(255) DEFAULT NULL,
  `emplr_st` varchar(255) DEFAULT NULL,
  `emplr_zip4` varchar(255) DEFAULT NULL,
  `emplr_phon` varchar(255) DEFAULT NULL,
  `lby_actvty` varchar(255) DEFAULT NULL,
  `fees_amt` varchar(255) DEFAULT NULL,
  `reimb_amt` varchar(255) DEFAULT NULL,
  `advan_amt` varchar(255) DEFAULT NULL,
  `advan_dscr` varchar(255) DEFAULT NULL,
  `per_total` varchar(255) DEFAULT NULL,
  `cum_total` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL,
  `emplr_id` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_names`;
CREATE TABLE `ftp_names` (
  `namid` varchar(255) DEFAULT NULL,
  `naml` varchar(255) DEFAULT NULL,
  `namf` varchar(255) DEFAULT NULL,
  `namt` varchar(255) DEFAULT NULL,
  `nams` varchar(255) DEFAULT NULL,
  `moniker` varchar(255) DEFAULT NULL,
  `moniker_pos` varchar(255) DEFAULT NULL,
  `namm` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `naml_search` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_rcpt`;
CREATE TABLE `ftp_rcpt` (
  `filing_id` varchar(255) NOT NULL DEFAULT '',
  `amend_id` varchar(255) NOT NULL DEFAULT '',
  `line_item` varchar(255) NOT NULL DEFAULT '',
  `rec_type` varchar(255) NOT NULL DEFAULT '',
  `form_type` varchar(255) NOT NULL DEFAULT '',
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `ctrib_naml` varchar(255) DEFAULT NULL,
  `ctrib_namf` varchar(255) DEFAULT NULL,
  `ctrib_namt` varchar(255) DEFAULT NULL,
  `ctrib_nams` varchar(255) DEFAULT NULL,
  `ctrib_city` varchar(255) DEFAULT NULL,
  `ctrib_st` varchar(255) DEFAULT NULL,
  `ctrib_zip4` varchar(255) DEFAULT NULL,
  `ctrib_emp` varchar(255) DEFAULT NULL,
  `ctrib_occ` varchar(255) DEFAULT NULL,
  `ctrib_self` varchar(255) DEFAULT NULL,
  `tran_type` varchar(255) DEFAULT NULL,
  `rcpt_date` varchar(255) DEFAULT NULL,
  `date_thru` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `cum_ytd` varchar(255) DEFAULT NULL,
  `cum_oth` varchar(255) DEFAULT NULL,
  `ctrib_dscr` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `tres_naml` varchar(255) DEFAULT NULL,
  `tres_namf` varchar(255) DEFAULT NULL,
  `tres_namt` varchar(255) DEFAULT NULL,
  `tres_nams` varchar(255) DEFAULT NULL,
  `tres_city` varchar(255) DEFAULT NULL,
  `tres_st` varchar(255) DEFAULT NULL,
  `tres_zip4` varchar(255) DEFAULT NULL,
  `intr_naml` varchar(255) DEFAULT NULL,
  `intr_namf` varchar(255) DEFAULT NULL,
  `intr_namt` varchar(255) DEFAULT NULL,
  `intr_nams` varchar(255) DEFAULT NULL,
  `intr_city` varchar(255) DEFAULT NULL,
  `intr_st` varchar(255) DEFAULT NULL,
  `intr_zip4` varchar(255) DEFAULT NULL,
  `intr_emp` varchar(255) DEFAULT NULL,
  `intr_occ` varchar(255) DEFAULT NULL,
  `intr_self` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL,
  `xref_schnm` varchar(255) DEFAULT NULL,
  `xref_match` varchar(255) DEFAULT NULL,
  `int_rate` varchar(255) DEFAULT NULL,
  `intr_cmteid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`filing_id`(10),`amend_id`(10),`line_item`(10),`rec_type`(10),`form_type`(10)),
  KEY `filing_id` (`filing_id`(10)),
  KEY `amend_id` (`amend_id`(10)),
  KEY `form_type` (`form_type`(10))
);

DROP TABLE IF EXISTS `ftp_received_filings`;
CREATE TABLE `ftp_received_filings` (
  `filer_id` varchar(255) DEFAULT NULL,
  `filing_file_name` varchar(255) DEFAULT NULL,
  `received_date` varchar(255) DEFAULT NULL,
  `filing_directory` varchar(255) DEFAULT NULL,
  `filing_id` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `receive_comment` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_reports`;
CREATE TABLE `ftp_reports` (
  `rpt_id` varchar(255) DEFAULT NULL,
  `rpt_name` varchar(255) DEFAULT NULL,
  `rpt_desc_` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `data_object` varchar(255) DEFAULT NULL,
  `parms_flg_y_n` varchar(255) DEFAULT NULL,
  `rpt_type` varchar(255) DEFAULT NULL,
  `parm_definition` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_s401`;
CREATE TABLE `ftp_s401` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `agent_naml` varchar(255) DEFAULT NULL,
  `agent_namf` varchar(255) DEFAULT NULL,
  `agent_namt` varchar(255) DEFAULT NULL,
  `agent_nams` varchar(255) DEFAULT NULL,
  `payee_naml` varchar(255) DEFAULT NULL,
  `payee_namf` varchar(255) DEFAULT NULL,
  `payee_namt` varchar(255) DEFAULT NULL,
  `payee_nams` varchar(255) DEFAULT NULL,
  `payee_city` varchar(255) DEFAULT NULL,
  `payee_st` varchar(255) DEFAULT NULL,
  `payee_zip4` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `aggregate` varchar(255) DEFAULT NULL,
  `expn_dscr` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bakref_tid` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_s496`;
CREATE TABLE `ftp_s496` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `exp_date` varchar(255) DEFAULT NULL,
  `expn_dscr` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `date_thru` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_s497`;
CREATE TABLE `ftp_s497` (
  `filing_id` varchar(255) NOT NULL DEFAULT '',
  `amend_id` varchar(255) NOT NULL DEFAULT '',
  `line_item` varchar(255) NOT NULL DEFAULT '',
  `rec_type` varchar(255) NOT NULL DEFAULT '',
  `form_type` varchar(255) NOT NULL DEFAULT '',
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `enty_naml` varchar(255) DEFAULT NULL,
  `enty_namf` varchar(255) DEFAULT NULL,
  `enty_namt` varchar(255) DEFAULT NULL,
  `enty_nams` varchar(255) DEFAULT NULL,
  `enty_city` varchar(255) DEFAULT NULL,
  `enty_st` varchar(255) DEFAULT NULL,
  `enty_zip4` varchar(255) DEFAULT NULL,
  `ctrib_emp` varchar(255) DEFAULT NULL,
  `ctrib_occ` varchar(255) DEFAULT NULL,
  `ctrib_self` varchar(255) DEFAULT NULL,
  `elec_date` varchar(255) DEFAULT NULL,
  `ctrib_date` varchar(255) DEFAULT NULL,
  `date_thru` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `bal_id` varchar(255) DEFAULT NULL,
  `cand_id` varchar(255) DEFAULT NULL,
  `sup_off_cd` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`filing_id`(10),`amend_id`(10),`line_item`(10),`rec_type`(10),`form_type`(10)),
  KEY `filing_id` (`filing_id`(10)),
  KEY `amend_id` (`amend_id`(10)),
  KEY `form_type` (`form_type`(10))
);

DROP TABLE IF EXISTS `ftp_s498`;
CREATE TABLE `ftp_s498` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `tran_id` varchar(255) DEFAULT NULL,
  `entity_cd` varchar(255) DEFAULT NULL,
  `cmte_id` varchar(255) DEFAULT NULL,
  `payor_naml` varchar(255) DEFAULT NULL,
  `payor_namf` varchar(255) DEFAULT NULL,
  `payor_namt` varchar(255) DEFAULT NULL,
  `payor_nams` varchar(255) DEFAULT NULL,
  `payor_city` varchar(255) DEFAULT NULL,
  `payor_st` varchar(255) DEFAULT NULL,
  `payor_zip4` varchar(255) DEFAULT NULL,
  `date_rcvd` varchar(255) DEFAULT NULL,
  `amt_rcvd` varchar(255) DEFAULT NULL,
  `cand_naml` varchar(255) DEFAULT NULL,
  `cand_namf` varchar(255) DEFAULT NULL,
  `cand_namt` varchar(255) DEFAULT NULL,
  `cand_nams` varchar(255) DEFAULT NULL,
  `office_cd` varchar(255) DEFAULT NULL,
  `offic_dscr` varchar(255) DEFAULT NULL,
  `juris_cd` varchar(255) DEFAULT NULL,
  `juris_dscr` varchar(255) DEFAULT NULL,
  `dist_no` varchar(255) DEFAULT NULL,
  `off_s_h_cd` varchar(255) DEFAULT NULL,
  `bal_name` varchar(255) DEFAULT NULL,
  `bal_num` varchar(255) DEFAULT NULL,
  `bal_juris` varchar(255) DEFAULT NULL,
  `sup_opp_cd` varchar(255) DEFAULT NULL,
  `amt_attrib` varchar(255) DEFAULT NULL,
  `memo_code` varchar(255) DEFAULT NULL,
  `memo_refno` varchar(255) DEFAULT NULL,
  `employer` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `selfemp_cb` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_smry`;
CREATE TABLE `ftp_smry` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `amount_a` varchar(255) DEFAULT NULL,
  `amount_b` varchar(255) DEFAULT NULL,
  `amount_c` varchar(255) DEFAULT NULL,
  `elec_dt` varchar(255) DEFAULT NULL,
  KEY `filing_id` (`filing_id`(10)),
  KEY `amend_id` (`amend_id`(10)),
  KEY `line_item` (`line_item`(10)),
  KEY `amount_c` (`amount_c`(10))
);

DROP TABLE IF EXISTS `ftp_splt`;
CREATE TABLE `ftp_splt` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `pform_type` varchar(255) DEFAULT NULL,
  `ptran_id` varchar(255) DEFAULT NULL,
  `elec_date` varchar(255) DEFAULT NULL,
  `elec_amount` varchar(255) DEFAULT NULL,
  `elec_code` varchar(255) DEFAULT NULL
);

DROP TABLE IF EXISTS `ftp_text_memo`;
CREATE TABLE `ftp_text_memo` (
  `filing_id` varchar(255) DEFAULT NULL,
  `amend_id` varchar(255) DEFAULT NULL,
  `line_item` varchar(255) DEFAULT NULL,
  `rec_type` varchar(255) DEFAULT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `ref_no` varchar(255) DEFAULT NULL,
  `text4000` varchar(4000) DEFAULT NULL
);

