<?php
/*
 * Copyright (C) 2016 OpenSIPS Project
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

/*
 * Server-side hook for the "Cluster Nodes" tab. It is wired as the tab's
 * custom_search action_script, so tviewer.php includes it on every Nodes page
 * load. It first handles the one-click Active/Inactive state toggle (fired by
 * the clickable state icon, see clusterer_state_wrapper in tviewer.inc.php),
 * then delegates to the stock tviewer search behaviour.
 */

if ($action == "change_state") {
	if (!$_SESSION['read_only']) {
		$pk = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key'];
		if (isset($_GET[$pk])) {
			$id = $_GET[$pk];

			// flip relative to the state the operator saw when the link was rendered
			$desired = ($_GET['state'] == "1") ? "0" : "1";

			$upd = $link->prepare("UPDATE ".$table." SET state=? WHERE ".$pk."=?");
			$ret = $upd->execute(array($desired, $id));
			if ($ret === false)
				$errors = "Update to DB failed with: ".print_r($upd->errorInfo(), true);
		}
	} else {
		$errors = "User with Read-Only Rights";
	}
}

// delegate to the stock tviewer search handler
require("custom_actions/search.php");
?>
