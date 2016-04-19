<!--
 /*
 * $Id$
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
-->



<?php
if (!isset($toggle_button)) {

	// 	get nat ping status

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	// get status from the first one only
	$comm_type=mi_get_conn_params($mi_connectors[0]);
	mi_command("nh_enable_ping" , $errors , $status);
	print_r($errors);
	$status = trim($status);
	
	if (preg_match('/0/',$status,$matches)){	
		$toggle_button = "enable";
	} else
	if (preg_match('/1/',$status,$matches)) {
		$toggle_button = "disable";
	}

}
?>
<div>
<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>" method="post">
<?php if  ( $toggle_button == "disable" ) {

	echo '<h3>The current status is <span style="color:#00ff00"> enabled. </span> To disable NAT Ping push the button: <input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton"></h3>';

} else
 if  ( $toggle_button == "enable" )
{
	echo '<h3>The current status is <span style="color:#ff0000"> disabled. </span>To enable NAT Ping push the button: <input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton"></h3>';
}
?>
	</form>
</div>
