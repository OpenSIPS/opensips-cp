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

form_generate_input_text("Dialplan ID", "The dailplan ID (as number)",
	"dpid", "n", $dp_form['dpid'], 128, "^[0-9]+$");

form_generate_input_text("Rule priority", "Priority value/level assigned to the rule (higher priorities have higher numbers)",
	"pr", "n", $dp_form['pr'], 128, "^[0-9]+$");

form_generate_select("Matching operator", "What method will be used for matching",
	"match_op", 128, $dp_form['match_op'], array("1", "0"), array("REGEX", "EQUAL"));

form_generate_input_text("Matching Regular Expression", "Regular expresion used to match and select this rule",
	"match_exp", "n", $dp_form['match_exp'], 128, "^[^@]+$");

form_generate_select("Matching Flags", "Flags",
	"match_flags", 128, $dp_form['match_flags'], array("0", "1"), array("case sensitive", "case insensitive"));

form_generate_input_text("Substitution Regular Expression", "Regular expression to be used for the substitution",
	"subst_exp", "y", $dp_form['subst_exp'], 128, "^[^@]+$");

form_generate_input_text("Replacement Expression", "What should be the substitution result",
	"repl_exp", "y", $dp_form['repl_exp'], 128, "^[^@]+$");

if ( !isset($dialplan_attributes_mode) || $dialplan_attributes_mode==1 ) {
	form_generate_input_text("Attributes", "Attributes (as string) attached to this rule",
		"attrs", "y", $dp_form['attrs'], 128, NULL);
} else {
	foreach( $config->attrs_cb as $id => $val ) {
		$checked = ( strpos($dp_form['attrs'], (string)$id) === FALSE ) ? 0 : 1;
		form_generate_input_checkbox("Attribute '".$id."'", "Script attribute", "dp_attr_".$id, $val, $checked);
	}
}
?>
