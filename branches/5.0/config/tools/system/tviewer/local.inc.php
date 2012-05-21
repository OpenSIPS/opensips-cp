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

 
 $config->results_per_page = 20;
 $config->results_page_range = 10;

 $config->custom_table = "n2h_masq";

 $config->custom_table_columns = array ("Id" => "id","Original Contact" => "orig_uri","Masq Contact" => "masq_uri","Source IP" => "dst_uri","Timeout" => "timeout");

 $config->custom_table_primary_key = "id";

 // what system to talk to for MI functions
 $talk_to_this_assoc_id = 1 ;

?>
