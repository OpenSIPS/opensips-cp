<?php
/*
 * $Id$
 * Copyright (C) 2008 Voice Sistem SRL
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
?>

<html>

<head>
 <base target="main_body">
 <link href='style.css' type='text/css' rel='StyleSheet'>
 <script language="JavaScript">

 function select(img, no)
  {
   for (k=0; k<no; k++)
    document[k].src="images/menu_dot.gif";
   document[img].src="images/menu_select.gif";
  }
  
 </script>
</head>

<body>
<br>
<table cellspacing="3" cellpadding="0" border="0" id="tbl_menu" >
<?php
 $i=0;
 if ($handle=opendir('tools/'))
 {
  while (false!==($file=readdir($handle)))
   if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn") && ((in_array($file,$available_tabs)) || ($super_admin)))
   {
    $menu_link_text[$file."/"]=trim(file_get_contents("tools/".$file."/tool.name"));
    $i++;
   }
  closedir($handle);

  asort($menu_link_text);
  reset($menu_link_text);
  
  $k=0;
  foreach ($menu_link_text as $key=>$val )
  {
   	if ($_SESSION['user_active_tool']==$key) $img_src="images/menu_select.gif";
    else $img_src="images/menu_dot.gif";
   ?>
    <tr height="20" id="<?=$key?>" >
     <td width="20" valign="top"><img id="<?=$k?>" src="<?=$img_src?>"></td>
     <td><a href="tools/<?=$key?>" class="menuItem" onClick='select(<?=$k?>, <?=$i?>)'><?=$val?></a></td>
    </tr>
   <?php
	$k++;
  }
    next( $menu_link_text);
 }
?>
</table>
</body>

</html>
