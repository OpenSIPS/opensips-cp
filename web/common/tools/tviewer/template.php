<?php 

session_start();
require_once("../../../common/cfg_comm.php");
$module_id = "your_module";
$branch = "your_branch";
$_SESSION['branch'] = $branch;
$_SESSION['module_id'] = $module_id;

header("Location: ../../../common/tools/tviewer/tviewer.php?module_id=".$module_id."");

?>