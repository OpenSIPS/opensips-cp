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

$gateways_types = get_gw_types();
form_generate_input_text("Gateway ID", "The name of the gateway",
	"gwid", "n", $dr_form['gwid'], 128, "^[a-zA-Z0-9_\-]+$");

if (count($gateways_types) > 1) {
	form_generate_select("GW Type", "Gateways's type",
		"type", 128, $dr_form['type'], array_keys($gateways_types), array_values($gateways_types));
}

form_generate_input_text("SIP Address", "SIP address of the gateway, in the format of IP[:port]",
	"address", "n", $dr_form['address'], 128, "^.*$");

form_generate_input_text("Strip", "Number of digits to be stripped when sending a call to the gateway",
	"strip", "y", $dr_form['strip'], 128, "^[0-9]+$");

form_generate_input_text("PRI Prefix", "The prefix to be added to the called number when sending a call to the gateway",
	"pri_prefix", "y", $dr_form['pri_prefix'], 128, "^.*$");

form_generate_select("Probe Mode", "Indicates the probing mode for the gateway. use only the first gateway",
	"probe_mode", 128, $dr_form['probe_mode'], array(0, 1, 2), array("0 - Never", "1 - When disabled", "2 - Always"));

$gw_sockets = get_settings_value("sockets");
if ($gw_sockets == "") {
	form_generate_input_text("Socket", "The OpenSIPS' socket to be used when sending a call to the gateway",
		"socket", "y", $dr_form['socket'], 128, "^(sctp|tls|udp|tcp):(((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))|((([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])))(:([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5]))?$");
} else {
	form_generate_select("Socket", "The OpenSIPS' socket to be used when sending a call to the gateway",
		"socket", 128, $dr_form['socket'], array_values($gw_sockets), array_keys($gw_sockets));
}

form_generate_select("DB State", "Whether the gateways should be active, inactive or probing",
	"state", 128, $dr_form['state'], array(0, 1), array("0 - Active", "1 - Inactive", "2 - Probing"));

$gw_attributes_mode = get_settings_value("gw_attributes_mode");
$gw_attributes = get_settings_value("gw_attributes");
if ($gw_attributes_mode == "input") {
    form_generate_input_text((isset($gw_attributes["display_name"])?$gw_attributes["display_name"]:"Attributes"),
			       "attributes used for the gw",
			       "attrs",
			       "y",
			       (isset($resultset[0]['attrs'])?$resultset[0]['attrs']:$gw_attributes["add_prefill_value"]),
			       128,
			       $gw_attributes["validation_regexp"]);
} else if ($gw_attributes_mode == "params") {
	$attr_map = dr_get_attrs_map($resultset[0]['attrs']);
	foreach ($gw_attributes as $key => $value) {
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

form_generate_input_text("Description", "Arbitrary description of the gw",
	"description", "y", $dr_form['description'], 128, "^.*$");
?>
