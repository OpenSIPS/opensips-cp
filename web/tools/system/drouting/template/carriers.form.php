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

require("../../../common/forms.php");

if ($dr_form["action"] == "add") {
	form_generate_input_text("Carrier ID", "The name of the carrier",
		"carrierid", "n", $dr_form['carrierid'], 128, "^[a-zA-Z0-9_\-]+$");
}
?>
 <tr>
  <td class="dataRecord" ><b>Gateway List</b></td>
   <td class="dataRecord">
	<input type="text" name="gwlist" id="gwlist" value="<?=$dr_form["gwlist"]?>" maxlength="<?=(isset($config->gwlist_size)?$config->gwlist_size:255)?>" readonly class="dataInput" style="width:370!important">
	<input type="button" name="clear_gwlist" value="Clear Last" class="inlineButton" style="width:90px" onclick="clearObject('gwlist')">
   </td>
  </tr>
  <tr>
   <td/>
   <td class="dataRecord">
	<?=print_gwlist()?>
	<input type="text"   name="weight" id="weight" value="" maxlength="5" class="dataInput" style="width:40!important;">
	<input type="button" name="add_gwlist" value="Add" class="inlineButton" style="width:90px" onclick="addElementToObject('gwlist','weight')">
   </td>
  </tr>
<?php
form_generate_select("List Sorting", "How gateways should be sorted inside the carrier",
	"sort_alg", 128, $dr_form['sort_alg'], array_keys($dr_sort_alg), array_values($dr_sort_alg));

form_generate_select("Use only first", "Whether to use only the first gateway",
	"useonlyfirst", 128, $dr_form['useonlyfirst'], array(0, 1), array("0 - No", "1 - Yes"));

form_generate_select("DB State", "Whether the carrier should be initially active or not",
	"state", 128, $dr_form['state'], array(0, 1), array("0 - Active", "1 - Inactive"));

$carrier_attributes_mode = get_settings_value("carrier_attributes_mode");
$carrier_attributes = get_settings_value("carrier_attributes");
if ($carrier_attributes_mode == "input") {
    form_generate_input_text((isset($carrier_attributes["display_name"])?$carrier_attributes["display_name"]:"Attributes"),
			       "attributes used for the carrier",
			       "attrs",
			       "y",
			       (isset($resultset[0]['attrs'])?$resultset[0]['attrs']:$carrier_attributes["add_prefill_value"]),
			       128,
			       $carrier_attributes["validation_regexp"]);
} else if ($carrier_attributes_mode == "params") {
	$attr_map = dr_get_attrs_map($resultset[0]['attrs']);
	foreach ($carrier_attributes as $key => $value) {
		if ($dr_form["action"] == "edit")
			$val = dr_get_attrs_val($attr_map, $key, $value);
		else
			$val =  (isset($value["default"])?$value["default"]:"");
		switch ($value["type"]) {
		case "text":
			form_generate_input_text($value["display"],
				$value["hint"],
				"extra_".$key,
				"y",
				$val,
				128,
				(isset($value["validation_regexp"])?$value["validation_regexp"]:NULL));
			break;
		case "checkbox":
			form_generate_input_checkbox($value["display"],
				$value["hint"],
				"extra_".$key,
				$key,
				$val);
			break;
		case "combo":
			$options = dr_get_combo_attrs($value);
			form_generate_select($value['display'], $value['hint'],
				"extra_".$key, 205, $val, array_keys($options), array_values($options), true);
			break;
		}
	}
}

form_generate_input_text("Description", "Arbitrary description of the carrier",
	"description", "y", $dr_form['description'], 128, "^.*$");
?>
