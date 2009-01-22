USE opensips_1_2 ; 
DROP PROCEDURE IF EXISTS `opensips_cdrs_1_2` ; 
DELIMITER // 
CREATE PROCEDURE opensips_cdrs_1_2()
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE bye_record INT DEFAULT 0;
  DECLARE v_src_leg, v_dst_leg,v_callid,v_from_tag, v_to_tag, v_caller_id, v_callee_id, v_leg_type, v_leg_transition, v_in_gw, v_leg_status VARCHAR(128);
  DECLARE v_inv_time, v_bye_time DATETIME;
  DECLARE inv_cursor CURSOR FOR SELECT src_leg,  dst_leg, time, callid, from_tag, to_tag, caller_id, callee_id, leg_type, leg_transition, in_gw, leg_status FROM opensips_1_2.acc where method='INVITE' and cdr_id='0';
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
  OPEN inv_cursor;
  REPEAT
    FETCH inv_cursor INTO v_src_leg, v_dst_leg, v_inv_time, v_callid, v_from_tag, v_to_tag, v_caller_id, v_callee_id, v_leg_type, v_leg_transition, v_in_gw, v_leg_status;
    IF NOT done THEN
      SET bye_record = 0;
      SELECT 1, time INTO bye_record, v_bye_time FROM opensips_1_2.acc WHERE method='BYE' AND callid=v_callid AND ((from_tag=v_from_tag AND to_tag=v_to_tag) OR (from_tag=v_to_tag AND to_tag=v_from_tag)) ORDER BY time ASC LIMIT 1;
      IF bye_record = 1 THEN
        INSERT INTO opensips_1_2.cdrs (src_uri,dst_uri,call_start_time,duration,sip_call_id,sip_from_tag,sip_to_tag,created, caller_id, callee_id, leg_type, leg_transition, in_gw, leg_status) VALUES (v_src_leg,v_dst_leg,v_inv_time,UNIX_TIMESTAMP(v_bye_time)-UNIX_TIMESTAMP(v_inv_time)+1,v_callid,v_from_tag,v_to_tag,NOW(), v_caller_id, v_callee_id, v_leg_type, v_leg_transition, v_in_gw, v_leg_status);
        UPDATE acc SET cdr_id=last_insert_id() WHERE callid=v_callid AND ((from_tag=v_from_tag AND to_tag=v_to_tag) OR (from_tag=v_to_tag AND to_tag=v_from_tag));
      END IF;
      SET done = 0;
    END IF;
  UNTIL done END REPEAT;
END 
//
DELIMITER ; 
