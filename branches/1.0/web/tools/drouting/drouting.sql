USE openser;
CREATE TABLE IF NOT EXISTS dr_gateways (
	`gwid` integer auto_increment,
	`type` int(11) NOT NULL default '0',
	`address` varchar(128) not null,
	`strip` integer default 0,
	`pri_prefix` varchar(16) default null,
	`description` varchar(128) NOT NULL default '',
	PRIMARY KEY  (`gwid`)
	);
CREATE TABLE IF NOT EXISTS dr_rules (
	`ruleid` integer auto_increment,
	`groupid` varchar(255) not null,
	`prefix` varchar(64) not null,
	`timerec` varchar(255) not null,
	`priority` integer default 0,
	`routeid` integer default 0,
	`gwlist` varchar(255) not null,
	`description` varchar(128) NOT NULL default '',
	PRIMARY KEY  (`ruleid`)
	);
CREATE TABLE IF NOT EXISTS dr_groups (
	`username` varchar(64) not null,
	`domain` varchar(128) default "",
	`groupid` integer not null,
	`description` varchar(128) NOT NULL default '',
	PRIMARY KEY (username,domain)
	);
