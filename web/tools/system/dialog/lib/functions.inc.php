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
require ("../../../../config/tools/system/dialog/local.inc.php");
	global $config;

	if (sizeof($shared_profiles))
		for ( $i=0 ; $i<sizeof($shared_profiles) ; $i++) {
			$spl = preg_split('/\//', $shared_profiles[$i]);
			$shared_profiles[$i] = array("name" => $spl[0], "flag" => "/" . $spl[1]);
		}
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	// get status from the first one only
	$message=mi_command("list_all_profiles", NULL, $mi_connectors[0], $errors);

	if (!empty($message))
		for( $i=0 ; $i<sizeof($message['Profiles']) ; $i++) {
			if (sizeof($shared_profiles))
				for ($j = 0; $j < sizeof($shared_profiles); $j++)
					if ($shared_profiles[$j]['name'] == $message['Profiles'][$i]['name'])
						$message['Profiles'][$i]['flag'] = $shared_profiles[$j]['flag'];
			$options[] = array("label" => $message['Profiles'][$i]['name'], "value" => $message['Profiles'][$i]['name'] . $message['Profiles'][$i]['flag']);
		}

	$start_index = 0;
	$end_index = sizeof($options);
?>
 <select name="profile" id="profile" size="1" style="width: 175px" class="dataSelect">
 <?php
  for ($i=$start_index;$i<$end_index;$i++)
  {
        echo('<option value="'.$options[$i]['value']. '"> '.$options[$i]['label'].'</option>');
   }
 ?>
 </select>
 <?php
 }

?>
