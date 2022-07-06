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

if (isset($form_error) && $form_error!="")
	echo '<div class="formError" ><strong>Error </strong>'.$form_error.'</div>';
else if (isset($success) && $success!="")
	echo '<div class="formSuccess" ><strong>Success </strong>'.$success.'</div>';
?>
			<form id="addnewentry" action="<?=$page_name?>?action=add_verify" method="post">
				<table width="400" cellspacing="2" cellpadding="2" border="0">
					<tr align="center">
						<td colspan="2" class="mainTitle">
							Add New Entry
						</td>
					</tr>
				<?php foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) { ?>	
					<?php if ($value['show_in_add_form'] == true ){ ?>
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
						$validate=" opt='".$opt."' oninput='validate_input(\"".$key."\", \"".$key."_ok\",".$regex.")'";
						?>
						<td class="dataRecord" width="275">
							<table style="width:100%"><tr><td>
							<?php switch ($value['type']) { 
								case "text": ?>
									<input 	id="<?=$key?>" 
										name="<?=$key?>" 
										class="dataInput" 
										type="text" 
										value="<?=(isset($_SESSION[$key]))?$_SESSION[$key]:((isset($value['default_value']))?$value['default_value']:"")?>" 
										<?=$validate?>
									/> 
									<?php break; ?>	
							<?php 	case "combo": ?>
									<?php print_custom_combo($key,$value, $value['default_value'], FALSE); ?>
									<?php break; ?>	
							<?php case "textarea": ?>
								<textarea id="<?=$key?>" 
									name="<?=$key?>" 
									class="dataInput" 
									style="height:100px"
									<?=$validate?>
									></textarea>
									<?php break; ?>	
							<?php case "checklist": ?>
									<?php print_custom_checklist($key, $value, $value['default_value']); ?>
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
							<input type="submit" name="add" value="Add" class="formButton">
							<input type="button" value="Reset" class="formButton" href="javascript:;" onclick="document.getElementById('addnewentry').reset();">
							<?php print_back_input(); ?>
						</td>
					</tr>

			</table>
			<script> form_init_status(); </script>
			</form>

