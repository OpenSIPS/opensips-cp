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

<body>
<!-- Keep all menus within masterdiv-->
<div id="masterdiv">
<?php
foreach ($config_modules as $menuitem => $menuitem_config) {
	if ($menuitem_config['enabled']) {
?>

<?php
		$menu_link_text=array();
		if ($handle=opendir('tools/'.$menuitem.'/')) {
			while (false!==($file=readdir($handle))) {
				if ((($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn") && ((in_array($file,$available_tabs)) || ($super_admin))) && ($menuitem_config['modules'][$file]['enabled'])) {
					$menu_link_text[$file]=$menuitem_config['modules'][$file]['name'];
				}
			}
			closedir($handle);

			reset($available_tabs);
			asort($menu_link_text);
			#reset($menu_link_text);

			$tools = array_keys($menu_link_text);
			if ( isset($_SESSION['user_active_tool']) && in_array($_SESSION['user_active_tool'],$tools)){
	?>
			<div id="menu<?=$menuitem?>" class="menu_active" onclick="SwitchMenu('<?=$menuitem?>')"><?=$menuitem_config['name']?></div>
				<span id="<?=$menuitem?>" class="submenu" style="display: block;">
	<?php
			} else {
	?>
			<div id="menu<?=$menuitem?>" class="menu" onclick="SwitchMenu('<?=$menuitem?>')"><?=$menuitem_config['name']?></div>
				<span id="<?=$menuitem?>" class="submenu" style="display: none;">
	<?php	
			} 
	?>
				<table cellspacing="2" cellpadding="0" border="0" id="tbl_menu" >
	<?php	
				foreach ($menu_link_text as $key=>$val) {
	?>
            		<tr height="20">
	<?php 
					if (isset($_SESSION['user_active_tool']) && $_SESSION['user_active_tool'] == $key) { 
	?>
						<td onClick="top.frames['main_body'].location.href='tools/<?=$menuitem?>/<?=$key?>/index.php';">
							<a id="<?=$key?>" class="submenuItemActive" onclick="SwitchSubMenu('<?=$key?>')" href12="tools/<?=$menuitem?>/<?=$key?>/index.php">
								<?=$val?>
							</a>
						</td>
	<?php 
					} 
					else { 
	?>
						<td onClick="top.frames['main_body'].location.href='tools/<?=$menuitem?>/<?=$key?>/index.php';">
							<a id="<?=$key?>" class="submenuItem" onclick="SwitchSubMenu('<?=$key?>')" href12="tools/<?=$menuitem?>/<?=$key?>/index.php">
								<?=$val?>
							</a>
						</td>
	<?php 			
					} 
	?>
            		</tr>
	<?php
          		}
            	next($menu_link_text);
         	}
    ?>
				</table>
			</span>
	<?php 
	}
} 
	?>
</div>
</body>
</html>
