<?php
  require("../../../common/cfg_comm.php");
  $_SESSION['current_tool'] = "dashboard";
  require("../../../../config/tools/system/dashboard/db.inc.php");
  include("lib/db_connect.php");
  require("../../../../config/db.inc.php");
  require("../../../../config/tools/system/dashboard/settings.inc.php");
  $table=get_settings_value("custom_table");

  $str_json = file_get_contents('php://input');
  $result = json_decode($str_json);
  $panel_id = array_pop($result);


	$sql = 'UPDATE '.$table.' SET positions = ? WHERE id = ? ';
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}

	if ($stm->execute( array(json_encode($result), $panel_id)) == false)
		echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
  else {

  }

?>
