<?php
/*
* Copyright (C) 2016 OpenSIPS Project
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

require("lib/functions.inc.php");
require_once("../../../../web/common/cfg_comm.php");
require_once("../../../../config/session.inc.php");
include("../../../../config/tools/system/homer/local.inc.php");

$page_name = basename($_SERVER['PHP_SELF']);
$page_id = substr($page_name, 0, strlen($page_name) - 4);

if (isset($_GET['callid'])) {
	$search_path="#/result?query=(trancall:'true',search_callid:'".$_GET['callid']."')";
} else
	$search_path="";


$cookie = generateRandomString(32);

if ($homer_auth_method=="cookie") {
	## set the cookie before doing any kind of output
	setcookie("externalid", $cookie, time()+120, "/", $common_subdomain,  0);
	$homer_URL_extra = '/'.$search_path;
} else {
	## compute the GET param
	$homer_URL_extra = "/api/v1/redirect?externalid=".$cookie."&url=".urlencode($homer_URL.'/'.$search_path);
}


# store the session ID in cache, using as key the value of the cookie
apc_store ( $cookie, session_id(), 60 );

require("template/homer.main.php");

exit();
?>
