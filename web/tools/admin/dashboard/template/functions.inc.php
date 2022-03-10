<?php
/*
* Copyright (C) 2022 OpenSIPS Solutions
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

    function get_panels_count() {
        return count($_SESSION['config']['panels']);
    }

    function swap_panels($first, $second, $table) {  
        require("../../../../config/tools/admin/dashboard/db.inc.php");
        include("lib/db_connect.php");
        require("../../../../config/db.inc.php");
        require("../../../../config/tools/admin/dashboard/local.inc.php");

        $sql = 'UPDATE '.$table.' SET `order` = 
        CASE
         WHEN `order` = ? THEN ?
         WHEN `order` = ? THEN ?
        END
        WHERE `order` = ? or `order` = ?
        ';
        $stm = $link->prepare($sql);
        if ($stm === false) {
            die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
        }

        if ($stm->execute( array($first, $second, $second, $first, $first, $second)) == false)
            echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
        else {

        }
    }

?>