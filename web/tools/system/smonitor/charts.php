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

 session_start();  
 require("template/header.php");
 include("lib/db_connect.php"); 
 
 $box_id=get_box_id($current_box); 
 $table=$config->table_monitoring;
 $name_table=$config->table_monitored;

 $gauge_arr = get_vars_type($current_box);
 
 if ($_GET['stat_id']!=null)
 {
  $stat_id = $_GET['stat_id'];
  if ($_SESSION['stat_open'][$stat_id]=="yes") $_SESSION['stat_open'][$stat_id]="no";
   else $_SESSION['stat_open'][$stat_id]="yes";
 }
 
 if ($_POST['flush']!=null)
 {
  $sql = "DELETE FROM ".$config->table_monitoring." WHERE box_id = ?";
  $stm = $link->prepare($sql);
  if ($stm->execute(array($box_id)) === false)
  	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
 }
 
 $expanded=false;
 for($i=0; $i<sizeof($_SESSION['stat_open']); $i++)
  if ($_SESSION["stat_open"][$i]=="yes") $expanded=true;
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
