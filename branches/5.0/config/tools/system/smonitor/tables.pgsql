-- 
-- Table for `monitored_stats`
-- 

DROP TABLE IF EXISTS monitored_stats;
CREATE TABLE monitored_stats (
  name text PRIMARY KEY NOT NULL,
  extra text NOT NULL,
  box_id integer NOT NULL default '0'
);
SET CLIENT_ENCODING TO 'latin1' ;
-- --------------------------------------------------------

-- 
-- Table for `monitoring_stats`
-- 

DROP TABLE IF EXISTS monitoring_stats;
CREATE TABLE monitoring_stats (
  name text NOT NULL,
  time integer NOT NULL,
  value text NOT NULL default '0',
  box_id integer NOT NULL default '0'
);
SET CLIENT_ENCODING TO 'latin1' ;
