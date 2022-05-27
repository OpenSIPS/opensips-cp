<?php
session_start();
require_once("../../../common/cfg_comm.php");

$_SESSION['branch'] = $branch;
$_SESSION['module_id'] = $module_id;

if (isset($_SERVER['QUERY_STRING']))
	$query_string = "?".$_SERVER['QUERY_STRING'];
else
	$query_string = "";

header("Location: ../../../common/tools/tviewer/tviewer.php".$query_string);
?>
