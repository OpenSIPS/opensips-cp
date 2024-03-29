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

form_generate_input_text("SIP Domain", "A SIP Domain to be considered local by OpenSIPS - can be an IP or a FQDN",
	"domain", "n", $domain_form['domain'], 128, "^(([0-9]{1,3}\\\.[0-9]{1,3}\\\.[0-9]{1,3}\\\.[0-9]{1,3})|(([A-Za-z0-9-]+\\\.)+[a-zA-Z]+))$");
if ($has_attrs) {
	form_generate_input_text("Attributes", "Attributes assigned to the domain",
		"attrs", "y", $domain_form['attrs'], 128, get_settings_value("attributes_regex"));
}
?>
