<?php
/*
 * $Id$
 * Copyright (C) 2008-2010 Voice Sistem SRL
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
 $super_admin=false;
 $available_tabs=array();
 if ($_SESSION['user_tabs']!="*") $available_tabs=explode(",",$_SESSION['user_tabs']);
 else $super_admin=true;

include("menu.js");

?>

<html>

<head>
 <base target="main_body">
 <link href='style.css' type='text/css' rel='StyleSheet'>
</head>

<body>
<!-- Keep all menus within masterdiv-->
<div id="masterdiv">
	<div id="menuadmin" class="menu" onclick="SwitchMenu('admin')">Admin</div>
	<span id="admin" class="submenu" >
        <table cellspacing="2" cellpadding="0" border="0" id="tbl_menu" >
        <?php
	 $menu_link_text=array();
         if ($handle=opendir('tools/admin/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn") && ((in_array($file,$available_tabs)) || ($super_admin)))
           {
            $menu_link_text[$file."/"]=trim(file_get_contents("tools/admin/".$file."/tool.name"));
           }
          closedir($handle);

	  reset($available_tabs);
          asort($menu_link_text);
          reset($menu_link_text);
	?>
<?php
          $k=0;
          foreach ($menu_link_text as $key=>$val )
          {
	?>
            <tr height="20" >

		<td onClick="top.frames['main_body'].location.href='tools/admin/<?php print $key?>index.php';"><a class="submenuItem" href="tools/admin/<?php print $key?>index.php"><?=$val?></a></td>
            </tr>
	<?php
                $k++;
          }
            next( $menu_link_text);
         }
        ?>
        </table>

	</span>

	<div id="menuusers" class="menu" onclick="SwitchMenu('users')">Users</div>
	<span class="submenu" id="users">
        <table cellspacing="2" cellpadding="0" border="0" id="tbl_menu" >
        <?php
	 $menu_link_text=array();
         if ($handle=opendir('tools/users/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn") && ((in_array($file,$available_tabs)) || ($super_admin)))
           {
            $menu_link_text[$file."/"]=trim(file_get_contents("tools/users/".$file."/tool.name"));
           }
          closedir($handle);

          asort($menu_link_text);
          reset($menu_link_text);

          $k=0;
          foreach ($menu_link_text as $key=>$val )
          {
           ?>
            <tr height="20" id="<?=$key?>" >
		<td onClick="top.frames['main_body'].location.href='tools/users/<?php print $key?>index.php';"><a class="submenuItem" href="tools/users/<?php print $key?>/index.php"><?=$val?></a></td>
            </tr>
           <?php
                $k++;
          }
            next( $menu_link_text);
         }
        ?>
        </table>

	</span>

	<div id="menusystem" class="menu" onclick="SwitchMenu('system')">System</div>
	<span class="submenu" id="system">
	<table cellspacing="2" cellpadding="0" border="0" id="tbl_menu" >
	<?php
	 $i=0;
	 $menu_link_text=array();
	 if ($handle=opendir('tools/system/'))
	 {
	  while (false!==($file=readdir($handle)))
	   if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn") && ((in_array($file,$available_tabs)) || ($super_admin)))
	   {
            $menu_link_text[$file."/"]=trim(file_get_contents("tools/system/".$file."/tool.name"));
            $i++;
	   }
	  closedir($handle);

	  asort($menu_link_text);
	  reset($menu_link_text);

	  $k=0;
	  foreach ($menu_link_text as $key=>$val )
	  {
	   ?>
	    <tr height="20" id="<?=$key?>">
		<td onClick="top.frames['main_body'].location.href='tools/system/<?php print $key?>index.php';"><a class="submenuItem" href="tools/system/<?php print $key?>index.php"><?=$val?></a></td>
	    </tr>
	   <?php
        	$k++;
	  }
	    next( $menu_link_text);
	 }
	?>
	</table>
	</span>
	
</div>
</body>
</html>
