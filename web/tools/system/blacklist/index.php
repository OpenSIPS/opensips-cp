<?php

require_once("../../../../config/session.inc.php");
require("../../../common/cfg_comm.php");


session_start();
$_SESSION['user_active_tool']="blacklist";
get_priv();

header("Location: blacklist.php");
?>
