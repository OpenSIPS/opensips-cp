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

require("template/header.php");
require("lib/".$page_id.".main.js");
require ("../../../common/mi_comm.php");

$current_page="current_page_dialog";

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";
if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

################
# start load   #
################
if ($action=="load") {
	extract($_POST);
	$profile = $_POST['profile'];
        $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
        for ($i=0;$i<count($mi_connectors);$i++){
                $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
                // get status from the first one only
                $comm_type=mi_get_conn_params($mi_connectors[0]);
                $message=mi_command("profile_list_dlgs $profile" , $errors , $status);
                print_r($errors);
                $status = trim($status);
	}
$_SESSION['message']=$message;

}

##############
# end load   #
##############


################
# start delete #
################

if ($action=="delete")
{
        if(!$_SESSION['read_only']){

                $h_entry=$_GET['h_entry'];
                $h_id=$_GET['h_id'];
                $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
                for ($i=0;$i<count($mi_connectors);$i++){

                        $comm_type=mi_get_conn_params($mi_connectors[$i]);
                        mi_command("dlg_end_dlg ".$h_entry." ".$h_id,$errors,$status);
                        print_r($errors);
                        $status = trim($status);
                }
        }else{

                $errors= "User with Read-Only Rights";
        }
}
##############
# end delete #
##############



##############
# start main #
##############

require("template/".$page_id.".main.php");
if($errors)
echo('!!! ');echo($errors);
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
