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
 
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="mainTitle">Detailed view for Gateway <?=$_GET['gwid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway ID:</b> <?=$resultset[0]['gwid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>GW Type:</b> <?=get_type($resultset[0]['type'])?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>SIP Address:</b> <?=$resultset[0]['address']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Strip:</b> <?=$resultset[0]['strip']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>PRI Prefix:</b> <?=$resultset[0]['pri_prefix']?></td>
 </tr>
<tr>
  	<td class="dataRecord"><b>Probe mode:</b> 
		<?php 
			switch ($resultset[0]['probe_mode']){
				case "0" : echo "0 - Never"; break;
				case "1" : echo "1 - When disabled"; break;
				case "2" : echo "2 - Always"; break;
			}
		?>
	</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Socket:</b> <?=$resultset[0]['socket']?></td>
 </tr>
 <tr>
  	<td class="dataRecord"><b>State:</b> 
		<?php 
			switch ($resultset[0]['state']){
				case "0" : echo "0 - Active"; break;
				case "1" : echo "1 - Inactive"; break;
				case "2" : echo "2 - Probing"; break;
			}
		?>
	</td>
 </tr>
<?php
$gw_attributes_mode = get_settings_value("gw_attributes_mode");
$gw_attributes = get_settings_value("gw_attributes");
if ($gw_attributes_mode != "none") { ?>
 <tr>
  <td class="dataRecord"><b><?=($gw_attributes_mode == "input" && isset($gw_attributes["display_name"])?$gw_attributes["display_name"]:"Attributes")?>:</b> <?=$resultset[0]['attrs']?></td>
 </tr>
<?php } ?>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
</table>
<br>
<?php print_back_button(); ?>
