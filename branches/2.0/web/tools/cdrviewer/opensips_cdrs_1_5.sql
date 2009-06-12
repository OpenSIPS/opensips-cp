USE opensips ; 
DROP PROCEDURE IF EXISTS `opensips_cdrs_1_5` ; 
DELIMITER // 
CREATE PROCEDURE opensips_cdrs_1_5()
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE bye_record INT DEFAULT 0;
  DECLARE v_callid,v_from_tag, v_to_tag VARCHAR(64);
  DECLARE v_inv_time, v_bye_time DATETIME;
  DECLARE inv_cursor CURSOR FOR SELECT time, callid, from_tag, to_tag, caller_id, callee_id FROM acc where method='INVITE' and cdr_id='0';
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
  OPEN inv_cursor;
  REPEAT
    FETCH inv_cursor INTO v_inv_time, v_callid, v_from_tag, v_to_tag,v_caller_id, v_callee_id;
    IF NOT done THEN
      SET bye_record = 0;
      SELECT 1, time INTO bye_record, v_bye_time FROM acc WHERE method='BYE' AND callid=v_callid AND ((from_tag=v_from_tag AND to_tag=v_to_tag) OR (from_tag=v_to_tag AND to_tag=v_from_tag)) ORDER BY time ASC LIMIT 1;
      IF bye_record = 1 THEN
        INSERT INTO cdrs (call_start_time,duration,sip_call_id,sip_from_tag,sip_to_tag,created,caller_id,callee_id) VALUES (v_inv_time,UNIX_TIMESTAMP(v_bye_time)-UNIX_TIMESTAMP(v_inv_time),v_callid,v_from_tag,v_to_tag,NOW(),v_caller_id,v_callee_id);
        UPDATE acc SET cdr_id=last_insert_id() WHERE callid=v_callid AND ( (from_tag=v_from_tag AND to_tag=v_to_tag) OR (from_tag=v_to_tag AND to_tag=v_from_tag));
      END IF;
      SET done = 0;
    END IF;
  UNTIL done END REPEAT;
END
//
DELIMITER ; 
