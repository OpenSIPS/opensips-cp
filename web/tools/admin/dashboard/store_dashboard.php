<?php
  require("../../../../config/tools/admin/dashboard/db.inc.php");
  include("lib/db_connect.php");
  require("../../../../config/db.inc.php");
  require("../../../../config/tools/admin/dashboard/local.inc.php");
  $table=$config->table_dashboard; 

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