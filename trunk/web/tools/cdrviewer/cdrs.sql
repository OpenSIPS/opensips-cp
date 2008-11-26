CREATE TABLE `cdrs` (
  `cdr_id` bigint(20) NOT NULL auto_increment,
  `call_start_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `duration` int(10) unsigned NOT NULL default '0',
  `leg_status` varchar(32) NOT NULL default '',
  `sip_call_id` varchar(128) NOT NULL default '',
  `sip_from_tag` varchar(128) NOT NULL default '',
  `sip_to_tag` varchar(128) NOT NULL default '',
  `cdr_rated` bigint(20) unsigned default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `leg_type` varchar(32) NOT NULL default '',
  `leg_transition` varchar(32) NOT NULL default '',
  `caller_id` varchar(32) NOT NULL default '',
  `callee_id` varchar(32) NOT NULL default '',
  `destination` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`cdr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1332970 DEFAULT CHARSET=latin1 ;


