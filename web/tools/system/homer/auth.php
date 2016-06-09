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

require("../../../common/cfg_comm.php");

# fetch the cookie from the URL
$cookie = htmlspecialchars($_GET["param"]);
if ($cookie==NULL) {
	header('HTTP/1.0 403 Forbidden');
	echo("{'sid': '','auth': 'false', 'status': 403 , 'message': 'missing parameter'}");
	exit();
}

# fetch the session ID from cache (the cache key is the cookie value)
if ( ($session_id=apc_fetch($cookie))===FALSE ||
$session_id=="" || $session_id==NULL
) {
	header('HTTP/1.0 403 Forbidden');
	echo("{'sid': '','auth': 'false', 'status': 403 , 'message': 'wrong session'}");
	exit();
}

session_id($session_id);
session_start();

if (!isset($_SESSION['user_login'])) {
	header('HTTP/1.0 403 Unauthorized');
	echo("{'sid': '','auth': 'false', 'status': 403 , 'message': 'failed authentication'}");
	exit();
}

get_priv("homer");

# everything is fine
echo('{"uid":1,"gid":10,"grp":"users');
# if with WR rights, put the user in admins group too
if ($_SESSION['permission'] == "Read-Write")
	echo(',admins');
echo('","username":"opensips","firstname":"OpenSIPS","lastname":"CP","email":""}' );

exit();

?>
