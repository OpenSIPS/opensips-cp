<?php
/*
 * Copyright (C) 2026 OpenSIPS Project
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

$page_name = basename($_SERVER['SCRIPT_NAME']);
$page_id = substr($page_name, 0, strlen($page_name) - 4);
$_SESSION['current_tool'] = 'mi';
$_SESSION['current_group'] = get_group();
session_load();
?>
<html>
<head>
<link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
<style>
<?php require("lib/console.css"); ?>
</style>
</head>
<body bgcolor="#e9ecef">
<div class="mi-page">
<?php require("template/menu.php"); ?>
