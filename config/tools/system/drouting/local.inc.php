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


 $config->results_per_page = 20;
 $config->results_page_range = 5;
 
 # Gateways
 // default gateway type
 $config->default_gw_type = 1;
 
 
 # Rules
 // "static" (from file) or "dynamic" (from table) group ids 
 $config->group_id_method = "static";
 
 
 # Groups
 // default domain
 $config->default_domain = "yourdomain.net";

 # Partition used to query status - if not set, or "", default is used
 $config->routing_partition = "";

###############################################################################

 //database tables
 $config->table_gateways = "dr_gateways";
 $config->table_groups = "dr_groups";
 $config->table_rules = "dr_rules";
 $config->table_carriers = "dr_carriers";
 
 $talk_to_this_assoc_id = 1 ;

 $config->gw_attributes = array(
	 "display_name" => "Attributes",
	 "add_prefill_value" => "",
	 "validation_regexp" => NULL,
	 "validation_error" => NULL,
 );

?>
