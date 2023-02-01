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

<form action="<?=$page_name?>" method="post">
<?php csrfguard_generate(); ?>
<table width="100%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td align="center" class="mainTitle">Click a statistic to see its chart</td>
 </tr>
</table>

<table width="100%" class="ttable" cellspacing="2" cellpadding="2" border="0">
<?php 
$i = 0;

$sampling_time=get_settings_value('sampling_time');
$monitored_table=get_settings_value('table_monitored');
$stat_img="../../../images/share/chart.png";

foreach (get_stats_list($box_id) as $stat_details) {
	$group = false;
	if (substr($stat_details['name'], 0, 5) == "Group") {
		$stat = substr($stat_details['name'], 7);
		$group = true;
	}
	else
		$stat = $stat_details['name'];
	$from_time= $stat_details['from_time'];

		?>
		<tr>
			<td class="searchRecord">
				<div id="stat_<?=$stat?>" class="Data"  onMouseOver="this.style.cursor='pointer'" onClick="document.location.href='<?=$page_name?>?stat_id=<?=$i?>'">
				<img src="<?=$stat_img?>"><b><?=$stat?></b> - monitored from <?=$from_time?> every <?=$sampling_time?> minute(s)
				</div>
			</td>
		</tr>
		<?php
	
		if (isset($_SESSION["stat_open"][$i]) && $_SESSION["stat_open"][$i]=="yes") {
			?>
			<tr><td class="rowEven"><?php if (!$group) show_graph(str_replace(':', '', $stat), $stat,$box_id); else show_graphs("group".$i, $stat) ?></td></tr>
			<tr><td><img src="../../../images/share/spacer.gif"></td></tr>
			<?php
		}
	$i++;
}
?>
 </tr>
</table>
<br>
<?php
 if ((!$_read_only) && ($data_no!=0)) echo('<input type="submit" name="flush" value="Clear Statistics Logs" class="formButton">');
?>
</form>
