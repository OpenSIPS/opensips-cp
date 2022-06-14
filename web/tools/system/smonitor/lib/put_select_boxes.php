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

 require_once("../../../common/cfg_comm.php");
 require_once("../../../../config/session.inc.php");

 $current_box=$_SESSION['smon_current_box'];  

 if (empty($current_box))
  		$current_box="";

 $boxlist=array();
 $boxlist=inspect_config_mi();


  if (!empty($_POST['box_val'])) {
  	
  		$current_box=$_POST['box_val'];
  		$_SESSION['smon_current_box']=$current_box ; 
  }

  if (!empty($_SESSION['smon_current_box']) && empty($current_box)) {
  		$current_box=$_SESSION['smon_current_box'];
  }


  $current_box=show_boxes($boxlist,$current_box);
  $_SESSION['smon_current_box']=$current_box;

?>
