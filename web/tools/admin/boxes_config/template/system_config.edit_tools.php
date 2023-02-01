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

require_once("../../../common/cfg_comm.php");
require_once("../../../common/forms.php");
require("../../../../config/boxes.global.inc.php");
require_once("functions.js");
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	$assoc_id=$_GET['assoc_id'];
	$current_tool=$_SESSION['current_tool'];
	$current_tool_name = get_tool_name();
?> 

<form action="<?=$page_name?>?action=modify_params&tool=<?=$current_tool?>&assoc_id=<?=$assoc_id?>" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
 <td colspan="3" height="10" class="mainTitle">Edit system</td>

 </tr>
  <?php 
	$system_params = get_system_params();
	foreach ($system_params as $attr=>$params) {
		if ($params['show_in_edit_form']) {
      			$opt = (isset($params['opt']) && $params['opt'])?"y":"n";
      			$current_tip = isset($params['tip'])?$params['tip']:NULL;
			$value = $systems[$assoc_id][$attr];	
			$regexp = isset($params['validation_regex'])?$params['validation_regex']:NULL;
			switch ($params['type']) {
				case "checklist":
					if (isAssoc($params['options']))
						form_generate_checklist($params['name'], $current_tip, $attr, 64,  $value, array_values($params['options']), array_keys($params['options']));
					else form_generate_input_checklist($params['name'], $current_tip, $attr, 64, $value, array_value($params['options']));
					break;
				case "json":
					form_generate_input_textarea($params['name'], $current_tip, $attr, $opt, json_encode($value, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT), (isset($params['maxlen'])?$params['maxlen']:NULL), $regexp, 'validate_json');
					break;
				case "dropdown": 
					if (isAssoc($params['options']))
						form_generate_select($params['name'], $current_tip, $attr, 64,  $value, array_values($params['options']), array_keys($params['options']));
					else form_generate_select($params['name'], $current_tip, $attr, 64,  $value, array_values($params['options']));
					break;
				default:
					form_generate_input_text($params['name'], $current_tip, $attr, $opt, $value, 64, $regexp);
			}
		}
	}
	
if (!$_SESSION['read_only']) {
?>
  <tr>
   <td colspan="3">
    <table cellspacing=20>
      <tr>
        <td class="dataRecord" align="right" width="50%"><input type="submit" name="save" value="Save" class="formButton"></td>
		<td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
  </tr>
<?php
 }
?>
  </table>

</form>

