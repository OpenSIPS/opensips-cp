<?php
//use this file to point to the config file of this module 
//this file is used in lib/data_loader.php and in template/header.php but also index.php
require_once("../../../common/cfg_comm.php");
$branch = "system";
$module_id = get_value_from_tool("module_id", "tviewer");
$_SESSION['branch'] = "system";
$_SESSION['module_id'] = $module_id;
?>
