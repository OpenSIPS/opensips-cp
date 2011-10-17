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
require("../../../common/mi_comm.php");
include("lib/db_connect.php");

$table=$config->table_dispatcher;
$current_page="current_page_dispatcher";

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;


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
# start add verify #
####################
if ($action=="add_verify")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$setid=$_POST['setid'];
		$destination=$_POST['destination'];
		$flags = $_POST['flags'];

		if (!empty($_POST['description'])) {
			$description= $_POST['description'];
		} else {
			$description=NULL;
		}

		if($setid=="")
		$setid = "0";

		if($flags=="")
		$flags = "0";

		if ($errors=="") {
		/*	$sql = "SELECT * FROM ".$table.
			" WHERE setid=" .$setid;
			$resultset = $link->queryAll($sql);
                        if(PEAR::isError($resultset)) {
                                die('Failed to issue query, error message : ' . $resultset->getMessage());
                        }
 	
			if (count($resultset)>0) {
				$errors="Duplicate rule";
			} else {*/
				$sql = "INSERT INTO ".$table."
				(setid, destination, flags, description) VALUES 
				(". $setid .",'". $destination ."',". $flags .",'". $description."') ";
				$resultset = $link->prepare($sql);
				$resultset->execute();
				$resultset->free();
				$info="The new record was added";
			//}
			$link->disconnect();
		}
	}else{
		$errors= "User with Read-Only Rights";
	}

}

##################
# end add verify #
##################

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
# end edit	#
#############

#################
# start modify	#
#################
if ($action=="modify")
{

	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$id = $_GET['id'];
		$setid=$_POST['setid'];
		$destination=$_POST['destination'];
		$flags = $_POST['flags'];
		$description= $_POST['description'];

		if ($setid=="" || $destination=="" || $flags==""){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if ($errors=="") {
			$sql = "SELECT * FROM ".$table." WHERE setid=" .$setid. " AND id!=".$id;
			$resultset = $link->queryAll($sql);
                        if(PEAR::isError($resultset)) {
                                die('Failed to issue query, error message : ' . $resultset->getMessage());
                        }
				$sql = "UPDATE ".$table." SET setid=".$setid.", destination = '".$destination.
				"', flags= ".$flags.", description ='".$description."' WHERE id=".$id;
				$resultset = $link->prepare($sql);
				$resultset->execute();
				$resultset->free();
				$info="The new rule was modified";
			$link->disconnect();
		}
	}else{

		$errors= "User with Read-Only Rights";
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
if ($action=="dp_act")
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
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}else if($delete=="Delete Dispatcher"){
		$sql_query = "";
		if( $_POST['dispatcher_setid'] != "" ) {
			$setid = $_POST['dispatcher_setid'];
			$sql_query .= " AND setid=".$setid;
		}

		if( $_POST['dispatcher_dest'] != "" ) {
			$dest = $_POST['dispatcher_dest'];
			$sql_query .= " AND destination='".$dest . "'";
		}

		if( $_POST['dispatcher_descr'] != "" ) {
			$descr = $_POST['dispatcher_descr'];
			$sql_query .= " AND description='".$descr . "'";
		}

		$sql = "SELECT * FROM ".$table.
		" WHERE (1=1) " . $sql_query;
		$resultset = $link->queryAll($sql);
                if(PEAR::isError($resultset)) {
	                die('Failed to issue query, error message : ' . $resultset->getMessage());
                }
		if (count($resultset)==0) {
			$errors="No such Dispatcher rule";
			$_SESSION['dispatcher_setid']="";
			$_SESSION['dispatcher_dest']="";
			$_SESSION['dispatcher_descr']="";

		}else{

			$sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
			$link->exec($sql);
		}
		$link->disconnect();
	}
}
##############
# end search #
##############

################
#change state   #
################
if ($action=="change_state") {


	if (($_GET['state']=='Active') || ($_GET['state']=='Probing') ){
		$desired_state = 'I';
	} else if ($_GET['state']=='Inactive'){
		$desired_state = 'A';
	}

	$group = $_GET['group'];
	$address = $_GET['address'];

	$mi_connectors=get_all_proxys_by_assoc_id($talk_to_this_assoc_id);
	for ($i=0;$i<count($mi_connectors);$i++){

        	$comm_type=params($mi_connectors[$i]);
	        $message=mi_command("ds_set_state $desired_state $group $address",$errors,$status);
        	print_r($errors);
	        $status = trim($status);
	}


}

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
