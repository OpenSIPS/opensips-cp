CREATE SEQUENCE admin_id_seq;
CREATE TABLE ocp_admin_privileges (
  id integer Primary KEY DEFAULT nextval('admin_id_seq'),
  first_name text NOT NULL default '',
  last_name text NOT NULL default '',
  username text NOT NULL default '',
  password text NOT NULL default '',
  ha1 text default '',
  blocked text default NULL,
  failed_attempts integer default 0,
  available_tools text NOT NULL default '',
  permissions text default NULL,
  secret text default NULL
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

CREATE SEQUENCE ocp_boxes_config_id_seq;
CREATE TABLE ocp_boxes_config (
  id integer Primary KEY DEFAULT nextval('ocp_boxes_config_id_seq'),
  name text DEFAULT NULL,
  mi_conn text DEFAULT NULL,
  monit_conn text DEFAULT NULL,
  monit_user text DEFAULT NULL,
  monit_pass text DEFAULT NULL,
  monit_ssl text DEFAULT NULL,
  "desc" text NOT NULL DEFAULT '',
  smonitcharts text DEFAULT NULL,
  assoc_id integer DEFAULT '-1'
);

INSERT INTO ocp_boxes_config (mi_conn,"desc",assoc_id) values ('json:127.0.0.1:8888/mi','Default box',1);

-- --------------------------------------------------------

--
-- Table for `ocp_system_config`
--

CREATE SEQUENCE ocp_system_config_id_seq;
CREATE TABLE ocp_system_config (
  assoc_id integer Primary KEY DEFAULT nextval('ocp_system_config_id_seq'),
  name text DEFAULT NULL,
  "desc" text DEFAULT ''
);

INSERT INTO ocp_system_config (assoc_id, name, "desc") values (1,'System 0','Default system');

-- --------------------------------------------------------

--
-- Table for `ocp_tools_config`
--

CREATE SEQUENCE ocp_tools_config_id_seq;
CREATE TABLE ocp_tools_config (
  id integer Primary KEY DEFAULT nextval('ocp_tools_config_id_seq'),
  module text NOT NULL UNIQUE,
  param text NOT NULL UNIQUE,
  value text DEFAULT NULL,
  box_id text DEFAULT '' UNIQUE
);

-- --------------------------------------------------------

--
-- Table for `ocp_dashboard`
--

CREATE SEQUENCE ocp_dashboard_id_seq;
CREATE TABLE ocp_dashboard (
  id integer Primary KEY DEFAULT nextval('ocp_dashboard_id_seq'),
  name text DEFAULT NULL,
  content text NOT NULL,
  "order" integer DEFAULT NULL,
  positions text DEFAULT NULL
);


-- --------------------------------------------------------

--
-- Table for `ocp_extra_stats`
--

CREATE SEQUENCE ocp_extra_stats_id_seq;
CREATE TABLE ocp_extra_stats (
  id integer Primary KEY DEFAULT nextval('ocp_extra_stats_id_seq'),
  name text DEFAULT NULL,
  input text DEFAULT NULL,
  box_id integer default NULL,
  tool text DEFAULT NULL,
  class text DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table for `ocp_db_config`
--

CREATE SEQUENCE ocp_db_config_id_seq;
CREATE TABLE ocp_db_config (
  id integer Primary KEY DEFAULT nextval('ocp_db_config_id_seq'),
  config_name text NOT NULL default '',
  db_host text NOT NULL default '',
  db_port text NOT NULL default '',
  db_user text NOT NULL default '',
  db_pass text default NULL,
  db_name text NOT NULL default ''
);
