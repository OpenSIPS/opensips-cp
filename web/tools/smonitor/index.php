<?php
/*
 * $Id: index.php,v 1.2 2007-04-03 13:10:53 daniel Exp $
 */
 
 require("../../../config/tools/smonitor/db.inc.php");
 require("../../../config/tools/smonitor/local.inc.php");
 require("../../common/mi_comm.php"); 
 require("lib/functions.inc.php");
 
 session_start();
 get_priv();

 clean_stats_table();

 header("Location: rt_stats.php");
 
?>