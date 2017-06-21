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

require("template/header.php");
require("lib/".$page_id.".main.js");
require ("../../../common/mi_comm.php");
require ("../../../common/cfg_comm.php");
require ("../../../../config/tools/system/loadbalancer/local.inc.php");
include("lib/db_connect.php");

$table=$config->table_lb;
$current_page="current_page_lb";
$lb_probing_modes = array("No probing","On disabled","Permanent");

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

$info="";
$errors="";
 
if ( $_SESSION['read_only'] && 
($action=="add" || $action=="do_add" || $action=="edit" || $action=="modify" || $action=="delete" || $action=="toggle") ) {
	$errors= "User with Read-Only Rights";
} else	

switch ($action) {

#################
# start add new #
#################
case "add":
	extract($_POST);
	require("template/".$page_id.".add.php");
	require("template/footer.php");
	exit();
#################
# end add new   #
#################


################
# start do_add #
################
case "do_add":
	$group_id=$_POST['group_id'];
	$dst_uri=$_POST['dst_uri'];
	$resources=$_POST['resources'];
	$description=$_POST['description'];
	$probe_mode = $_POST['probe_mode'];

	$sql = "INSERT INTO ".$table."
		(group_id, dst_uri,resources,probe_mode,description) VALUES 
		(".$group_id.", '".$dst_uri."','".$resources."',".$probe_mode.",'".$description."') ";

	$result = $link->exec($sql);
       	if(PEAR::isError($result)) {
		$errors = "Add/Insert to DB failed with: ".$result->getUserInfo();
	} else {
		$info="The new LB destination was added";
	}
	$link->disconnect();	

	break;
##############
# end do_add #
##############


##############
# start edit #
##############
case "edit":
	extract($_POST);

	require("template/".$page_id.".edit.php");
	require("template/footer.php");
	exit();
#############
# end edit  #
#############



#################
# start modify	#
#################
case "modify":
	$id=$_GET['id'];
	$group_id=$_POST['group_id'];
	$dst_uri=$_POST['dst_uri'];
	$resources=$_POST['resources'];
	$probe_mode = $_POST['probe_mode'];
	$description=$_POST['description'];

	$sql = "UPDATE ".$table." SET group_id=".$group_id.", dst_uri = '".$dst_uri.
		"', resources='".$resources."', probe_mode=".$probe_mode.", description='".$description."' WHERE id=".$id;
	$result = $link->exec($sql);
       	if(PEAR::isError($result)) {
		$errors = "Update to DB failed with: ".$result->getUserInfo();
	} else {
		$info="LB destination has been updated";
	}
	$link->disconnect();

	break;
#################
# end modify	#
#################


#################
# start toggle #
#################
case "toggle":

	$state= $_GET['state'];
	$id = $_GET['id'];
	if ($state=="enabled") {
		$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
		for($i=0;$i<count($mi_connectors);$i++) {
			mi_command("lb_status $id 0", $mi_connectors[$i], $errors , $status);
		}
	} else if ($state=="disabled") {
		$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
		for($i=0;$i<count($mi_connectors);$i++) {
			mi_command("lb_status $id 1", $mi_connectors[$i], $errors , $status);
		}
	}
	break;
################
# end   toggle #
################


################
# start delete #
################
case "delete":
	$id=$_GET['id'];
	$sql = "DELETE FROM ".$table." WHERE id=".$id;
        $link->exec($sql);
	$link->disconnect();
	$info="Record has been deleted";

	break;
##############
# end delete #
##############

} //switch(action)



################
# start search #
################
if ($action=="search")
{
	$query="";
	$_SESSION['lb_groupid']  = $_POST['lb_groupid'];
	$_SESSION['lb_dsturi']= $_POST['lb_dsturi'];
	$_SESSION['lb_resources']= $_POST['lb_resources'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['lb_groupid']="";
		$_SESSION['lb_dsturi']="";
		$_SESSION['lb_resources']="";
	} else if($search=="Search"){
		$_SESSION['lb_groupid']=$_POST['lb_groupid'];
		$_SESSION['lb_dsturi'] =$_POST['lb_dsturi'];
		$_SESSION['lb_resources'] =$_POST['lb_resources'];
	}
}
##############
# end search #
##############


##############
# start main #
##############
require("template/".$page_id.".main.php");
if ($errors!="") echo('<tr><td align="center"><div class="formError">'.$errors.'</div></td></tr>');
if ($info!="") echo('<tr><td  align="center"><div class="formInfo">'.$info.'</div></td></tr>');
require("template/footer.php");
exit();
##############
# end main   #
##############
?>
