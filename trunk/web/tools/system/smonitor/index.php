<?php
/*
 * $Id: index.php 76 2009-07-03 13:42:10Z iulia_bublea $
 */
 
 require("../../../../config/tools/system/smonitor/db.inc.php");
 require("../../../../config/db.inc.php");
 require("../../../../config/tools/system/smonitor/local.inc.php");
 require("../../../common/mi_comm.php"); 
 require("lib/functions.inc.php");
 //include("lib/db_connect.php"); 

 session_start();
 get_priv();

 clean_stats_table();

 header("Location: rt_stats.php");
 
?>
