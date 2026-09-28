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

// the drivers a configuration can use; "" is config/db.inc.php's
function db_config_drivers() {
	global $config;
	return array("" => "Default (".$config->db_driver.")", "mysql" => "MySQL",
		"pgsql" => "PostgreSQL", "sqlite" => "SQLite");
}

// the posted driver as stored, NULL for the default; it ends up in the
// connection's DSN, so anything else is refused
function db_config_driver($driver) {
	if (!isset($driver) || $driver === "")
		return NULL;
	if (!array_key_exists($driver, db_config_drivers()))
		die("Unknown DB driver: ".htmlspecialchars($driver));
	return $driver;
}

function db_config_driver_select($val) {
	global $config;
	$drivers = db_config_drivers();
	form_generate_select("DB driver", "Database driver; SQLite needs no host or user, only the database file path as DB name",
		"db_driver", 64, $val, array_keys($drivers), array_values($drivers));
	// host and user are optional for sqlite: a posted empty value is stored as ''
	echo('<script>
	function db_driver_changed() {
		var driver = document.getElementById("db_driver").value || "'.$config->db_driver.'";
		["db_host", "db_user"].forEach(function (id) {
			var field = document.getElementById(id);
			field.setAttribute("opt", driver == "sqlite" ? "y" : "n");
			field.oninput();
		});
	}
	</script>');
}


?>
