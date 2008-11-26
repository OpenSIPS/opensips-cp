USE opensips_1_4 ; 
DROP PROCEDURE IF EXISTS `opensips_cdrs_1_4` ; 
DELIMITER // 
CREATE PROCEDURE opensips_cdrs_1_4()
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE bye_record INT DEFAULT 0;
  DECLARE v_caller_id, v_callee_id, v_destination, v_callid,v_from_tag, v_to_tag VARCHAR(64);
  DECLARE v_leg_status VARCHAR(32);
  DECLARE v_inv_time, v_bye_time DATETIME;
  DECLARE inv_cursor CURSOR FOR SELECT caller_id, callee_id, destination, time, leg_status, callid,from_tag, to_tag FROM opensips_1_4.acc where method='INVITE' and cdr_id='0';
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
  OPEN inv_cursor;
  REPEAT
    FETCH inv_cursor INTO v_caller_id, v_callee_id, v_destination, v_inv_time, v_leg_status, v_callid, v_from_tag, v_to_tag;
    IF NOT done THEN
      SET bye_record = 0;
      SELECT 1, time INTO bye_record, v_bye_time FROM opensips_1_4.acc WHERE method='BYE' AND callid=v_callid AND ((from_tag=v_from_tag AND to_tag=v_to_tag) OR (from_tag=v_to_tag AND to_tag=v_from_tag)) ORDER BY time ASC LIMIT 1;
      IF bye_record = 1 THEN
        INSERT INTO cdrs (caller_id,callee_id,destination,call_start_time,duration,leg_status,sip_call_id,sip_from_tag,sip_to_tag,created) VALUES (v_caller_id,v_callee_id,v_destination,v_inv_time,UNIX_TIMESTAMP(v_bye_time)-UNIX_TIMESTAMP(v_inv_time),v_leg_status,v_callid,v_from_tag,v_to_tag,NOW());
        UPDATE acc SET cdr_id=last_insert_id() WHERE callid=v_callid AND ( (from_tag=v_from_tag AND to_tag=v_to_tag) OR (from_tag=v_to_tag AND to_tag=v_from_tag));
      END IF;
      SET done = 0;
    END IF;
  UNTIL done END REPEAT;
END
//
DELIMITER ; 
