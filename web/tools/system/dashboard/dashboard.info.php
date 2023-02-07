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
require("../../../common/cfg_comm.php");
get_priv("dashboard");
require("../../../../config/db.inc.php");
require("../../../../config/tools/system/dashboard/db.inc.php");
require("../../../../config/tools/system/dashboard/settings.inc.php");
include("lib/db_connect.php");
session_load_from_tool("dashboard");
if (!isset($_GET['widget_type'])) {
	error_log("no widget type");
  http_response_code(404);
	die();
}
if (!isset($_GET['widget_command'])) {
	error_log("no widget command");
  http_response_code(404);
	die();
}
$widget_type = $_GET['widget_type'];
$widget_command = $_GET['widget_command'];
load_widgets();
echo(json_encode($widget_type::$widget_command($_GET)));
?>
