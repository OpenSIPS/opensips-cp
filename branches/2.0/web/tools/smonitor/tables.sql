-- 
-- Table for `monitored_stats`
-- 

DROP TABLE IF EXISTS `monitored_stats`;
CREATE TABLE `monitored_stats` (
  `name` varchar(64) PRIMARY KEY NOT NULL,
  `extra` varchar(64) NOT NULL,
  `box_id` mediumint(8) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table for `monitoring_stats`
-- 

DROP TABLE IF EXISTS `monitoring_stats`;
CREATE TABLE `monitoring_stats` (
  `name` varchar(64) PRIMARY KEY NOT NULL,
  `time` int(11) NOT NULL,
  `value` varchar(64) NOT NULL default '0',
  `box_id` mediumint(8) unsigned NOT NULL default '0'
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

