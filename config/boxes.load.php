<?php
/*
 * Copyright (C) 2011-2021 OpenSIPS Project
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

require("".__DIR__."/../web/tools/admin/box_config/boxes.params.php");
require("".__DIR__."/../web/tools/admin/system_config/systems.params.php");
require("".__DIR__."/../web/tools/admin/box_config/lib/db_connect.php");

if (!isset($boxes)) {
    if (!isset($_SESSION['boxes'])) {
        $sql = 'select * from boxes_config';
        $stm = $link->prepare($sql);
        if ($stm === false) {
            die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
        }
        $stm->execute( array() );
        $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultset as $elem) {
            $box_id = $elem['id'];
            $_SESSION['boxes'][$box_id]['id'] = $box_id;
            foreach ($config->boxes as $param => $attr)
                $_SESSION['boxes'][$box_id][$param] = $elem[$param];
        }
    }
    foreach ($_SESSION['boxes'] as $elem) {
        $box_id = $elem['id'];
        $boxes[$box_id]['mi']['conn']=$elem['mi_conn'];
        $boxes[$box_id]['monit']['conn']=$elem['monit_conn'];
        $boxes[$box_id]['monit']['user']=$elem['monit_user'];
        $boxes[$box_id]['monit']['pass']=$elem['monit_pass'];
        $boxes[$box_id]['monit']['has_ssl']=$elem['monit_ssl'];
        $boxes[$box_id]['desc']=$elem['desc'];
        $boxes[$box_id]['assoc_id']=$elem['assoc_id'];
        $boxes[$box_id]['smonitor']['charts']=$elem['smonitcharts'];
        foreach ($config->boxes as $param => $attr)
            $boxes[$box_id][$param] = $elem[$param];
    }
}

require("".__DIR__."/../web/tools/admin/system_config/lib/db_connect.php");
if (!isset($systems)) {
    if (!isset($_SESSION['systems'])) {
        $sql = 'select * from system_config';
        $stm = $link->prepare($sql);
        if ($stm === false) {
            die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
        }
        $stm->execute( array() );
        $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultset as $elem) {
            $system_id = $elem['assoc_id'];
            $_SESSION['systems'][$system_id]['assoc_id'] = $elem['assoc_id'];   
            foreach ($config->systems as $param => $attr)
                $_SESSION['systems'][$system_id][$param] = $elem[$param];
        }
    }
    foreach ($_SESSION['systems'] as $elem) {
        $system_id = $elem['assoc_id'];
        $systems[$system_id]['name'] = $elem['name'];
        $systems[$system_id]['desc'] = $elem['desc'];
        $systems[$system_id]['assoc_id'] = $elem['assoc_id'];
        foreach ($config->systems as $param => $attr)
            $systems[$system_id][$param] = $elem[$param];
    }
}


?>
