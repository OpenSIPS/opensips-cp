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
session_start();

require_once("../../../../config/session.inc.php");
require("../../../common/cfg_comm.php");
require_once("lib/functions.inc.php");
get_priv("monit");
session_load();
require("template/header.php");



if ($source = get_monit_page($foo['host'],$foo['port'],$foo['user'],$foo['pass'],"/","",$foo['has_ssl'])) {
	$page=(substr($source,strpos($source,"\r\n\r\n")+4)) ;

	$newpage=monit_html_replace($page);

	ob_flush();
	flush();
	echo $newpage;
} else {
	echo "I can't connect!";
}

require("template/footer.php");

?>
