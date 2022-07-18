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

require_once("../../../common/forms.php");
require_once("../../../common/cfg_comm.php");
require_once("../../../../config/boxes.global.inc.php");
require_once("functions.js");
	if (isset($form_error)) {
		echo(' <tr align="center">');
		echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
		echo(' </tr>');
	}
	
	unset($box_id);
	if (isset($_GET['box_id']))
		$box_id=$_GET['box_id'];
	else $box_id = null;

	$current_tool=$_SESSION['current_tool'];
	$current_tool_name = get_tool_name();

    $index_row=0;
	$permissions=array();
?> 

<form action="<?=$page_name?>?action=modify_params&tool=<?=$current_tool?>&box_id=<?=$box_id?>" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
	 <?php   
	 if(!isset($box_id)) { ?>
 <td colspan="3" height="10" class="mainTitle">Settings for <?=$current_tool_name?></td>
 <?php  } else { ?>
 <td colspan="3" height="10" class="mainTitle">Settings for <?=$current_tool_name?> for <?=$boxes[$box_id]['desc']?></td>
 <?php } ?>
 </tr>
  <?php
	$tools_params=get_params();
	
	foreach ($tools_params as $module=>$params) {
		if (isset($params['opt'])) $opt = "y"; else $opt = "n";
		if (isset($params['tip']))
			$current_tip = $params['tip'];
		else $current_tip = null;
		switch ($params['type']) {
			case "checklist":
				if (isAssoc($params['options']))
				form_generate_checklist($params['name'], $current_tip, $module, 100,  explode(",", get_settings_value($module, $box_id)), array_values($params['options']), array_keys($params['options']));
				else form_generate_input_checklist($params['name'], $current_tip, $module, 100, explode(",", get_settings_value($module, $box_id)), array_value($params['options']));
				break;
			case "json":
				$flags = JSON_PRETTY_PRINT;
				$validation = "validate_json";
				if (isset($params['json_format']) && $params['json_format'] == "object")
					$flags |= JSON_FORCE_OBJECT;
				form_generate_input_textarea($params['name'], $current_tip, $module, $opt, json_encode(get_settings_value($module, $box_id), $flags), (isset($params['maxlen'])?$params['maxlen']:NULL), (isset($params['validation_regex'])?$params['validation_regex']:NULL), $validation, (isset($params['json_format'])?$params['json_format']:NULL));
				break;
			case "dropdown": 
				if (isAssoc($params['options']))
					form_generate_select($params['name'], $current_tip, $module, 100,  get_settings_value( $module, $box_id), array_values($params['options']), array_keys($params['options']));
				else form_generate_select($params['name'], $current_tip, $module, 100,  get_settings_value( $module, $box_id), array_values($params['options']));
				break;
			case "title":
					print '<tr> <td class=\'sectionTitle\'><b>'.$params['title'].'</b></td></tr>';
				break;
			default:
				form_generate_input_text($params['name'], $current_tip, $module, $opt, get_settings_value($module, $box_id), 100,(isset($params['validation_regex'])?$params['validation_regex']:NULL));
		}
		if (isset($params['example'])) {
			print_example($params['example'], $params['name'], $module);
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

