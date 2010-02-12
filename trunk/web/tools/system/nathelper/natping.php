<?php
/*
 * $Id: natping.php 47 2009-04-28 16:26:07Z iulia_bublea $
 * Copyright (C) 2008 Voice Sistem SRL
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
 
 require("template/header.php");
 require("lib/".$page_id.".main.js");
 
 require("../../../../config/tools/system/nathelper/local.inc.php");
 require("../../../common/mi_comm.php");
 $current_page="current_page_nathelper";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";
 
 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
 
 
if ($action=="toggle"){

$toggle_button=	$_GET['toggle_button'];


if ($toggle_button=="Enable") {	
	$nat_ping	= "1" ;
	$toggle_button = "Disable";
} else
if ($toggle_button=="Disable") {
        $nat_ping       = "0" ;
        $toggle_button = "Enable";
}

$command="nh_enable_ping"." ".$nat_ping;
$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
for ($i=0;$i<count($mi_connectors);$i++){	
		$comm_type=params($mi_connectors[$i]);	
		mi_command($command,$errors,$status);
	}

}


 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
