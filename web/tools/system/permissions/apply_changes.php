<?php
/*
* $Id: address_apply_changes.php 287 2011-10-17 09:41:35Z untiptun $
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

<?php


require("../../../../config/tools/system/dialplan/local.inc.php");
require("../../../common/mi_comm.php");
require("lib/functions.inc.php");

$command="address_reload";

?>
<fieldset><legend>Sending MI command: <?=$command?></legend>
<br>
<?php

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

for ($i=0;$i<count($mi_connectors);$i++){
	echo "Sending to <b>".$mi_connectors[$i]."</b> : ";

	$comm_type=params($mi_connectors[$i]);

	$message=mi_command($command, $errors, $status);

	if ($errors) {
		echo "<font color='red'><b>".$errors[0]."</b></font>";
	} else {
        if (substr(trim($status),0,3) != "200"){
            echo "<font color='red'><b>".substr(trim($status),4)."</b></font>";
        }
        else {
            echo "<font color='green'><b>Success</b></font>";
        }
	}
	echo "<br>";
}

?>

</fieldset>

