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


require_once("../../../../config/tools/system/drouting/db.inc.php");
require_once("../../../../config/db.inc.php");
require_once("MDB2.php");
        global $config;
        if (isset($config->db_host_drouting) && isset($config->db_user_drouting) && isset($config->db_name_drouting) ) {
                $config->db_host = $config->db_host_drouting;
                $config->db_port = $config->db_port_drouting;
                $config->db_user = $config->db_user_drouting;
                $config->db_pass = $config->db_pass_drouting;
                $config->db_name = $config->db_name_drouting;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::factory($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
