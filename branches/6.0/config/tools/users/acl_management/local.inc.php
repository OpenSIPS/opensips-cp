<?php
/*
 * $Id: local.inc.php 210 2010-03-08 18:09:33Z bogdan_iancu $
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
 
 $config->results_per_page = 10;
 $config->results_page_range = 10;

 // the array containing the alias tables
 $config->table_acls = "grp";

 $config->grps = array("grp_one","grp_two","grp_three");
 
 // whats the id of the `system` ?  
 $talk_to_this_assoc_id = 1 ;
 
?>
