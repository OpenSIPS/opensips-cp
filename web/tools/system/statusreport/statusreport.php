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

 require("../../../../config/session.inc.php");
 require("../../../common/cfg_comm.php");
 require("../../../common/mi_comm.php");
 require("../../../../config/db.inc.php");
 require("template/header.php");
 require_once('../../../../config/boxes.load.php');
 require("../../../common/forms.php");
 
 session_load();

 get_mi_identifiers($current_box);

 $table=get_settings_value("table_monitored");	
 
 include("lib/db_connect.php");
 
 if (isset($_GET['group']))
 {
	 $group = $_GET['group'] . ": ";
	 $len = strlen($group);
	 if (isset($_GET['id']))
		 $name = $group . $_GET['id'];
	 else
		 $name = null;
	 for($i=0; $i<$_SESSION['identifiers_no']; $i++) {
		 if ((substr($_SESSION['identifier_name'][$i], 0, $len) == $group) &&
				 ($name == null || $name == $_SESSION['identifier_name'][$i])) {
   			$_SESSION['identifier_open'][$i]="yes";
		 } else {
   			$_SESSION['identifier_open'][$i]="no";
		 }
	 }
 }
 if (isset($_GET['identifier_id']))
 { 
  $identifier_id = $_GET['identifier_id'];
  if ($_SESSION['identifier_open'][$identifier_id]=="yes") $_SESSION['identifier_open'][$identifier_id]="no";
   else $_SESSION['identifier_open'][$identifier_id]="yes";
 }
 
 $expanded=false;
 for($i=0; $i<$_SESSION['identifiers_no']; $i++)
  if ($_SESSION["identifier_open"][$i]=="yes") $expanded=true;

 if (isset($_POST['reset_stats'])){
  $reset=$_POST['reset'];
  for($i=0; $i<sizeof($reset); $i++)
  if ($reset[$i]!=null) reset_var($reset[$i], $current_box);
 }
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
