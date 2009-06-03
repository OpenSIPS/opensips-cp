<?php
/*
* $Id$
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

$table=$config->table_trusted;
$current_page="current_page_trusted";

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

		$src_ip=$_POST['src_ip'];
		$proto=$_POST['proto'];
		$from_pattern = $_POST['from_pattern'];
		$tag= $_POST['tag'];

		if($from_pattern=="")
		$flags = "0";

		if ($errors=="") {
			$sql = "SELECT * FROM ".$table.
			" WHERE src_ip='" .$src_ip."'";
			$resultset = $link->queryAll($sql);
                        if(PEAR::isError($resultset)) {
                                die('Failed to issue query, error message : ' . $resultset->getMessage());
                        }

			if (count($resultset)>0) {
				$errors="Duplicate rule";
			} else {
				$sql = 'INSERT INTO '.$table.'
				(src_ip, proto, from_pattern, tag) VALUES 
				(:src_ip, :proto, :from_pattern, :tag)';
				$resultset = $link->prepare($sql);
				$resultset->bindParam('src_ip', $src_ip);
				$resultset->bindParam('proto', $proto);
				$resultset->bindParam('from_pattern', $from_pattern);
				$resultset->bindParam('tag', $tag);
				$resultset->execute();
				$resultset->free();

				$info="The new record was added";
			}
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
		$src_ip=$_POST['src_ip'];
		$proto=$_POST['proto'];
		$from_pattern = $_POST['from_pattern'];
		$tag= $_POST['tag'];

		if ( $src_ip=="" || $proto=="" ){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if ($errors=="") {
			$sql = "SELECT * FROM ".$table." WHERE src_ip='" .$src_ip. "' AND proto='" . $proto. "' AND id!=".$id;
	                if(PEAR::isError($resultset)) {
                                die('Failed to issue query, error message : ' . $resultset->getMessage());
                        }
	
			if (count($resultset)>0) {
				$errors="Duplicate rule";
			} else {

				$sql = "UPDATE ".$table." SET src_ip='".$src_ip."', proto = '".$proto.
				"', from_pattern= '".$from_pattern."', tag ='".$tag."' WHERE id=".$id;
				$resultset = $link->prepare($sql);
				$resultset->execute();
				$resultset->free();

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

	$_SESSION['trusted_src']=$_POST['trusted_src'];
	$_SESSION['trusted_proto']=$_POST['trusted_proto'];
	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['trusted_src']="";
		$_SESSION['trusted_proto']="";
	} else if($search=="Search"){
		$_SESSION['trusted_src']=$_POST['trusted_src'];
		$_SESSION['trusted_proto']=$_POST['trusted_proto'];
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}else if($delete=="Delete Trusted"){
		$sql_query="";
		if ( $_POST['trusted_src'] != "" ) {
			$src_ip = $_POST['trusted_src'];
			$sql_query .= " AND src_ip like '%" . $src_ip . "%'"; 
		}
		if ( $_POST['trusted_proto'] != "" ) {
			$proto = $_POST['trusted_proto'];
			$sql_query .= " AND proto like '%" . $proto . "%'"; 
		}
		$sql = "SELECT * FROM ".$table.
		" WHERE (1=1) " .$sql_query;
		$resultset = $link->queryAll($sql);
		if (count($resultset)==0) {
			$errors="No such rule";
			$_SESSION['trusted_src']="";
			$_SESSION['trusted_proto']="";

		}else{
			$sql = "DELETE FROM ".$table." WHERE (1=1) ".$sql_query;
			$link->exec($sql);
		}
		$link->disconnect();
	
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
