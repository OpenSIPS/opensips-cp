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


require("../../../../web/common/cfg_comm.php");
require("lib/functions.inc.php");
require("../../../../config/db.inc.php");
require("../../../../config/tools/system/cdrviewer/db.inc.php");
//include("lib/db_connect.php");
unset($_SESSION['read_only']);
session_start();
$_SESSION['current_tool']="cdrviewer";
get_priv("cdrviewer");
$_SESSION['detailed_callid']=array();
$_SESSION['grouped_results']=true;
header("Location: cdrviewer.php");
?> 
