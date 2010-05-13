<?php
/*
 * $Id$
 * Copyright (C) 2008 Voice Sistem SRL
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

 //$config->profile_list = array("caller_profile" , "pstn");
 $config->profile_list = array("proxyIP");

 $box[1]['mi']['conn']="10.5.7.16:8080";; 

 // what system to talk to for MI functions
 $talk_to_this_assoc_id = 1 ;

?>
