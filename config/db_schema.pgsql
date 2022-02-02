CREATE SEQUENCE admin_id_seq;
CREATE TABLE ocp_admin_privileges (
  id integer Primary KEY DEFAULT nextval('admin_id_seq'),
  first_name text NOT NULL default '',
  last_name text NOT NULL default '',
  username text NOT NULL default '',
  password text NOT NULL default '',
  ha1 text default '',
  available_tools text NOT NULL default '',
  permissions text default NULL
); 
SET CLIENT_ENCODING TO 'latin1' ;

CREATE FUNCTION unix_timestamp(time_str varchar(19) ) RETURNS integer AS $$
  SELECT (date_part('epoch',to_timestamp(time_str, 'YYYY-MM-DD HH24:MI:SS')))::integer;
  $$ LANGUAGE SQL IMMUTABLE;

CREATE FUNCTION unix_timestamp(time_str TIMESTAMP ) RETURNS integer AS $$
  SELECT (date_part('epoch',time_str))::integer;
  $$ LANGUAGE SQL IMMUTABLE;

INSERT INTO ocp_admin_privileges (username,password,first_name,last_name,ha1,available_tools,permissions) values ('admin','opensips','Super','Admin',md5('admin:opensips'),'all','all');

-- 
-- Table for `ocp_monitored_stats`
-- 

DROP TABLE IF EXISTS ocp_monitored_stats;
CREATE TABLE ocp_monitored_stats (
  name text PRIMARY KEY NOT NULL,
  extra text NOT NULL,
  box_id integer NOT NULL default '0'
);
SET CLIENT_ENCODING TO 'latin1' ;
-- --------------------------------------------------------

-- 
-- Table for `ocp_monitoring_stats`
-- 

DROP TABLE IF EXISTS ocp_monitoring_stats;
CREATE TABLE ocp_monitoring_stats (
  name text NOT NULL,
  time integer NOT NULL,
  value text NOT NULL default '0',
  box_id integer NOT NULL default '0'
);
SET CLIENT_ENCODING TO 'latin1' ;

-- --------------------------------------------------------

-- 
-- Table for `ocp_boxes_config`
-- 

CREATE TABLE ocp_boxes_config (
  id integer Primary KEY DEFAULT nextval('ocp_boxes_config'),
  mi_conn text DEFAULT NULL,
  monit_conn text NOT NULL,
  monit_user text DEFAULT NULL,
  monit_pass text DEFAULT NULL,
  monit_ssl text NOT NULL,
  desc text NOT NULL DEFAULT '',
  smonitcharts text DEFAULT NULL,
  assoc_id integer DEFAULT '-1',
);

-- --------------------------------------------------------

-- 
-- Table for `ocp_system_config`
-- 

CREATE TABLE ocp_system_config (
  assoc_id integer Primary KEY DEFAULT nextval('ocp_system_config'),
  name text DEFAULT NULL,
  desc text DEFAULT ''
)

-- --------------------------------------------------------

-- 
-- Table for `ocp_tools_config`
-- 

CREATE TABLE ocp_tools_config (
  id integer Primary KEY DEFAULT nextval('ocp_tools_config'),
  module text NOT NULL UNIQUE,
  param text NOT NULL UNIQUE,
  value text DEFAULT NULL,
  box_id text DEFAULT '' UNIQUE
)
