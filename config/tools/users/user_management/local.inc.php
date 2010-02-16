<?php
/*
 * $Id: local.inc.php 40 2009-04-13 14:59:22Z iulia_bublea $
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

 
 //database tables
 $config->table_users = "subscriber";
 $config->table_location = "location";
 $config->results_per_page = 10;
 $config->results_page_range = 10;

 $talk_to_this_assoc_id = 1 ;

// the array containing the alias tables
 $config->table_aliases = array("DBaliases"=>"dbaliases");


 //Password can be saved in plain text mode by setting $config->passwd_mode to 0 or chyphered mode, by setting it to 1
 $config->passwd_mode=1;
?>
