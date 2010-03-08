<!--
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
-->



<?php
if (!isset($toggle_button)) {

	// 	get nat ping status

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	// get status from the first one only
	$comm_type=params($mi_connectors[0]);
	$message = mi_command("nh_enable_ping" , $errors , $status);
	print_r($errors);
	$message = trim($message);
	//echo $status;
	if (preg_match('/0/',$message,$matches)){	
		$toggle_button = "Enable";
		echo "ping is disabled";
	} else
	if (preg_match('/1/',$message,$matches)) {
		$toggle_button = "Disable";
	}

}
?>
<div>
<?php if(!$_SESSION['read_only']){ ?>
<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>" method="post">
<?php if  ( $toggle_button == "Disable" ) {

	echo '<h3>The current status is <span style="color:#00ff00"> enabled. </span> To disable NAT Ping push the button: <input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton"></h3>';

} else
 if  ( $toggle_button == "Enable" )
{
	echo '<h3>The current status is <span style="color:#ff0000"> disabled. </span>To enable NAT Ping push the button: <input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton"></h3>';

}
?>
	</form>
<?php } else {echo ('<h3>Read-Only mode.</h3>'); } ?>
</div>
