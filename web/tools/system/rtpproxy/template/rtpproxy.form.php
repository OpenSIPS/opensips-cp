<?php
/*
* Copyright (C) 2018 OpenSIPS Project
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

form_generate_input_text("RTPProxy Socket", "The RTPProxy's socket used by OpenSIPS to communicate with RTPProxy",
	"rtpproxy_sock", "n", $rtpp_form['rtpproxy_sock'], 192, "^.*$");

form_generate_input_text("Set ID", "The numerical ID of set to contain this new RTPProxy socket",
	"set_id", "n", $rtpp_form['set_id'], 128, "^[0-9]+$");

?>
