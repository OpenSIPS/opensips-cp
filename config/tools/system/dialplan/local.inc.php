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

###############################################################################
# Attention : advanced options !!

 
 //database tables
 $config->table_dialplan = "dialplan";
 $config->results_per_page = 20;
 $config->results_page_range = 5;

 $config->attrs_cb=array(
					// name , description
					array("a","Descr a"),
					array("b","Descr b"),
					array("c","Descr c"),
					array("d","Descr d"),
					array("e","Descr e"),
					array("f","Descr f"),
					);
 $config->cb_per_row = 3;
 $talk_to_this_assoc_id = 1 ;

// Dialplan - Add/Edit new Translation Rule - Attributes input type 
// 0 - checkboxes 
// 1 - text
$dialplan_attributes_mode = 1 ;
 
?>
