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
require("../config/modules.inc.php");
require("../config/local.inc.php");

if (!isset($_SESSION['user_login'])) {
	header("Location:index.php?err=1");
}

$main_body="blank.php";
if (isset($_SESSION['user_active_tool'])) {
	foreach ($config_modules as $menuitem => $menuitem_config) {
		if (!$menuitem_config['enabled'])
			continue;
		# if it has no modules, do not print it at all
		if (!isset($menuitem_config['modules']))
			continue;
		foreach ($menuitem_config['modules'] as $key => $value) {
			# if the module is not available, skip it
			if (!isset($value['enabled']) || !$value['enabled'] ||
					$key != $_SESSION['user_active_tool'])
				continue;
			$path = 'tools/';
			# check if there is a path and it exists
			if (!isset($value['path']))
				$path .= $menuitem . '/' . $key;
			else
				$path .= $value['path'];
			$path .= $_SESSION['user_active_page'];
			# check if the module actually exists
			if (file_exists($path)) {
				$main_body=$path;
				break;
			}
		}
	}
}
?>

<html>

<head>
 <title><?=$page_title?></title>
</head>
<script>
function onXloadfunction() {
	var path = top.frames['main_body'].location.pathname;
	var items = path.split('/');
	if (items.length>4 && items[items.length-4]=="tools") {
		var tool = items[items.length-2];
		var section = items[items.length-3];
		top.frames['main_menu'].UpdateWholeMenu(tool);
	}
}

</script>

<frameset border="0" frameborder="0" framespacing="0" rows="30,*,25">

 <frame noresize scrolling="no" src="header.php" name="main_header">

 <frameset border="0" frameborder="0" framespacing="0" cols="180,*">
  <frame noresize scrolling="no" src="menu.php" name="main_menu" id="side-bar" class="side-menu">
  <frame noresize scrolling="auto" src="<?=$main_body?>" name="main_body" onload="onXloadfunction();">
 </frameset>

 <frame noresize scrolling="no" src="footer.php" name="main_footer">

</frameset>

</html>
