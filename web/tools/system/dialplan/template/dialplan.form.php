<?php
/*
* Copyright (C) 2017 OpenSIPS Project
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

form_generate_input_text("Dialplan ID", "The number that identifies the dailplan",
						 "dpid", "n", $ds_form['dpid'], 128, "^[0-9]+$");

form_generate_input_text("Rule priority", "Integer value assinged to the rule (higher priorities have higher numbers)",
						 "pr", "n", $ds_form['pr'], 128, "^[0-9]+$");

form_generate_select("Matching operator", "What method will for matching",
					 "match_op", 128, $ds_form['match_op'], array("0", "1"), array("REGEX", "EQUAL"));

form_generate_input_text("Matching Regular Expression", "What should be matched",
						 "match_exp", "n", $ds_form['match_exp'], 128, "^[^@]+$");

form_generate_input_text("Matching Flags", "Flags",
						 "match_flags", "y", $ds_form['match_flags'], 128, "^[^@]+$");

form_generate_input_text("Substitution Regular Expression", "What should be matched for the substitution",
						 "subst_exp", "n", $ds_form['subst_exp'], 128, "^[^@]+$");

form_generate_input_text("Replacement Expression", "What should be the substitution result",
						 "repl_exp", "y", $ds_form['repl_exp'], 128, "^[^@]+$$");

if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {
	//TODO: read names and description from $config->attrs_cb array
	form_generate_select("Attributes", "Assign an attribute to the dialplan id",
						 "attrs", 128, $ds_form['attr'], array("a", "b", "c", "d"),
						 array("Description A", "Description B", "Description C", "Description D") );
} else if ($dialplan_attributes_mode == 1 ) {
	form_generate_input_text("Attributes", "Assign an attribute to the dialplan id",
							 "attrs", "y", $ds_form['attrs'], 128, "^[^@]+$");
}
?>
