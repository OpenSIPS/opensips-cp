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

form_generate_input_text("Registrar", "The URI pointing to the SIP Trunk registrar (eg: sip:sip-trunk.telekom.de)",
						 "registrar", "n", $ds_form['registrar'], 255, "^" . $re_sip_uris . "(:(5060|5061)?)?$");

form_generate_input_text("Proxy", "The URI pointing to the SIP proxy of the registrar (eg: sip:reg.sip-trunk.telekom.de)",
						 "proxy", "n", $ds_form['proxy'], 255, "^" . $re_sip_uris . "(:(5060|5061)?)?$");

// registration mode: get key/value pairs from config file 'local.inc.php'
$keys = array();
$values = array();
foreach($config->registration_mode as $element) {
	array_push($keys, $element[0]);
	array_push($values, $element[1]);
}

// server side: define the selection box
form_generate_select("Registration Mode", "What mode is used to register the SIP Trunk at the registrar",
					 "registrar_mode", 128, $ds_form['registrar_mode'],
					 $keys, $values, "aor" );


// server side: define the input field, client side: preset defaults for tooltip_text and re values
form_generate_input_text("Address of Registrant", "",
						 "aor", "n", $ds_form['aor'], 255, "");

form_generate_input_text("Address of 3rd party registrant", "Address associated to the SIP of the 3rd party registrant",
						 "third_party_registrant", "y", $ds_form['third_party_registrant'], 255, "^" . $re_pstn . "@" . $re_fqdn . "$");

form_generate_input_text("Username", "The username of the registrant",
						 "username", "n", $ds_form['username'], 64, "^[^@]+(.{8,})$");
						 //"^([a-z][A-Z][0-9])+$");

form_generate_input_text("Password", "The password of the registrant",
						 "password", "n", $ds_form['password'], 64, "^[^@]+(.{7,})$");

form_generate_input_text("Binding URI", "The URI the registrar will binding the registrant to (e.g: sips:'PSTN-Nr'@sip-trunk.telekom.de:5060",
						 //"binding_uri", "n", $ds_form['binding_uri'], 255, "^(sip(s)?:" . $re_pstn . "@" . $re_fqdn . "(:(5060|5061)?)?$");
						 "binding_uri", "n", $ds_form['binding_uri'], 255, "^sip(s)?:" . $re_pstn . "@" . $re_uris . "(:(5060|5061)?)?$");

form_generate_input_text("Binding Parameters", "Binding Parameters",
						 "binding_params", "y", $ds_form['binding_params'], 64 , "^sip:([0-9][a-Z]+)$");

form_generate_input_text("Expriry", "Timeout value to revalidated the authentication (in seconds)",
						 "expiry", "y", $ds_form['expiry'], 16, "^([0-9]+)$");

form_generate_input_text("Forced Socket", "The OpenSIPS network listener (as proto:ip:port) to be used for reach the registrar (leave empty if not needed)",
						 "forced_socket", "y", $ds_form['forced_socket'], 64,  "^(tcp|udp):" . $re_ips . "(:[0-9]+)?$");

form_generate_input_text("Cluster shared tag", "Shared tag inside the cluster",
						 "cluster_shtag", "y", $ds_form['cluster_shtag'], 64, "^([0-9]+)$");

?>
