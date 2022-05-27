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

require_once("../../../common/forms.php");
require_once("../../../tools/system/domains/lib/functions.inc.php");
require_once("lib/functions.inc.php");

form_generate_input_text("Username", "The name of the user", "uname",
	"n", $um_form['username'], 128, get_settings_value("user_format"));

$domains = get_domains("user_management", false);
form_generate_select("Domain", "Users's domain", "domain", 200,
	$um_form['domain'], $domains);

foreach (get_settings_value("subs_extra") as $key => $value) {
	if (($um_edit && $value['show_in_edit_form'] == false) ||
		(!$um_edit && $value['show_in_add_form'] == false))
		continue;
	if (!isset($value['type']))
		$value['type'] = "text";
	switch($value['type']) {
	case "text":
		if (isset($um_form['extra_'.$key]) && $um_form['extra_'.$key] != "")
			$display = $um_form['extra_'.$key];
		else
			$display = $value['default_value'];
		form_generate_input_text($value['header'], $value['info'],
			"extra_".$key, (isset($value['is_optional'])?$value['is_optional']:'y'),
			$display, 128, $value['validation_regex']);
		break;
	case "combo":
		$options = get_combo_options($value);
		form_generate_select($value['header'], $value['info'],
			"extra_".$key, 205, $um_form['extra_'.$key], array_keys($options),
			array_map('array_shift', array_values($options)),
			($value['is_optional']=="y"));
		break;
	}
}

form_generate_passwords('passwd', $um_form['passwd'], $um_form['confirm_passwd'],
	 6, "User's ".($um_edit?"New":"")." Password", ($um_edit?"y":"n"));
?>
