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

form_generate_input_text("Cluster ID", "The numerical ID of the cluster for adding the the new node",
	"cluster_id", "n", $cl_form['cluster_id'], 128, "^[0-9]+$");

form_generate_input_text("Node ID", "The numerical ID of the server/node inside the cluster (note that this ID must be unique across all the clusters the node belongs to)",
	"node_id", "n", $cl_form['node_id'], 128, "^[0-9]+$");

form_generate_input_text("BIN URL", "The Binary INterface URL for reaching the node (like bin:ip:port)",
	"url", "n", $cl_form['url'], 192, "^bin:[^:]+(:[0-9]+)$");

form_generate_input_text("Max retries", "Maximum number of probes/retries before marking other nodes as unreachable",
	"no_ping", "n", $cl_form['no_ping_retries'], 128, "^[0-9]+$");

form_generate_input_text("SIP address", "An IP address where this node is receiving the SIP traffic (for certain scenarios, like Federated User Location)",
	"sip_addr", "y", $cl_form['sip_addr'], 192, $re_ip);

form_generate_input_text("Flags", "Comma separated list of text flags required by modules using the clusterer enging. The only supported right now is 'seed'.",
	"flags", "y", $cl_form['flags'], 128, "^seed$");

form_generate_input_text("Description", "Description in DB, not used by OpenSIPS",
	"description", "y", $cl_form['description'], 128, null);
?>
