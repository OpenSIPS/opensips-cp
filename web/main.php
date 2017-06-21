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

if (!isset($_SESSION['user_active_tool'])) {
	$main_body="blank.php";
} else {
	foreach ($config_modules as $menuitem => $menuitem_config) {
		if ($menuitem_config['enabled']) {
			if ($handle=opendir('tools/'.$menuitem.'/')){
				while (false!==($file=readdir($handle))) {
					if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn") && ($menuitem_config['modules'][$file]['enabled'])) {
						${"file_".$menuitem}[] = $file;
						if ($_SESSION['user_active_tool'] == $file){
            				$main_body="tools/".$menuitem."/".$_SESSION['user_active_tool']."/".$_SESSION['user_active_page'];	
						}
						$i++;
					}
				}
				closedir($handle);
			}
		}
	}
}
?>

<html>

<head>
 <title><?=$page_title?></title>
</head>

<frameset border="0" frameborder="0" framespacing="0" rows="35,*,25">

 <frame noresize scrolling="no" src="header.php" name="main_header">

 <frameset border="0" frameborder="0" framespacing="0" cols="180,*">
  <frame noresize scrolling="no" src="menu.php" name="main_menu">
  <frame noresize scrolling="auto" src="<?=$main_body?>" name="main_body">
 </frameset>

 <frame noresize scrolling="no" src="footer.php" name="main_footer">
 
</frameset>
 
</html>
