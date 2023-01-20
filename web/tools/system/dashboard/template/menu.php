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
		<?php print "System / Dashboard / ".$_SESSION['permission'];
		  display_settings_button();
		?>
	</td>
</tr>
<tr>
	<td align="center" valign="middle">
	<div class="menuItems">
		<?php
		$config->menu_item = array();
		$orders = array();
		foreach ($_SESSION['config']['panels'] as $key => $row)
		{
				$orders[$key] = $row['order'];
		}
		$sorted_panels = $_SESSION['config']['panels'];
		array_multisort($orders, SORT_ASC, $sorted_panels);
		if (count($sorted_panels) > 0) {
			if (isset($_GET['action']) && $_GET['action'] == "view_new_panel")
				$default = end($sorted_panels)['id'];
			else $default = $sorted_panels[0]['id'];
		}
		else $default = -1; // edit panels (no panels available);

		if (!isset($_GET['action']) || $_GET['action'] == "view_new_panel")
			$current_tab = $default;
		foreach ( $sorted_panels as $id => $elem)
			$config->menu_item[] = array(
			"dashboard.php?action=display_panel&panel_id=".$elem['id'], // page name
			$elem['name'] // menu name
			);
		$config->menu_item[] = array(
				"dashboard.php?action=edit_panel", // page name
				"Edit panels" // menu name
		);
		if ($_GET['action'] == "add_blank_panel")
			$current_req = "dashboard.php?action=edit_panel";
		$first_item = true;
		if (!isset($config->menu_item)) echo('<font class="menuItemSelect">&nbsp;</font>');
		else
		while (list($key,$value) = each($config->menu_item))
		{		
			if (!$first_item) echo('&nbsp;&nbsp;|&nbsp;&nbsp;');
			if ($_SESSION['config']['panels'][$current_tab]['name'] != $config->menu_item[$key]["1"] && $current_req != $config->menu_item[$key]["0"])
				echo('<a href="'.$config->menu_item[$key]["0"].'" class="menuItem">'.$config->menu_item[$key]["1"].'</a>');
			else echo('<a href="'.$config->menu_item[$key]["0"].'" class="menuItemSelect">'.$config->menu_item[$key]["1"].'</a>');
			$first_item = false;
		}
		?>
		<a href=# onclick="lockPanel()" id='lockButton' style="display:none; position:relative; left:-55px; bottom:25px; content: url('../../../images/dashboard/unlock.png');"></a>

	</div>
	</td> 
</tr>
</table>
<br>
