<?php
/*
 * Copyright (C) 2019 OpenSIPS Project
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
$set_cache = array();

$dialplan_group_mode = get_settings_value("dialplan_groups_mode");
$dialplan_group = get_settings_value("dialplan_groups");
switch ($dialplan_group_mode) {
	case "static":
		break;
	case "input":
		form_generate_input_text("Dialplan ID", "The dailplan ID (as number)",
			"dpid", "n", $dp_form['dpid'], 128, "^[0-9]+$");
		break;
	case "database":
		$query = "SELECT " . $dialplan_group['id'] . " AS id, " .
			$dialplan_group['name'] . " AS name " .
			"FROM " . $dialplan_group['table'];

		$set_values = array();
		// fetch only the subset we need  for the groups that might match
		$stm = $link->prepare($query);
		if ($stm===FALSE) {
			die('Failed to issue query [' . $query . '], error message : ' . $link->errorInfo()[2]);
		}
		$stm->execute($set_values);
		$results = $stm->fetchAll();
		foreach ($results as $key => $value)
			$set_cache[$value['id']] = $value['name'];
		/* fallback */

	case "array":
		if ($dialplan_group_mode == "array")
			$set_cache = $dialplan_group;
		form_generate_select("Dialplan ID", "The dialplan ID of the rule",
			"dpid", "n", $dp_form['grp'],
			array_keys($set_cache), array_values($set_cache));
		break;
}

form_generate_input_text("Rule priority", "Priority value/level assigned to the rule (higher priorities have higher numbers)",
	"pr", "n", $dp_form['pr'], 128, "^[0-9]+$");

form_generate_select("Matching operator", "What method will be used for matching",
	"match_op", 128, $dp_form['match_op'], array("1", "0"), array("REGEX", "EQUAL"));

form_generate_input_text("Matching Regular Expression", "Regular expresion used to match and select this rule",
	"match_exp", "n", $dp_form['match_exp'], 128, "^[^@]+$");

form_generate_select("Matching Flags", "Flags",
	"match_flags", 128, $dp_form['match_flags'], array("0", "1"), array("case sensitive", "case insensitive"));

form_generate_input_checkbox("Match Only", "Indicates whether the rule is only used to match, and not modify the input",
	"match_only", true, $dp_form['match_only'], "onclick='toggleChecked();'");

form_generate_input_text("Substitution Regular Expression", "Regular expression to be used for the substitution",
	"subst_exp", "y", $dp_form['subst_exp'], 128, "^[^@]+$");

form_generate_input_text("Replacement Expression", "What should be the substitution result",
	"repl_exp", "y", $dp_form['repl_exp'], 128, "^[^@]+$");

if ( !isset($dialplan_attributes_mode) || $dialplan_attributes_mode==1 ) {
	form_generate_input_text("Attributes", "Attributes (as string) attached to this rule",
		"attrs", "y", $dp_form['attrs'], 128, NULL);
} else {
	foreach( get_settings_value("attrs_cb") as $id => $val ) {
		$checked = ( strpos($dp_form['attrs'], (string)$id) === FALSE ) ? 0 : 1;
		form_generate_input_checkbox("Attribute '".$val."'", "Script attribute", "dp_attr_".$id, $val, $checked);
	}
}
?>
<script>
function getFormRow(name) {
	var el = document.getElementById(name);
	var firstTr = false;
	while (true) {
		if (el.tagName.toLowerCase() == "tr") {
			if (firstTr)
				return el;
			firstTr = true;
		}
		el = el.parentNode;
	}
	return el;
}
function toggleChecked() {
	var mode = (document.getElementById("match_only").checked?"none":"");
	getFormRow("subst_exp").style.display = mode;
	getFormRow("repl_exp").style.display = mode;
}
toggleChecked();
</script>
