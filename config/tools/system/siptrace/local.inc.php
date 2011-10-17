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
 
 $config->results_per_page = 25;
 $config->results_page_range = 10;
 
 // highlighting 
 $config->from_color="black";	   $config->from_bgcolor="yellow";
 $config->to_color="white";      $config->to_bgcolor="blue";
 $config->callid_color="black";  $config->callid_bgcolor="orange";
 $config->cseq_color="white";	   $config->cseq_bgcolor="navy";
 $config->regexp_color="navy";   $config->regexp_bgcolor="red";

###############################################################################
  
 //database tables
 $config->table_trace = "sip_trace";
 
 $talk_to_this_assoc_id = 1 ; 
 
 // sip proxy - ip:port
 $proxy_list=array("udp:78.46.64.50:5060","tcp:78.46.64.50:5060");
 
?>
