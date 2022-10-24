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

function permission($option,$i,$disabled) {
	global $config;
	require("../../../../config/globals.php");
	$permissions = $config->permissions;
	if ($disabled=='disabled') {
		?>
		<select disabled="disabled" name="permission<?php print "_$i";?>" id="permission" size="1" style="width: 175px" class="dataSelect" >
		<?php
	} else {
	?>
		<select name="permission<?php print "_$i";?>" id="permission" size="1" style="width: 175px" class="dataSelect" >
	<?php
	}

	if (!empty($option)) {
             echo('<option value="'.$option. '" selected > '.$option.'</option>');			
	}	
		
	foreach ($permissions as $key) { 
		if ($key==$option){
			continue;
		} else {
		
             		echo('<option value="'.$key. '" > '.$key.'</option>');			
		}
	}
	?>
	</select>
	<?php
}


?>
