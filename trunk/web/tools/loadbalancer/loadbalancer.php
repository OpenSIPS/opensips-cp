<?php
/*
* $Id: loadbalancer.php 79 2009-07-10 09:37:43Z iulia_bublea $
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

require("template/header.php");
require("lib/".$page_id.".main.js");
require ("../../common/mi_comm.php");
include("lib/db_connect.php");

$table=$config->table_lb;
$current_page="current_page_lb";

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
                $temp = split(";",$_POST['resources']);
		print $temp[count($temp)+1];
		if (empty($temp[count($temp)+1])) pop($temp[count($temp)+1]); 
                for($i=0;count($temp)>$i;$i++) {
                        preg_match('/(\s*[a-zA-Z0-9]+=\d+\s*)*/',$temp[$i],$matches);
                        if (!empty($matches[0])) $match[]=$matches[0];
                }
                if (count($match)!=count($temp)) {
                        while(1) {
                                $errors = "Data format is incorrect!. Should be name1=value1;name2=value2...";
                                if($errors)
                                echo('!!! ');echo($errors);
                                require("template/footer.php");
                                exit();
                                }
                }
		

		if ($_POST['probe_mode'] == "No probing") $probe_mode = 0;
		else if ($_POST['probe_mode'] == "Probing only when the destination is in disabled mode") $probe_mode=1;
		else $probe_mode=2;

		$sql_command = "INSERT INTO ".$table."
		(group_id, dst_uri,resources,probe_mode,description) VALUES 
		(".$group_id.", '".$dst_uri."','".$resources."',".$probe_mode.",'".$description."') ";
	        $result = $link->prepare($sql_command);
	        $result->execute();
	        $result->free();

		$info="The new record was added";
		$link->disconnect();	
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
# end edit  #
#############

#################
# start modify	#
#################
if ($action=="modify")
{

	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$group_id=$_POST['group_id'];
		$dst_uri=$_POST['dst_uri'];
		$resources=$_POST['resources'];
		$temp = split(";",$resources);
		for($i=0;count($temp)>$i;$i++) {
			preg_match('/(\s*[a-zA-Z0-9]+=\d+\s*)*/',$temp[$i],$matches); //print_r($matches);
			if (!empty($matches[0])) $match[]=$matches[0];
		}
		if (count($match)!=count($temp)) { 
			while(1) {
				$errors = "Data format is incorrect!. Should be name1=value1;name2=value2...";
				if($errors)
				echo('!!! ');echo($errors);
				require("template/footer.php");
				exit();
				}
		}

		if ($_POST['probe_mode'] == "No probing") $probe_mode = 0;
		else if ($_POST['probe_mode'] == "Probing only when the destination is in disabled mode") $probe_mode=1;
		else $probe_mode=2;
		$description=$_POST['description'];

		if ($group_id=="" || $dst_uri=="" || $resources==""){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if ($errors=="") {
			$sql = "SELECT * FROM ".$table." WHERE group_id=" .$group_id. " AND dst_uri='".$dst_uri."' AND resources='".$resources."' AND probe_mode=". $probe_mode." AND id!=".$id;
                        $row = $link->queryAll($sql);
                        if(PEAR::isError($row)) {
                                 die('Failed to issue query, error message : ' . $row->getMessage());
                        }

			if (count($row)>0) {
				$errors="Duplicate rule";
			} else {

				$sql_command = "UPDATE ".$table." SET group_id=".$group_id.", dst_uri = '".$dst_uri.
				"', resources='".$resources."', probe_mode=".$probe_mode.", description='".$description."' WHERE id=".$id;
                                 $result = $link->prepare($sql_command);
                                 $result->execute();
                                 $result->free();

				$info="The new rule was modified";
			}
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
		$link->disconnect();
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
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}else if($delete=="Delete Load Balancer Definition"){
		$group_id = $_POST['lb_groupid'];
		$dst_uri = $_POST['lb_dsturi'];
		$resources = $_POST['resources'];

		if($dst_uri =="") { 
			$query .= " AND dst_uri like '%'";
		}else {
			$query .= " AND dst_uri like '%" . $dst_uri."%' "; 
		}

		if ($group_id != ""){
			$query .=" AND group_id=".$group_id;
		}

		if ($resources == ""){
			$query .= " AND resources like '%'";
		} else {
			$query .= " AND resources like '%" . $resources . "%' ";
		}
			$sql = "SELECT * FROM ".$table.
			" WHERE (1=1) ". $query;
	                $row = $link->queryAll($sql);
                        if(PEAR::isError($row)) {
                                 die('Failed to issue query, error message : ' . $row->getMessage());
                        }

			if (count($row)==0) {
				$errors="No such Load Balancer Rule";
				$_SESSION['lb_groupid']="";
				$_SESSION['lb_dsturi']="";
				$_SESSION['lb_resources']="";

			}else{
				$sql = "DELETE FROM ".$table." WHERE (1=1) " .$query;
		                $link->exec($sql);				
			}
			$link->disconnect();
		//print $result;
	}
}
##############
# end search #
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
