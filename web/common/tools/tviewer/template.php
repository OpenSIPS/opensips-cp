<?php 

session_start();
require_once("../../../common/cfg_comm.php");

$_SESSION['branch'] = "your_branch";
$_SESSION['module_id'] = "your_module";

header("Location: ../../../common/tools/tviewer/tviewer.php?module_id=".$module_id."");

?>