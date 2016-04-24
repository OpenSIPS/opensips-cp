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

<form action="<?=$page_name?>?action=refresh" method="post">
<?
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	for ($i=0;$i<count($mi_connectors);$i++){

                $message = mi_command('lb_list', $mi_connectors[$i], $mi_type, $errors,$status);
				
				if ($mi_type != "json"){
	                $message = trim($message);
					$pattern = '/Destination\:\:\s+(?P<destination>sip\:[a-zA-Z0-9.:-]+)\s+id=(?P<id>\d+)\s+group=(?P<group>\d+)\s+enabled=(?P<enabled>yes|no)\s+auto-re=(?P<autore>on|off)\s+Resources(?P<resources>(\s+Resource\:\:\s+[a-zA-Z0-9]+\s+max=\d+\s+load=\d+)*)/';
					preg_match_all($pattern,$message,$matches);
					$data_no = count($matches[0]);
				}
				else {
					//no more stupid parsing
					$message = json_decode($message,true);
					$message = $message['Destination'];
					$data_no = count($message);
				}
}
?>
</form>
<form action="<?=$page_name?>?action=refresh" method="post">
<table width="95%" cellspacing="2" cellpadding="2" border="0">
 
 <tr height="10">
  <td colspan="3"  align="right"><input type="submit" name="refresh" value="Refresh from Cache" class="searchButton"></td>
 </tr>
</table>
</form>
<br>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">

  <th class="loadbalancerTitle">ID</th>
  <th class="loadbalancerTitle">Group ID</th>
  <th class="loadbalancerTitle">Destination URI</th>
  <th class="loadbalancerTitle">Status</th>
  <th class="loadbalancerTitle">Auto</th>
  <th class="loadbalancerTitle">Resources</th>

<?

if ($data_no==0) {
	echo('<tr><td colspan="6" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
}
else {
	if ($mi_type != "json"){
		for ($i=0; $i<count($matches[0]);$i++) {
			$row_style = ($i%2==1)?"rowOdd":"rowEven";
			
			$dst_uri 	= $matches['destination'][$i];
			$id			= $matches['id'][$i];
			$group 		= $matches['group'][$i];
			$status 	= $matches['enabled'][$i];
			$auto_re 	= $matches['autore'][$i];

			$pattern	= '/\s+Resource\:\:\s+(?P<resource_name>[a-zA-Z0-9_-]+)\s+max=(?P<resource_max_load>\d+)\s+load=(?P<resource_load>\d+)/';
			
			preg_match_all($pattern,$matches['resources'][$i],$resources);


			$toggle_button =($status=="yes")?"enabled":"disabled";

			$resource="<table>";
			for ($j=0;$j<count($resources[0]);$j++) {
				
				$resource .= "<tr>";
				$resource .= "<td>";
				$resource .= $resources['resource_name'][$j];
				$resource .= " = ";
				$resource .= $resources['resource_load'][$j];
				$resource .= "</td>";
				$resource .= "<td>";
				$resource .= "( max =  ";
				$resource .= $resources['resource_max_load'][$j];
				$resource .= " )  ";
				$resource .= "</td>";
				$resource .= "</tr>";
			}
			$resource .= "</table>";
?>
			<tr align=center>
				<td class="<?=$row_style?>">&nbsp;<?=$id?></td>
				<td class="<?=$row_style?>">&nbsp;<?=$group?></td>
				<td class="<?=$row_style?>">&nbsp;<?=$dst_uri?></td>
				<td class="<?=$row_style?>">&nbsp;
					<div align="center">
			
						<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>&id=<?=$id?>" method="post">
						<? if ( $toggle_button == "enabled" ) {
								echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #00ff00; ">';
						} else if  ( $toggle_button == "disabled" ) {
								echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #ff0000; ">';
						}
						?>
						</form>
					</div>
				</td>
				<td class="<?=$row_style?>">&nbsp;<?=$auto_re?></td>
				<td class="<?=$row_style?>" style="text-align: left; padding-left: 5px;"><?=$resource?></td>
			</tr>
<?
 		}
 	}
 	else {
		for ($i=0; $i<count($message);$i++) {
			$row_style = ($i%2==1)?"rowOdd":"rowEven";
			
			$dst_uri 	= $message[$i]['value'];
			$id 		= $message[$i]['attributes']['id'];
			$group 		= $message[$i]['attributes']['group'];
			$status 	= $message[$i]['attributes']['enabled'];
			$auto_re	= $message[$i]['attributes']['auto-re'];

			$toggle_button =($status=="yes")?"enabled":"disabled";

			$resource="<table>";
			for ($j=0;$j<count($message[$i]['children']['Resources']['children']['Resource']);$j++) {
				
				$resource .= "<tr>";
				$resource .= "<td>";
				$resource .= $message[$i]['children']['Resources']['children']['Resource'][$j]['value'];
				$resource .= " = ";
				$resource .= $message[$i]['children']['Resources']['children']['Resource'][$j]['attributes']['load'];
				$resource .= "</td>";
				$resource .= "<td>";
				$resource .= "( max =  ";
				$resource .= $message[$i]['children']['Resources']['children']['Resource'][$j]['attributes']['max'];
				$resource .= " )  ";
				$resource .= "</td>";
				$resource .= "</tr>";
			}
			$resource .= "</table>";
?>
			<tr align=center>
				<td class="<?=$row_style?>">&nbsp;<?=$id?></td>
				<td class="<?=$row_style?>">&nbsp;<?=$group?></td>
				<td class="<?=$row_style?>">&nbsp;<?=$dst_uri?></td>
				<td class="<?=$row_style?>">&nbsp;
					<div align="center">
			
						<form action="<?=$page_name?>?action=toggle&toggle_button=<?=$toggle_button?>&id=<?=$id?>" method="post">
						<? if ( $toggle_button == "enabled" ) {
								echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #00ff00; ">';
						} else if  ( $toggle_button == "disabled" ) {
								echo '<input type="submit" name="toggle" value="'.$toggle_button.'" class="formButton" style="background-color: #ff0000; ">';
						}
						?>
						</form>
					</div>
				</td>
				<td class="<?=$row_style?>">&nbsp;<?=$auto_re?></td>
				<td class="<?=$row_style?>" style="text-align: left; padding-left: 5px;"><?=$resource?></td>
			</tr>
<?
		}
 	}
}
?>

 <tr>
 <th colspan="6" class="loadbalancerTitle" align="right">Total Records: <?php print $data_no?>&nbsp;</th>
 <tr>
</table>
