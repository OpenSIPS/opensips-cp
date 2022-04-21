<?php
/*
* Copyright (C) 2022 OpenSIPS Project
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

$domains = get_domains("user_management", false);
$groups = get_settings_value("grps");


form_generate_input_text("Username", "The name of the user", "username",
	"n", $grp_form['username'], 128, "^[a-zA-Z0-9&=+$,;?/%]+$");

form_generate_select("Domain", "Users's domain", "domain", 200,
	$grp_form['domain'], $domains);

form_generate_select("Group", "The name of the group to add the user to",
	"group", 64, $grp_form['group'], array_values($groups), array_values($groups) );

?>
