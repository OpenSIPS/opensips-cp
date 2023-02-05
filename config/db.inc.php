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

 global $config;
 if (!isset($config)) $config = new stdClass();

 //database driver mysql or pgsql
 $config->db_driver = "mysql";

 //database host
 $config->db_host = "localhost";
 
 //database port - leave empty for default
 $config->db_port = "";
 
 //database connection user
 $config->db_user = "opensips";
 
 //database connection password
 $config->db_pass = "opensipsrw";
 
 //database name
 $config->db_name = "opensips";

 if (!empty($config->db_port) ) $config->db_host = $config->db_host . ";port=" . $config->db_port;

 //connection attributes, optional
 //$config->db_attr = array(PDO::MYSQL_ATTR_LOCAL_INFILE => true);
 
?>
