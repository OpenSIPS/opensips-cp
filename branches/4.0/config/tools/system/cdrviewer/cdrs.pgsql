CREATE SEQUENCE cdr_id_seq;
CREATE TABLE cdrs (
  cdr_id bigint NOT NULL PRIMARY KEY DEFAULT nextval('cdr_id_seq'),
  call_start_time timestamp with time zone NOT NULL default '0000-00-00 00:00:00',
  duration integer NOT NULL default 0,
  sip_call_id text NOT NULL default '',
  sip_from_tag text NOT NULL default '',
  sip_to_tag text NOT NULL default '',
  created timestamp with time zone NOT NULL default '0000-00-00 00:00:00'
) ;
SET CLIENT_ENCODING TO 'latin1' ;
