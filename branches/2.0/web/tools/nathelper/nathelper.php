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
require("../../../config/db.inc.php");

$table=$config->table_nathelper;
$current_page="current_page_nathelper";

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

		$rtpproxy_sock=$_POST['rtpproxy_sock'];
		$set_id=$_POST['set_id'];

		if($rtpproxy_sock=="" || $set_id=="") {
			print "Invalid data!!";
		}

		if ($errors=="") {
			$link = db_connect();
			$result=mysql_query("SELECT * FROM ".$table." WHERE set_id=" .$set_id . " AND rtpproxy_sock='".$rtpproxy_sock."'")
			or die(mysql_error());
			if (mysql_num_rows($result)>0) {
				$errors="Duplicate rule";
			} else {
				mysql_query("INSERT INTO ".$table."
				(set_id, rtpproxy_sock) VALUES 
				(".$set_id.", '".$rtpproxy_sock."') ")
				or die(mysql_error());

				$info="The new record was added";
			}
			db_close();
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

		$set_id=$_POST['set_id'];
		$rtpproxy_sock=$_POST['rtpproxy_sock'];

		if ($set_id=="" || $rtpproxy_sock==""){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if ($errors=="") {
			$link = db_connect();
			$result=mysql_query("SELECT * FROM ".$table." WHERE set_id=" .$set_id. " AND rtpproxy_sock='".$rtpproxy_sock."' AND id!=".$id)or die(mysql_error());
			if (mysql_num_rows($result)>0) {
				$errors="Duplicate rule";
			} else {

				mysql_query("UPDATE ".$table." SET set_id=".$set_id.", rtpproxy_sock = '".$rtpproxy_sock.
				"' WHERE id=".$id) or die(mysql_error());

				$info="The new rule was modified";
			}
			db_close();
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

		db_connect();
		$id=$_GET['id'];
		mysql_query("DELETE FROM ".$table." WHERE id=".$id)
		or die(mysql_error());
		db_close();
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
	$_SESSION['nathelper_sock']  = $_POST['nathelper_sock'];
	$_SESSION['nathelper_setid']= $_POST['nathelper_setid'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['nathelper_setid']="";
		$_SESSION['nathelper_sock']="";
	} else if($search=="Search"){
		$_SESSION['nathelper_setid']=$_POST['nathelper_setid'];
		$_SESSION['nathelper_sock'] =$_POST['nathelper_sock'];
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}else if($delete=="Delete RTPproxy Sock"){
		$set_id = $_POST['nathelper_setid'];
		$rtpproxy_sock = $_POST['nathelper_sock'];
		if($rtpproxy_sock =="") { 
			$query .= " AND rtpproxy_sock like %";
		}else {
			$query .= " AND rtpproxy_sock like '%" . $rtpproxy_sock."%'"; 
		}
		if ($set_id!=""){
			$query .=" AND set_id=".$set_id;
		}
			db_connect();
			$result=mysql_query("SELECT * FROM ".$table.
			" WHERE 1 ". $query)
			or die(mysql_error());

			if (mysql_num_rows($result)==0) {
				$errors="No Rule with such RTPproxy Sock ID";
				$_SESSION['nathelper_setid']="";
				$_SESSION['nathelper_sock']="";

			}else{
				mysql_query("DELETE FROM ".$table." WHERE 1 " .$query)
				or die(mysql_error());
			}
			db_close();
		print $result;
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
