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
function print_profile() {
	session_load();
	global $config;

	$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
	// get status from the first one only
	$message=mi_command("list_all_profiles", NULL, $mi_connectors[0], $errors);

	if (!empty($message))
		for( $i=0 ; $i<sizeof($message['Profiles']) ; $i++) 
			$options[]=array("label"=>$message['Profiles'][$i]['name'],"value"=>$message['Profiles'][$i]['name']);

	$start_index = 0;
	$end_index = sizeof($options);
?>
 <select name="profile" id="profile" size="1" style="width: 175px" class="dataSelect">
 <?php
  for ($i=$start_index;$i<$end_index;$i++)
  {
        echo('<option value="'.$options[$i]['label']. '"> '.$options[$i]['label'].'</option>');
   }
 ?>
 </select>
 <?php
 }

?>
