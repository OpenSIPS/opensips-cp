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

require(__DIR__."/../../../../../config/tools/admin/boxes_config/settings.inc.php");
$params = $config->boxes;

$sql = "select * from ".$table." where id=?";

$stm = $link->prepare($sql);
if ($stm->execute(array($id)) === false)
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
?>
	<table width="400" border="0">
		<tr>
			<td class="mainTitle">
					View Entry
			</td>
		</tr>

		<tr>
			<td>

				<table class="ttable" width="100%" cellspacing="2" cellpadding="2" border="0">
				<?php
				$combo_cache = array();
				foreach ($params as $key => $value) {
					if ($value['type'] == "combo")
						$combo_cache[$key] = get_combo_options($value);
					if ($value['type'] == "checklist")
						$checklist_cache[$key] = $value['options'];
				}

				$i = 0;
				foreach ($params as $key => $value) { 
                    if ($value['show_in_edit_form'] == true ){
						$row_style = ($i%2 == 1)?"rowOdd":"rowEven";
						$i++;
						?>
						<tr>
						<td class="<?=$row_style?>">
							<b><?=$value['name']?></b>
						</td>
						<td class="<?=$row_style?>">
							<?php	switch ($value['type']) {
								case "checklist":
									$text = "";
									foreach (get_checklist($key, $resultset[0][$key], true) as $check_el) {
										if ($text != "") $text .= $value['separator'];
										$text .= $check_el;
									}
									break;
								case "combo":
									$text = isset($resultset[0][$key]) ? $combo_cache[$key][ $resultset[0][$key] ]['display'] : "";
									break;								
								case "text":
									$text = $resultset[0][$key];
									break;
								case "textarea":
									$text = $resultset[0][$key];
									break;
								case "dropdown":
									$text = array_search($resultset[0][$key], $value['options']);
									break;
                                default:
                                    $text = $resultset[0][$key];
								}
								if (isset($value['value_wrapper_func']))
									echo $value['value_wrapper_func']( $key, $text, $resultset[0] );
								else
									echo $text;
							?>
						</td>

					</tr>
					<?php } ?>
				<?php } ?>
				</table>

			</td>
		</tr>

		<tr>
			<td align="center">
				<? print_back_input(); ?>
			</td>
		</tr>
	

	</table>

