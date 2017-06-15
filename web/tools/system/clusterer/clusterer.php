<?php
/*
* Copyright (C) 2016 OpenSIPS Project
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
include("lib/db_connect.php");

$table=$config->table_clusterer;
$current_page="current_page_address";

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


################
# start do add #
################
if ($action=="do_add")
{
	if ($_SESSION['read_only']) {
		$errors= "User with Read-Only Rights";
	} else {
		$cln_cid=$_POST['cluster_id'];
		$cln_sid=$_POST['node_id'];
		$cln_url=$_POST['url'];
		$cln_ping=$_POST['no_ping'];
		$cln_description=$_POST['description'];

		$sql = "INSERT INTO ".$table." (cluster_id, node_id, url, no_ping_retries, description) VALUES 
			(".$cln_cid.",".$cln_sid.",'".$cln_url."','".$cln_ping."','".$cln_description."')";
		$result = $link->exec($sql);
        	if(PEAR::isError($result)) {
	        	$errors = "Add/Insert to DB failed with: ".$result->getUserInfo();
       		} else {
			$info="The new cluster node was added";
		}
		$link->disconnect();
	}

}
##############
# end do add #
##############

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
# end edit	#
#############

#################
# start modify	#
#################
if ($action=="modify")
{
	if ($_SESSION['read_only']) {
		$errors= "User with Read-Only Rights";
	} else {
		$cle_id = $_GET['id'];
		$cle_cid=$_POST['cluster_id'];
		$cle_sid=$_POST['node_id'];
		$cle_url=$_POST['url'];
		$cle_ping=$_POST['no_ping'];
		$cle_description=$_POST['description'];

		$sql = "UPDATE ".$table." set cluster_id=".$cle_cid.", node_id=".$cle_sid.", url='".$cle_url."', no_ping_retries='".$cle_ping."', description='".$cle_description."' where id=".$cle_id;
		$result = $link->exec($sql);
        	if(PEAR::isError($result)) {
	        	$errors = "Update to DB failed with: ".$result->getUserInfo();
       		} else {
			$info="Cluster Node has been updated";
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
if ($action=="search")
{
	$_SESSION['cl_cid']=$_POST['cl_cid'];
	$_SESSION['cl_url']=$_POST['cl_url'];
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['cl_cid']="";
		$_SESSION['cl_url']="";
	} else if($search=="Search"){
		$_SESSION['cl_cid']=$_POST['cl_cid'];
		$_SESSION['cl_url']=$_POST['cl_url'];
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
