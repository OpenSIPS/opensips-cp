-- 
-- Structura de tabel pentru tabelul `monitored_stats`
-- 

CREATE TABLE `monitored_stats` (
  `name` varchar(64) NOT NULL,
  `extra` varchar(64) NOT NULL,
#  PRIMARY KEY  (`name`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Structura de tabel pentru tabelul `monitoring_stats`
-- 

CREATE TABLE `monitoring_stats` (
  `name` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `value` varchar(64) NOT NULL default '0',
#  PRIMARY KEY  (`name`,`time`)
) ENGINE=MyISAM;
