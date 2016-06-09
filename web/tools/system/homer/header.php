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

session_start();

require_once("../../../../web/common/cfg_comm.php");
require_once("../../../../config/session.inc.php");
get_priv("homer");

if ($_GET['action']=="Hjump")
{
	include("../../../../config/tools/system/homer/local.inc.php");

	echo '<script type="text/javascript" language="javascript">
	window.open("'.$homer_URL.'");
	</script>';
}

require("template/header.main.php");
exit();

?>
