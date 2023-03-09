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

require("../../../../config/tools/system/keepalived/settings.inc.php");

if(!$_SESSION['read_only']){
	$colspan = 4;
}else{
	$colspan = 2;
}
echo('<div class="breadcrumb"></div>');

foreach(get_settings_value("machines") as $machine) {
    $has_master = false;
    echo('<table style="text-align: center;" width="95%" cellspacing="1" cellpadding="1" border="0" align="right">');
    $boxes_no = count($machine['boxes']);
    echo('<tr><td colspan=3 style="font-weight: 900;">'.$machine['name'].'</td></tr><tr>');
    foreach($machine['boxes'] as $box) {
        $box_color = "red";
        $box = set_defaults($box);
        $state = ssh_conn($box['ssh_ip'], $box['ssh_port'], $box['ssh_user'], $box['ssh_pubkey'], $box['ssh_key'], $box['check_exec']);
	if ($state) {
	       if (preg_match('/'.$box['check_pattern'].'/', $state)) {
		       if (!$has_master) {
			       $box_color = "green";
			       $has_master = true;
		       } else {
			       $box_color = "red";
		       }
	       } else {
		       $box_color = "grey";
	       }
	}
        echo('
<td>
<input type="submit" onclick="location.href = \'keepalived.php?action=switch_box&box='.$box['box'].'\';" style="background-color:'.$box_color.';" name="'.$box['box'].'" value="'.$box['box'].'" class="formButton add-new-btn">
</td>');
    }
    echo('</tr>
    </table><div style="height:100px;" class="breadcrumb"></div>');
}
?>


<br>
