<?php
/*
 * $Id$
 */
 
 require("../../../config/tools/smonitor/db.inc.php");
 require("../../../config/db.inc.php");
 require("../../../config/tools/smonitor/local.inc.php");
 require("../../common/mi_comm.php"); 
 require("lib/functions.inc.php");
 include("lib/db_connect.php"); 
 session_start();
 get_priv();

 clean_stats_table();

 header("Location: rt_stats.php");
 
?>
