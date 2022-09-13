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

require_once("../../forms.php");

$id=$_GET[$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']];

$sql = "select * from ".$table." where ".$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_primary_key']."=?";

$stm = $link->prepare($sql);
if ($stm->execute(array($id)) === false)
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
	if (isset($form_error) && $form_error!="")
		echo '<div class="formError" ><strong>Error </strong>'.$form_error.'</div>';
	else if (isset($success) && $success!="")
		echo '<div class="formSuccess" ><strong>Success </strong>'.$success.'</div>';
?>

			<form id="editentry" class="block-content form" action="<?=$page_name?>?action=modify&id=<?=$id?>" method="post">
				<table width="400" cellspacing="2" cellpadding="2" border="0">
					<tr align="center">
						<td colspan="2" class="mainTitle">
							Edit Entry
						</td>
					</tr>
				<?php foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) { ?>	
					<?php if ($value['show_in_edit_form'] == true ){ ?>
					<tr>
						<td class="dataRecord">
							<label for="<?=$key?>"><b><?=$value['header']?></b></label>
							<?php if (isset($value['tip']) && $value['tip']!="") { ?>
							<div class='tooltip'><sup>?</sup>
							<span class='tooltiptext'><?=$value['tip']?></span>
							</div>
							<?php } ?>
						</td>
						<?php if (!isset($value['validation_regex']))
							$regex = "null";
						else
							$regex = '"'.$value['validation_regex'].'"';
						$opt = isset($value['is_optional'])?$value['is_optional']:"y";
						$validate=" opt='".$opt."' valid='ok' oninput='validate_input(\"".$key."\", \"".$key."_ok\",".$regex.")'";
						?>
						<td class="dataRecord" width="275">
							<table style="width:100%"><tr><td>
							<?php switch ($value['type']) { 
								case "text": ?>
									<input 	id="<?=$key?>" 
										name="<?=$key?>" 
										class="dataInput" 
										type="text" 
										value="<?php if (isset($_SESSION[$key])) echo $_SESSION[$key]; else echo $resultset[0][$key];?>" 
										<?=$validate?>
									/> 
									<?php break; ?>	
							<?php 	case "combo": ?>
									<?php print_custom_combo($key, $value, isset($resultset[0][$key])?$resultset[0][$key]:$value['default_value'], FALSE); ?>
									<?php break; ?>	
							<?php case "textarea": ?>
								<textarea id="<?=$key?>" 
									name="<?=$key?>" 
									class="dataInput" 
									style="height:100px"
									<?=$validate?>
									><?php if (isset($_SESSION[$key])) echo $_SESSION[$key]; else echo $resultset[0][$key];?></textarea>
									<?php break; ?>	
							<?php case "checklist": ?>
									<?php print_custom_checklist($key, $value, isset($resultset[0][$key])?$resultset[0][$key]:$value['default_value']); ?>
									<?php break; ?>
							<?php } ?>
							</td>
							<td width='20'>
							<?php echo("<div id='".$key."_ok'></div>"); ?>
							</td></tr></table>


						</td>
					</tr>
						<?php } ?>
				<?php } ?>

					<tr>
						<td colspan="2" class="dataRecord" align="center">
							<input type="submit" name="add" value="Update" class="formButton">
							<input type="button" value="Reset" class="formButton" onclick="window.location.href='tviewer.php?action=edit&id=<?=$id?>'">
							<?php print_back_input(); ?>
						</td>
					</tr>

				</tr>
			</table>
			<script> form_init_status(); </script>
			</form>

