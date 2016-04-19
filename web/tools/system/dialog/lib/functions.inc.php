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
function print_profile() {
require ("../../../../config/tools/system/dialog/local.inc.php");
	global $config;

        foreach ($config->profile_list as $v) {
                $options[]=array("label"=>$v,"value"=>$v);
        }

        $start_index = 0;
        $end_index = sizeof($options);
?>
 <select name="profile" id="profile" size="1" style="width: 175px" class="dataSelect">
 <?php
  for ($i=$start_index;$i<$end_index;$i++)
  {
        echo('<option value="'.$options[$i]['label']. '"> '.$options[$i]['label'].'</option>');
   }
 ?>
 </select>
 <?php
 }

?>
