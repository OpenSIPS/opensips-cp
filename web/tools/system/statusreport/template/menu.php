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

require_once("../../../../config/session.inc.php");

$current_box=$_SESSION['srep_current_box'];  

if (empty($current_box))
     $current_box="";

$boxlist=array();
$boxlist=inspect_config_mi();


 if (!empty($_POST['box_val'])) {
   
     $current_box=$_POST['box_val'];
     $_SESSION['srep_current_box']=$current_box ; 
 }

 if (!empty($_SESSION['srep_current_box']) && empty($current_box)) {
     $current_box=$_SESSION['srep_current_box'];
 }

 $box_id=get_box_id($current_box); 
 $_SESSION['box_id'] = $box_id;
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td class="breadcrumb">
        <?php print "System / Status Report / ".$_SESSION['permission']; ?>
    </td>
    <td align=right style="border-bottom: 1px solid #ccc!important">

      <?php
  	    require("lib/put_select_boxes.php");
        $box_id=get_box_id($current_box); 
        $_SESSION['box_id'] = $box_id;
        display_settings_button();
        session_load();
      ?>
    </td>
  </tr>	
  <tr>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle">
      <div class="menuItems">
	  <?php
        $first_item = true;
        if (!isset($config->menu_item)) echo('<font class="menuItemSelect">&nbsp;</font>');
        else
        while (list($key,$value) = each($config->menu_item))
        {
        	if (!$first_item) echo('&nbsp;&nbsp;|&nbsp;&nbsp;');
        	if ($page_name!=$config->menu_item[$key]["0"]) echo('<a href="'.$config->menu_item[$key]["0"].'" class="menuItem">'.$config->menu_item[$key]["1"].'</a>');
        	else echo('<a href="'.$config->menu_item[$key]["0"].'" class="menuItemSelect">'.$config->menu_item[$key]["1"].'</a>');
        	$first_item = false;
        }
        ?>
      </div>
    </td> 
  </tr>
</table>
<div align="right">
<?php
 if ($page_id=="rt_stats") echo('<button type="button" class="formButton" onClick="window.location.href=\'rt_stats.php\'">Refresh Statistics Values</button><br>');
 if ($page_id=="charts") echo('<button type="button" class="formButton" onClick="window.location.href=\'charts.php\'">Refresh Statistics Charts</button><br>');
?>
</div>
<br>
