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

form_generate_input_text("Group ID", "The numerical ID of the balancer set/group for the new destination",
	"group_id", "n", $lb_form['group_id'], 128, "^[0-9]+$");

form_generate_input_text("SIP URI", "SIP URI pointing to the destination",
	"dst_uri", "n", $lb_form['dst_uri'], 192, "^$re_sip_uri$");

form_generate_input_text("Resources", "The list of resources (ans their capacity) offered by this destination. It can be a list semicolon separate list of name=value (name is alphanumerical and value is numerical) or a single resource element pointing to a FreeSWITCH URL (name=fs://[username]:password@host[:port])",
	"resources", "n", $lb_form['resources'], 256, "^((([a-zA-Z0-9]+(/[bs])?=[0-9]+)(;[a-zA-Z0-9]+(/[bs])?=[0-9]+)*)|([a-zA-Z0-9]+(/[bs])?=$re_fs_url))$");

form_generate_select("Probe Mode", "When the destination should be probed/pinged via SIP messages (for availability)",
	"probe_mode", 200, $lb_form['probe_mode'], array("0","1","2"),$lb_probing_modes);

form_generate_input_text("Attributes", "String of custom Attributes, to be passed to the OpenSIPS script",
	"attrs", "y", $lb_form['attrs'], 128, null);

form_generate_input_text("Description", "Description in DB, not used by OpenSIPS",
	"description", "y", $lb_form['description'], 128, null);
?>
