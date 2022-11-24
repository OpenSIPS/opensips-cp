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

<?php
 if ($resultset[0]['gwlist']=="") $gwlist='<img src="../../../images/share/inactive.png" alt="No GW List">';
  else if ( preg_match('/[#][0-9]+/',$resultset[0]['gwlist'])) $gwlist=parse_list($resultset[0]['gwlist']); 
  else $gwlist=parse_gwlist($resultset[0]['gwlist']);
?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="mainTitle">Detailed view for Rule #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Rule ID:</b> <?=$resultset[0]['ruleid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> 
  <?php 
   echo (get_groupid($resultset[0]['groupid']));
  ?>
  </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Prefix:</b> <?=$resultset[0]['prefix']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Time Recurrence:</b> <?=parse_timerec($resultset[0]['timerec'],1)?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Priority:</b> <?=$resultset[0]['priority']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Route ID:</b> <?=$resultset[0]['routeid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$gwlist?></td>
 </tr>
<?php if (get_settings_value("rules_attributes_mode") != "none") { ?>
<?php 	$rules_attributes = get_settings_value("rules_attributes"); ?>
 <tr>
  <td class="dataRecord"><b><?=(isset($rules_attributes["display_name"])?$rules_attributes["display_name"]:"Attributes")?>:</b> <?=$resultset[0]['attrs']?></td>
 </tr>
<?php } ?>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
</table>
<br>
<?php print_back_button(); ?>
