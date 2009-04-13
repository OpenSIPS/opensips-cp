<?php
/*
 * $Id$
 */
 require("../../../config/tools/smonitor/local.inc.php"); 
 require("../../../config/tools/smonitor/db.inc.php"); 
 require("lib/functions.inc.php");

 session_start();  
 require("lib/put_select_boxes.php"); 
 
 
 $box_id=get_box_id($current_box); 
 require("template/header.php");
 $table=$config->table_monitoring;
 $name_table=$config->table_monitored;
 
 if ($_GET['stat_id']!=null)
 {
  $stat_id = $_GET['stat_id'];
  if ($_SESSION['stat_open'][$stat_id]=="yes") $_SESSION['stat_open'][$stat_id]="no";
   else $_SESSION['stat_open'][$stat_id]="yes";
 }
 
 if ($_POST['flush']!=null)
 {
  db_connect();
  mysql_query("delete from ".$config->table_monitoring." where box_id=".$box_id) or die(mysql_error());
  db_close();
 }
 
 $expanded=false;
 for($i=0; $i<sizeof($_SESSION['stat_open']); $i++)
  if ($_SESSION["stat_open"][$i]=="yes") $expanded=true;
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>