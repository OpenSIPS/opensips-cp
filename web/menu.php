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



require("../config/session.inc.php");
require("../config/modules.inc.php");
$super_admin=false;
$available_tabs=array();
if ($_SESSION['user_tabs']!="*")
	$available_tabs=explode(",",$_SESSION['user_tabs']);
else
	$super_admin=true;
?>

<html>

<head>
	<base target="main_body">
	<link href='style.css' type='text/css' rel='StyleSheet'>
	<script type="text/javascript" src="menu.js"></script>
</head>

<body style="background-color: #374d66;">
<!-- Keep all menus within masterdiv-->
<div id="masterdiv">

<?php
foreach ($config_modules as $menuitem => $menuitem_config) {
	if (!$menuitem_config['enabled'])
		continue;

	# if it has no modules, do not print it at all
	if (!isset($menuitem_config['modules']))
		continue;


	if (isset($menuitem_config['icon'])) {
?>
	<style>#menu<?=$menuitem?>:before { content: url('<?=$menuitem_config['icon']?>');}</style>
<?php
	}
	# check to see if there is a tool within this module that is active
	if (isset($_SESSION['current_tool']) &&
			in_array($_SESSION['current_tool'], $menuitem_config['modules']))
		$active = true;
	else
		$active = false;
?>
<?php
	$menu_link_text=array();
	# now go through each tool and see if it is activated
	foreach ($menuitem_config['modules'] as $key => $value) {
		# if the module is not available, skip it
		if (!isset($value['enabled']) || !$value['enabled'])
			continue;
		# check if this is an available module
		if (!($super_admin) && !in_array($key, $available_tabs))
			continue;
		# check if there is a path and it exists
		if (!isset($value['path']))
			$path = $menuitem . '/' . $key;
		else
			$path = $value['path'];
		# check if the module actually exists
		if (file_exists('tools/'.$path.'/index.php'))
			$menu_link_text[$key] = $value['name'];
	}
	reset($available_tabs);
	//asort($menu_link_text);
	if (count($menu_link_text) == 1) {
		$path = 'tools/';
		if (!isset($menuitem_config['modules'][$key]['path']))
			$path .= $menuitem . '/' . $key;
		else
			$path .= $menuitem_config['modules'][$key]['path'];
		$path .= '/index.php';
		$single_module = true;
		$onclick = "SwitchMenu('".$menuitem."');top.frames['main_body'].location.href='".$path."'";
	} else {
		$single_module = false;
		$onclick = "SwitchMenu('".$menuitem."')";
	}
?>
<div id="menu<?=$menuitem?>" class="menu<?=($active?"_active":"")?>" onclick="<?=$onclick?>"><?=$menuitem_config['name']?></div>
<?php
	if ($single_module)
		continue;
?>
<span id="<?=$menuitem?>" class="submenu" style="display: <?=($active?"block":"none")?>;">
<table cellspacing="2" cellpadding="0" border="0" id="tbl_menu" >
<?php
	foreach ($menu_link_text as $key=>$val) {
		$path = 'tools/';
		if (!isset($menuitem_config['modules'][$key]['path']))
			$path .= $menuitem . '/' . $key;
		else
			$path .= $menuitem_config['modules'][$key]['path'];
		$path .= '/index.php';
		if (isset($_SESSION['current_tool']) && $_SESSION['current_tool'] == $key)
			$active = true;
		else
			$active = false;
?>
<tr height="20">
<td onClick="top.frames['main_body'].location.href='<?=$path?>';">
<a id="<?=$key?>" class="submenuItem<?=($active?"Active":"")?>" onclick="SwitchSubMenu('<?=$key?>')" href12="<?=$path?>"><?=$val?></a>
</td>
</tr>
<?php
	}
?>
</table>
</span>
<?php
}
?>
</div>
</body>
</html>
