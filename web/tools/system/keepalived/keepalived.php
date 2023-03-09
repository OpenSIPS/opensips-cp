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
require("../../../../config/db.inc.php");
require("template/header.php");
require("../../../../config/tools/system/keepalived/settings.inc.php");
include("lib/db_connect.php");
require("../../../../config/globals.php");
require_once("../../../common/mi_comm.php");
require("lib/functions.inc.php");

csrfguard_validate();

$current_page="current_page_keepalived";

session_load();

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if ($action=="switch_box") {
    foreach(get_settings_value("machines") as $machine) {
        foreach($machine['boxes'] as $box) {
	    $box = set_defaults($box);
            if ($box['box'] != $_GET['box'])
		$mode = "backup";
	    else
		$mode = "primary";
	    $command = isset($box[$mode.'_exec'])?$box[$mode.'_exec']:get_settings_value($mode.'_exec');
	    if ($command && $command != "")
		    ssh_conn($box['ssh_ip'], $box['ssh_port'], $box['ssh_user'], $box['ssh_pubkey'], $box['ssh_key'], $command);
        }
    }
    sleep(2);
}

require("template/".$page_id.".main.php");
if($errors) echo($errors);
require("template/footer.php");
exit();

?>
