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

