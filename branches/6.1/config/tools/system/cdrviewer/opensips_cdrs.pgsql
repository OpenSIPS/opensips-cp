CREATE LANGUAGE plpgsql;
ALTER TABLE acc ADD COLUMN cdr_id integer NOT NULL DEFAULT 0;
CREATE OR REPLACE FUNCTION opensips_cdrs() RETURNS text AS $$
DECLARE   bye_record  integer DEFAULT 0;
          v_callid varchar;
          v_from_tag varchar;
          v_to_tag varchar;
          v_inv_time TIMESTAMP;
          v_bye_time TIMESTAMP;
          inv_cursor CURSOR FOR SELECT time, callid, from_tag, to_tag FROM acc where method='INVITE' and cdr_id='0';
          row RECORD;
BEGIN
  FOR row IN SELECT time, callid, from_tag, to_tag FROM acc where method='INVITE' and cdr_id='0' LOOP
      bye_record := 0;
      SELECT 1, time INTO bye_record, v_bye_time FROM acc WHERE method='BYE' AND callid=row.callid AND ((from_tag=row.from_tag AND to_tag=row.to_tag) OR (from_tag=row.to_tag AND to_tag=row.from_tag)) ORDER BY time ASC LIMIT 1;
      IF bye_record = 1 THEN
        INSERT INTO cdrs (call_start_time,duration,sip_call_id,sip_from_tag,sip_to_tag,created) VALUES (row.time,EXTRACT('epoch' FROM v_bye_time)-EXTRACT('epoch' FROM row.time),row.callid,row.from_tag,row.to_tag,NOW());
        UPDATE acc SET cdr_id=CURRVAL(pg_get_serial_sequence('cdrs','cdr_id')) WHERE callid=row.callid AND ( (from_tag=row.from_tag AND to_tag=row.to_tag) OR (from_tag=row.to_tag AND to_tag=row.from_tag));
      END IF;
  END LOOP;
return 1;
END
$$ LANGUAGE plpgsql;



 

