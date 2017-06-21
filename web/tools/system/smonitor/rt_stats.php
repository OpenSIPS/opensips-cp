<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

 
 
 require("../../../common/mi_comm.php");
 require("../../../../config/tools/system/smonitor/local.inc.php");
 require("../../../../config/tools/system/smonitor/db.inc.php");
 require("../../../../config/db.inc.php");
 require("lib/functions.inc.php");
 include("lib/db_connect.php");
 
 session_start(); 
 
 require("lib/put_select_boxes.php");


 $box_id=get_box_id($current_box); 
 
 print_r(get_mi_modules($current_box));

 require("template/header.php");
 $table=$config->table_monitored;
 
 if ($_GET['var']!=null)
 {
  $var_name = $_GET['var'];
  $sql = "SELECT * FROM ".$table." WHERE name='".$var_name."'"." AND box_id=".$box_id;
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
          die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  if (count($resultset)==0){
	$sql = "INSERT INTO ".$table." (name,extra,box_id) VALUES ('".$var_name."','','".$box_id."') ";
	$resultset = $link->prepare($sql);
	$resultset->execute();
	$resultset->free();
  } else {
	$sql = "DELETE FROM ".$table." WHERE name='".$var_name."' AND box_id='".$box_id."'";
	$link->exec($sql);
	}
  $link->disconnect();
 }
 
 if ($_GET['module_id']!=null)
 {
  $module_id = $_GET['module_id'];
  if ($_SESSION['module_open'][$module_id]=="yes") $_SESSION['module_open'][$module_id]="no";
   else $_SESSION['module_open'][$module_id]="yes";
 }
 
 $expanded=false;
 for($i=0; $i<$_SESSION['modules_no']; $i++)
  if ($_SESSION["module_open"][$i]=="yes") $expanded=true;
 
 if ($_POST['reset_stats']!=null){
  $reset=$_POST['reset'];
  for($i=0; $i<sizeof($reset); $i++)
  if ($reset[$i]!=null) reset_var($reset[$i], $current_box);
 }
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
