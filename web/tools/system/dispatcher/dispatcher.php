<?php
/*
* Copyright (C) 2011-2017 OpenSIPS Project
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
require("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");
include("lib/db_connect.php");

$table=$config->table_dispatcher;
$current_page="current_page_dispatcher";

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;


$info="";
$errors="";

#################
# start add new #
#################

if ($action=="add")
{
	extract($_POST);
	if(!$_SESSION['read_only'])
	{
		require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	}else {
		$errors= "User with Read-Only Rights";
	}

}

#################
# end add new   #
#################


####################
# start do_add     #
####################
if ($action=="do_add")
{
	if ($_SESSION['read_only']) {
		$errors= "User with Read-Only Rights";
	} else {
		$setid=$_POST['setid'];
		$destination=$_POST['destination'];
		$socket = $_POST['socket'];
		$state = $_POST['state'];
		$weight = $_POST['weight'];
		$attrs = $_POST['attrs'];
		$description = $_POST['description'];

		$sql = "INSERT INTO ".$table." (setid, destination, socket, state, weight, attrs, description) VALUES 
			('". $setid ."','". $destination ."','".$socket."','".$state."','".$weight."','".$attrs."','".$description."') ";
		$result = $link->exec($sql);
        	if(PEAR::isError($result)) {
	        	$errors = "Add/Insert to DB failed with: ".$result->getUserInfo();
       		} else {
			$info="The new record was added";
		}
		$link->disconnect();
	}

}

##################
# end add verify #
##################

#################
# start edit	#
#################
if ($action=="edit")
{

	if(!$_SESSION['read_only']){

		extract($_POST);

		require("template/".$page_id.".edit.php");
		require("template/footer.php");
		exit();
	}else{
		$errors= "User with Read-Only Rights";
	}
}
#############
# end edit  #
#############

#################
# start modify	#
#################
if ($action=="modify")
{

	if ($_SESSION['read_only']) {
		$errors= "User with Read-Only Rights";
	} else {
		$id = $_GET['id'];
		$setid=$_POST['setid'];
		$destination=$_POST['destination'];
		$socket = $_POST['socket'];
		$state = $_POST['state'];
		$weight = $_POST['weight'];
		$attrs = $_POST['attrs'];
		$description = $_POST['description'];


		$sql = "UPDATE ".$table." SET 
			setid=".$setid.", 
			destination = '".$destination."', 
			socket = '".$socket."', 
			state = ".$state.", 
			weight = ".$weight.", 
			attrs = '".$attrs."', 
			description = '".$description."' 
			WHERE id=".$id;
		$result = $link->exec($sql);
        	if(PEAR::isError($result)) {
	        	$errors = "Update to DB failed with: ".$result->getUserInfo();
       		} else {
			$info="Record has been updated";
		}
		$link->disconnect();
	}

}
#################
# end modify	#
#################



################
# start delete #
################
if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id=$_GET['id'];

		$sql = "DELETE FROM ".$table." WHERE id=".$id;
		$link->exec($sql);
		$info="Record has been deleted";
	}else{
		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############


################
# start search #
################
if ($action=="ds_search")
{

	$_SESSION['dispatcher_id']=$_POST['dispatcher_id'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['dispatcher_setid']="";
		$_SESSION['dispatcher_dest']="";
		$_SESSION['dispatcher_descr']="";
	} else if($search=="Search"){
		$_SESSION['dispatcher_setid']=$_POST['dispatcher_setid'];
		$_SESSION['dispatcher_dest']=$_POST['dispatcher_dest'];
		$_SESSION['dispatcher_descr']=$_POST['dispatcher_descr'];
	}
}
##############
# end search #
##############

################
#change state   #
################
if ($action=="change_state") {


	if ($_GET['state']=='Active') {
		$desired_state = 'I';
	} else if ($_GET['state']=='Inactive'){
		$desired_state = 'A';
	} else if ($_GET['state']=='Probing'){
		$desired_state = 'A';
	}

	$group = $_GET['group'];
	$address = $_GET['address'];

	$mi_connectors=get_all_proxys_by_assoc_id($talk_to_this_assoc_id);
	for ($i=0;$i<count($mi_connectors);$i++){
	        $message=mi_command("ds_set_state $desired_state $group $address",$mi_connectors[$i],$errors,$status);
	}


}

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
