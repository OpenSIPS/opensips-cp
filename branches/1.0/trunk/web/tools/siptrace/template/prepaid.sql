CREATE TABLE `bb_acc` (
  `d_dlgid` varchar(64) NOT NULL default '',
  `d_callid` varchar(64) NOT NULL default '',
  `d_ruri` varchar(192) NOT NULL default '',
  `d_appid` int(11) NOT NULL default '0',
  `d_state` int(11) NOT NULL default '0',
  `d_init_method` int(11) NOT NULL default '0',
  `d_init_time` int(11) NOT NULL default '0',
  `d_init_type` int(11) NOT NULL default '0',
  `d_last_method` int(11) NOT NULL default '0',
  `d_last_time` int(11) NOT NULL default '0',
  `d_last_type` int(11) NOT NULL default '0',
  `d_start_time` int(11) NOT NULL default '0',
  `d_end_time` int(11) NOT NULL default '0',
  `d_destroy_time` int(11) NOT NULL default '0',
  `da_uri` varchar(230) NOT NULL default '',
  `da_tag` varchar(64) NOT NULL default '',
  `da_contact` varchar(230) NOT NULL default '',
  `db_uri` varchar(230) NOT NULL default '',
  `db_tag` varchar(64) NOT NULL default '',
  `db_contact` varchar(230) NOT NULL default '',
  PRIMARY KEY  (`d_dlgid`)
) ENGINE=MyISAM;

CREATE TABLE `bb_active_calls` (
  `d_hash` int(11) NOT NULL default '0',
  `d_flag` int(11) NOT NULL default '0',
  `d_appid` int(11) NOT NULL default '0',
  `d_callid` varchar(64) NOT NULL default '',
  `d_dlgid` varchar(64) NOT NULL default '',
  `d_ruri` varchar(192) NOT NULL default '',
  `d_state` int(11) NOT NULL default '0',
  `d_init_method` int(11) NOT NULL default '0',
  `d_init_cseq` int(11) NOT NULL default '0',
  `d_init_time` int(11) NOT NULL default '0',
  `d_init_type` int(11) NOT NULL default '0',
  `d_last_method` int(11) NOT NULL default '0',
  `d_last_cseq` int(11) NOT NULL default '0',
  `d_last_time` int(11) NOT NULL default '0',
  `d_last_type` int(11) NOT NULL default '0',
  `d_start_time` int(11) NOT NULL default '0',
  `da_uri` varchar(230) NOT NULL default '',
  `da_tag` varchar(64) NOT NULL default '',
  `da_contact` varchar(230) NOT NULL default '',
  `da_caddr` varchar(230) NOT NULL default '',
  `da_cseq` int(11) NOT NULL default '0',
  `da_route_set` blob,
  `db_uri` varchar(230) NOT NULL default '',
  `db_tag` varchar(64) NOT NULL default '',
  `db_contact` varchar(230) NOT NULL default '',
  `db_caddr` varchar(230) NOT NULL default '',
  `db_cseq` int(11) NOT NULL default '0',
  `db_route_set` blob,
  `d_keepalive_mode` int(11) NOT NULL default '0',
  `da_invcseq` int(11) NOT NULL default '0',
  `db_invcseq` int(11) NOT NULL default '0',
  PRIMARY KEY  (`d_dlgid`)
) ENGINE=MyISAM;

CREATE TABLE `bb_keepalive` (
  `username` varchar(100) NOT NULL default '',
  `hostname` varchar(128) NOT NULL default '',
  `keepalive` int(11) NOT NULL default '0'
) ENGINE=MyISAM;

CREATE TABLE `pp_acc_extra` (
  `dlgid` varchar(128) NOT NULL default '',
  `extra` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`dlgid`)
) ENGINE=MyISAM;

CREATE TABLE `pp_account` (
  `account` varchar(64) NOT NULL default '',
  `operator` varchar(64) NOT NULL default '',
  `plan` varchar(128) NOT NULL default '',
  `type` int(11) NOT NULL default '0',
  `credit` int(11) NOT NULL default '0',
  `reserved` int(11) NOT NULL default '0',
  `available_time` int(11) NOT NULL default '0',
  `consumed_credit` int(11) NOT NULL default '0',
  `consumed_time` int(11) NOT NULL default '0',
  `zoneid` varchar(128) NOT NULL default '0',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `telephone` varchar(128) NOT NULL default '',
  `address` varchar(128) NOT NULL default '',
  `description` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`account`,`operator`)
) ENGINE=MyISAM;

CREATE TABLE `pp_active_calls` (
  `p_dlgid` varchar(128) NOT NULL default '',
  `p_hash` int(11) NOT NULL default '0',
  `p_owner` varchar(192) NOT NULL default '',
  `p_dest` varchar(192) NOT NULL default '',
  `p_state` int(11) NOT NULL default '0',
  `p_cflag` int(11) NOT NULL default '0',
  `p_start_time` int(11) NOT NULL default '0',
  `p_store_time` int(11) NOT NULL default '0',
  `p_account` varchar(64) NOT NULL default '',
  `p_operator` varchar(64) NOT NULL default '',
  `p_charge_plan` varchar(64) NOT NULL default '',
  `p_cpu` int(11) NOT NULL default '0',
  `p_time_unit` int(11) NOT NULL default '0',
  `p_reserved` int(11) NOT NULL default '0',
  `p_connection_charge` int(11) NOT NULL default '0',
  `p_init_time_unit` int(11) NOT NULL default '0',
  `p_init_cpu` int(11) NOT NULL default '0',
  `p_zoneid` int(11) NOT NULL default '0',
  `p_charge_mode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`p_dlgid`)
) ENGINE=MyISAM;

CREATE TABLE `pp_charged` (
  `cid` int(11) NOT NULL auto_increment,
  `dlgid` varchar(128) NOT NULL default '',
  `src_username` varchar(64) NOT NULL default '',
  `src_hostname` varchar(128) NOT NULL default '',
  `dest_addr` varchar(192) NOT NULL default '',
  `start_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `account` varchar(64) NOT NULL default '',
  `operator` varchar(64) NOT NULL default '',
  `plan` varchar(64) NOT NULL default '',
  `cost` int(11) NOT NULL default '0',
  `consumed_time` int(11) NOT NULL default '0',
  `charge_mode` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `dlgid` (`dlgid`)
) ENGINE=MyISAM;

CREATE TABLE `pp_destination` (
  `operator` varchar(64) NOT NULL default '',
  `prefix` varchar(32) NOT NULL default '',
  `type` int(11) NOT NULL default '1',
  `description` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`operator`,`prefix`)
) ENGINE=MyISAM;


CREATE TABLE `pp_plan` (
  `plan` varchar(64) NOT NULL default '',
  `operator` varchar(64) NOT NULL default '',
  `prefix` varchar(128) NOT NULL default '',
  `zoneidx` int(11) NOT NULL default '0',
  `connection_charge` varchar(128) NOT NULL default '0',
  `init_time_unit` varchar(128) NOT NULL default '0',
  `init_cost` varchar(128) NOT NULL default '0',
  `time_unit` varchar(128) NOT NULL default '60',
  `cost` varchar(128) NOT NULL default '0',
  `credit_factor` int(11) NOT NULL default '0',
  `description` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`plan`,`operator`,`prefix`)
) ENGINE=MyISAM;

CREATE TABLE `pp_time_zones` (
  `zoneid` int(11) NOT NULL default '0',
  `operator` varchar(64) NOT NULL default '',
  `offset` int(11) NOT NULL default '0',
  `dstart` varchar(32) NOT NULL default '',
  `dend` varchar(32) NOT NULL default '',
  `saving` int(11) NOT NULL default '0',
  PRIMARY KEY  (`zoneid`,`operator`)
) ENGINE=MyISAM;

CREATE TABLE `pp_user` (
  `ppid` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `hostname` varchar(128) NOT NULL default '',
  `account` varchar(64) NOT NULL default '',
  `operator` varchar(64) NOT NULL default '',
  `plan` varchar(64) NOT NULL default '',
  `simultaneous` int(11) NOT NULL default '1',
  `keepalive` int(11) NOT NULL default '0',
  `type` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ppid`)
) ENGINE=MyISAM;

CREATE TABLE `pp_week_days` (
  `zoneid` int(11) NOT NULL default '0',
  `operator` varchar(64) NOT NULL default '',
  `day` char(3) NOT NULL default 'Mon',
  `start_time` varchar(128) NOT NULL default '0000',
  `costidx` varchar(128) NOT NULL default '0',
  PRIMARY KEY  (`zoneid`,`operator`,`day`)
) ENGINE=MyISAM;

CREATE TABLE `pp_year_days` (
  `zoneid` int(11) NOT NULL default '0',
  `operator` varchar(64) NOT NULL default '',
  `year` int(11) NOT NULL default '0',
  `month` int(11) NOT NULL default '0',
  `day` int(11) NOT NULL default '0',
  `start_time` varchar(128) NOT NULL default '0000',
  `costidx` varchar(128) NOT NULL default '0',
  PRIMARY KEY  (`zoneid`,`operator`,`year`,`month`,`day`)
) ENGINE=MyISAM;

-- # GRANT ALL PRIVILEGES ON prepaid.* TO prepaid@localhost IDENTIFIED  
BY 'fH8.1AxT';