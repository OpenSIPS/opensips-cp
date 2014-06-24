<?
/*
* $Id: tviewer.add.php 133 2009-10-29 18:05:56Z untiptun $
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
	if (isset($form_error) && $form_error!="")
		echo '<div class="formError" ><strong>Error </strong>'.$form_error.'</div>';
	else if (isset($success) && $success!="")
		echo '<div class="formSuccess" ><strong>Success </strong>'.$success.' ('.$back_link.')</div>';
?>

			<form id="addnewentry" class="block-content form" action="<?=$page_name?>?action=add_verify" method="post">
				<table width="400" cellspacing="2" cellpadding="2" border="0">
					<tr align="center">
						<td colspan="2" class="tviewerTitle">
							Add New Entry
						</td>
					</tr>
				<?php foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value) { ?>	
					<?php if ($value['show_in_add_form'] == true ){ ?>
					<tr>
						<td class="dataRecord">
							<label for="<?=$key?>"><?=$value['header']?></label>
						</td>
						<td class="dataRecord" width="275">
							<?php switch ($value['type']) { 
								case "text": ?>
									<input 	id="<?=$key?>" 
										name="<?=$key?>" 
										class="dataInput" 
										type="text" 
										value="<?=(isset($_SESSION[$key]))?$_SESSION[$key]:((isset($value['default_value']))?$value['default_value']:"")?>" 
									/> 
									<?php break; ?>	
							<?php 	case "combo": ?>
									<?php print_custom_combo($key,$value['default_value'],$value['default_display'],$value['combo_default_values'],$value['combo_table'],$value['combo_value_col'],$value['combo_display_col'],$value['disabled']); ?>	
									<?php break; ?>	
							<?php } ?>
						</td>
					</tr>
						<?php } ?>
				<?php } ?>

					<tr>
						<td colspan="2" class="dataRecord" align="center">
							<input type="submit" name="add" value="Add" class="formButton">
							<input type="button" value="Reset" class="formButton" href="javascript:;" onclick="document.getElementById('addnewentry').reset();">
						</td>
					</tr>

					<tr height="10">
						<td colspan="2" class="dataTitle">
						<img src="images/spacer.gif" width="5" height="5">
					</td>
				</tr>
			</table>
			</form>
		<div class="back_link" style="padding-top:25px; width: 50%; margin: 0 auto;text-align: center;">
                        	<?=$back_link?>
        </div>

