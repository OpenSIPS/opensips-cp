<!--
 /*
 * $Id:$
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
-->
<?


 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
?>

<div align="right">
<form action="<?=$page_name?>?action=toggle_on" method="post">
        <input type="submit" name="toggle_on" value="Enable Nat Ping" class="formButton" style="background-color: #00ff00 ">
</form>

<form action="<?=$page_name?>?action=toggle_off" method="post">
        <input type="submit" name="toggle_off" value="Disable Nat Ping" class="formButton" style="background-color: #ff0000 ">
</form>
</div>
<?
/*
/*if (!isset($toggle_button)) {

        $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

        // get status from the first one only
        $comm_type=params($mi_connectors[0]);

        mi_command("nh_enable_ping 1" , $errors , $status);
        print_r($errors);
        $status = trim($status);
        if ($status == "on")
        $toggle_button = "disable";

        if ($status == "off")
        $toggle_button = "enable";



}*/

 ?>
