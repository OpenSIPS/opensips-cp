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
 
 require("../config/local.inc.php");
 require("../config/modules.inc.php");
 require("../config/session.inc.php");
?>

<html>

<head>
 <link href='style.css' type='text/css' rel='StyleSheet'>
</head>

<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0">
<table class="headerTable" width="100%" height="30">
<tr>
  <td align="left">
    <?php
      if (file_exists("../web/version.txt")) {
        ob_start();
        include "../web/version.txt";
        $ocp_version = ob_get_clean();
      }
    ?>
    <div class="headerTitle"><?=$header_title.(empty($ocp_version)?"":(" ".$ocp_version))?></div>
  </td>
  <td align="right">
    <table>
      <tr>
	<td align="right"> 
  <?php 
  if ($_SESSION['user_priv'] == '*') {
  ?>
  <select class="custom-select" name="admin_list" id="admin_list" onChange="el=document.getElementById('admin_list'); top.frames['main_body'].location.href=el.value; el.value ='#'" >
  <option hidden disabled selected value="#">Admin tools</option>
  <?php
    foreach ($config_admin_modules as $key=>$val)
        if ($val['enabled'] == true)
          echo("<option value='tools/admin/".$key."/index.php'>".$val['name']."</option>");
    
    echo("</select>");
  }
  ?>
  </td>
	<td align="right">
	  <a href="logout.php" target="_parent" class="headerLogout" id="menu_logout">Logout</a>
        </td>
       </tr>
    </table>
  </td>
 </tr>
</table>
</body>

</html>
