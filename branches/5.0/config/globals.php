<?php
/*
 * $Id$
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

// Global configuration parameters used by more then one module


// parameter used for the aliases tables if there are more than 
// the standard dbaliases table. The defined array has as key the
// label and as value the table name.For defining more than one
// attribute/value pair, complete the list with identical elements
// separated by comma.



// The permissions parameter is also used by more the one module,
// when setting the modules permissions for a certain admin.
// This array has 2 values that will remain unchanged: read-only 
// and read-write.

$config->permissions = array("read-only","read-write");

// Password can be saved in plain text mode by setting 
// $config->admin_passwd_mode to 0 or chyphered mode, by setting it to 1
$config->admin_passwd_mode=1;

?>
