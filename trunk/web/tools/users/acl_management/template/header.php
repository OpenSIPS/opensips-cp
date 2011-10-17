<?php
/*
* $Id: header.php 210 2010-03-08 18:09:33Z bogdan_iancu $
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

require_once("../../../../config/session.inc.php");
require_once("../../../../config/tools/users/acl_management/db.inc.php");
require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/users/acl_management/local.inc.php");
require_once("lib/functions.inc.php");

$page_name = basename($_SERVER['PHP_SELF']);
$page_id = substr($page_name, 0, strlen($page_name) - 4);
$no_result = "No Data Found.";
$back_link = '<a href="'.$page_name.'" class="backLink">Go Main</a>';
?>

<html>

<head>
 <link href="style/style.css" type="text/css" rel="StyleSheet">
 
</head>

<body bgcolor="#e9ecef">
<center>
<table width="705" cellpadding="5" cellspacing="5" border="0">
 <tr  valign="top" height="20">
  <td><?php require("template/menu.php") ?></td>
 </tr>
 <tr valign="top" align="center"> 
  <td>
   <img src="images/spacer.gif" width="10" height="5"><br>
