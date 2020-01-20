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

 if (!isset($config))
   $config = new stdClass();

 $config->results_per_page = 20;
 $config->results_page_range = 5;
 
###############################################################################

 //database tables
 $config->table_dispatcher = "dispatcher";

 // system to talk with for MI part 
 $talk_to_this_assoc_id = 1 ;

 //status
 $config->status = array('Active'=>'Active','Inactive'=>'Inactive','Probing'=>'Probing');

/*
 * Using this method one can define a mapping between the dispatcher groups and their names.
 * These names will be displayed in the main page, as well in the add and edit forms.
 * The following config presumes that a ds_mappings table exists with two fields:
 * - id: stores the dispatcher id
 * - name: stores the name of the dispatcher id
 *
 $config->dispatcher_groups = array(
	 'type'		=> 'database', // keyword to determine type
	 'table'	=> 'ds_mappings',
	 'id'		=> 'id',
	 'name'		=> 'name',

/*
 * Using this method one can define static groups, instead of db ones
 *
	 'type'		=> 'array',
	 'array'	=> array(
		 "2" 	=> "Group 1",
		 "4" 	=> "Group 2",
	 ),
 );
 */
?>
