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
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td class="breadcrumb">
        <?php print "Users / Group Management / ".$_SESSION['permission'];
          display_settings_button();
        ?>   
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle">
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
<br>
