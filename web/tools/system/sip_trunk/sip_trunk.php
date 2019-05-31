<?php
/*
* Copyright (C) 2019 OpenSIPS Project
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
require("lib/" . $page_id . ".main.js");
require ("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");
include("lib/db_connect.php");

$table = $config->table_registrant;
$current_page = "current_page_registrant";

if ( isset($_POST['action']) )
	$action=$_POST['action'];
else if ( isset($_GET['action']) )
	$action=$_GET['action'];
else
	$action = "";

if ( isset($_GET['page']) )
	$_SESSION[$current_page]=$_GET['page'];
else if ( !isset($_SESSION[$current_page]) )
	$_SESSION[$current_page]=1;

#################
# start add new #
#################

if ( $action == "add" )
{
	extract($_POST);
	if( !$_SESSION['read_only'] )
	{
		require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	} else {
		$errors = "User with Read-Only Rights";
	}
}
#################
# end add new   #
#################

####################
# start add_verify #
####################
if ( $action == "add_verify" ) {
	$info = "";
	$errors = "";

	if( !$_SESSION['read_only'] ) {
		$registrar = $_POST['registrar'];
		$proxy = $_POST['proxy'];
		$registrar_mode = $_POST['registrar_mode'];
		$aor = $_POST['aor'];
		$third_party_registrant = $_POST['third_party_registrant'];
		$username = $_POST['username'];
		$password  = $_POST['password'];
		$binding_uri = $_POST['binding_uri'];
		$binding_params = $_POST['binding_params'];
		$expiry = $_POST['expiry'];
		$forced_socket = $_POST['forced_socket'];
		$cluster_shtag = $_POST['cluster_shtag'];

		if( $registrar == "" || $proxy == "" || $aor == "" || $username == "" || $password == "") {
			print "Invalid data!!";
		}

		if ( $errors == "" ) {
			$sql = "SELECT count(*) FROM ".$table." WHERE registrar=? and proxy=? AND aor=?";
			$stm = $link->prepare($sql);
			if ($stm === FALSE)
				die('Failed to issue query, error message : ' .
					print_r($link->errorInfo(), true));
			$stm->execute(array($registrar, $proxy, $aor));
			if ( $stm->fetchColumn(0) > 0 ) {
				$errors = "Duplicate Registrar";
			} else {
				$sql_command = "INSERT INTO " . $table .
				"(registrar, proxy, registrar_mode, aor, third_party_registrant, username, password, " .
				"binding_uri, binding_params, expiry, forced_socket, cluster_shtag) " .
							 "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stm = $link->prepare($sql_command);
				if ( $stm->execute( array($registrar, $proxy, $registrar_mode, $aor, $third_party_registrant, $username, $password, $binding_uri, $binding_params, $expiry, $forced_socket, $cluster_shtag) ) === false ) {
					$errors = "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
				} else {
					$info = "The new record was added";
				}
			}
		}
	} else {
		$errors = "User with Read-Only Rights";
	}
}
##################
# end add_verify #
##################

#####################################
# start add_verify cloned registrar #
#####################################
if ( $action == "add_verify_registrar" )
{
	$info = "";
	$errors = "";

	if(!$_SESSION['read_only']){

		$src_registrar = $_POST['src'];
		$dst_registrar = $_POST['dst'];

		if ($src_registrar == "" || $dst_registrar == ""){
			$errors = "Empty source or destination registrar";
		}else if($src_registrar == $dst_registrar){
			$errors = "Source the same as destination";
		}

		if ($errors == "") {
			$sql = "SELECT * FROM ".$table." WHERE registrar=?";
			$stm = $link->prepare($sql);
			if ($stm === FALSE)
				die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
			$stm->execute(array($src_registrar));
			$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

			if (count($resultset)==0) {
				$errors = "No rules to duplicate";
			} else {
				for ($i=0; $i<count($resultset); $i++)
				{
					$sql_command = "INSERT INTO " . $table .
								 "(registrar, proxy, registrar_mode, aor, third_party_registrant, username, password, " .
								 "binding_uri, binding_params, expiry, forced_socket, cluster_shtag) " .
								 "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$stm = $link->prepare($sql);
					if ($stm === false) {
						die('Failed to issue query [' .$sql . '], error message : ' . print_r($link->errorInfo(), true));
					}
					if ($stm->execute( array($dst_registrar,$resultset[$i]['proxy'],
											 $resultset[$i]['registrar_mode'],['aor'],
											 $resultset[$i]['third_party_registrant'],$resultset[$i]['username'],
											 $resultset[$i]['password'],$resultset[$i]['binding_uri'],
											 $resultset[$i]['binding_params'], $resultset[$i]['expiry'],
											 $resultset[$i]['forced_socket'],
											 $resultset[$i]['cluster_shtag']) ) == false )
						$errors .= "Inserting record into DB failed: " . print_r($stm->errorInfo(), true);
				}
				$info = "The dialplan was cloned";
			}
		}
	} else {
		$errors= "User with Read-Only Rights";
	}
}
###################################
# end add_verify cloned registrar #
###################################

################
# change state #
################
if ( $action == "change_state" ) {

	$state = $_GET['state'];
	$sock = $_GET['sock'];

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	for ( $i=0; $i<count($mi_connectors); $i++ ) {
		if ($state == "0") {
			mi_command("sip_trunk_enable $sock 0" , $mi_connectors[$i], $errors , $status);
		} else {
			mi_command("sip_trunk_enable $sock 1" , $mi_connectors[$i], $errors , $status);
		}
	}

}
####################
# end change state #
####################

###############
# start clone #
###############
if ( $action == "clone" )
{
	if( !$_SESSION['read_only'] ) {
		extract($_POST);
		require("template/".$page_id.".clone.php");
		//require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	} else {
		$errors= "User with Read-Only Rights";
	}
}
###############
# end clone   #
###############

################
# start delete #
################
if ( $action == "delete" )
{
	if( !$_SESSION['read_only'] ) {
		$id = $_GET['id'];
		$sql = "DELETE FROM " . $table . " WHERE id = ?";
		$stm = $link->prepare($sql);
		if ( $stm->execute( array($id) ) === false )
			die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	} else {
		$errors = "User with Read-Only Rights";
	}
}
##############
# end delete #
##############

##############
# start edit #
##############
if ( $action == "edit" )
{

	if ( !$_SESSION['read_only'] ){
		extract($_POST);

		require("template/" . $page_id . ".edit.php");
		require("template/footer.php");
		exit();
	} else {
		$errors = "User with Read-Only Rights";
	}
}
#############
# end edit  #
#############

#################
# start modify	#
#################
if ( $action == "modify" )
{
	$info = "";
	$errors = "";

	if( !$_SESSION['read_only'] ) {
		$id = $_GET['id'];
		$registrar=$_POST['registrar'];
		$proxy = $_POST['proxy'];
		$registrar_mode=$_POST['registrar_mode'];
		$aor = $_POST['aor'];
		$third_party_registrant = $_POST['third_party_registrant'];
		$username = $_POST['username'];
		$password  = $_POST['password'];
		$binding_uri = $_POST['binding_uri'];
		$binding_params = $_POST['binding_params'];
		$expiry = $_POST['expiry'];
		$forced_socket = $_POST['forced_socket'];
		$cluster_shtag = $_POST['cluster_shtag'];

		if( $registrar == "" || $proxy == "" || $aor == "" || $username == "" || $password == "") {
			print "Invalid data!!";
		}

		if ( $registrar == "" ) {
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if ( $errors == "" ) {
			$sql_command = "SELECT * FROM " . $table . " WHERE registrar = ?  AND id != ?";
			$stm = $link->prepare($sql_command);
			if ( $stm->execute( array($registrar, $id) ) === false )
				die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
			$row = $stm->fetchAll();

			if ( count($row)>0 ) {
				$errors = "Duplicate registrar";
			} else {
				$sql_command = "UPDATE " . $table . " SET " .
							 "registrar=?, " .
							 "proxy=?, " .
							 "registrar_mode=?, " .
							 "aor=?, " .
							 "third_party_registrant=?, " .
							 "username=?, " .
							 "password=?, " .
							 "binding_uri=?, " .
							 "binding_params=?, " .
							 "expiry=?, " .
							 "forced_socket=?, " .
							 "cluster_shtag=? " .
							 "WHERE id=?";
				$stm = $link->prepare($sql_command);
				if ($stm === false) {
					die('Failed to issue query ['.$sqlL_command.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ( $stm->execute( array($registrar, $proxy, $registrar_mode, $aor, $third_party_registrant, $username, $password,
										  $binding_uri, $binding_params, $expiry, $forced_socket, $cluster_shtag,
										  $id) ) === false ) {
					$errors = "Updating SIP Trunk record failed: ".print_r($stm->errorInfo(), true);
				} else {
					$info = "The SIP Trunk record was modified";
				}
			}
		}
	} else {

		$errors = "User with Read-Only Rights";
	}
}
#################
# end modify	#
#################

################
# start search #
################
if ( $action == "sip_trunk_search" ) {
	$_SESSION[$current_page]=1;
	extract($_GET);
	extract($_POST);

	if ( $show_all == "Show All" ) {
		$_SESSION['sip_trunk_registrar'] = "";
		$_SESSION['sip_trunk_proxy'] = "";
		$_SESSION['sip_trunk_aor'] = "";
	} else if( $search == "Search" ) {
		if (isset($_GET['sip_trunk_registrar']))
			$_SESSION['sip_trunk_registrar']=$_GET['sip_trunk_registrar'];
		else if (isset($_POST['sip_trunk_registrar']))
			$_SESSION['sip_trunk_registrar']=$_POST['sip_trunk_registrar'];
		else
			$_SESSION['sip_trunk_registrar']="";
		if (isset($_GET['sip_trunk_proxy']))
			$_SESSION['sip_trunk_proxy']=$_GET['sip_trunk_proxy'];
		else if (isset($_POST['sip_trunk_proxy']))
			$_SESSION['sip_trunk_proxy']=$_POST['sip_trunk_proxy'];
		else
			$_SESSION['sip_trunk_proxy']="";
		if (isset($_GET['sip_trunk_aor']))
			$_SESSION['sip_trunk_aor']=$_GET['sip_trunk_aor'];
		else if (isset($_POST['sip_trunk_aor']))
			$_SESSION['sip_trunk_aor']=$_POST['sip_trunk_aor'];
		else
			$_SESSION['sip_trunk_aor']="";
	}
}
##############
# end search #
##############

##############
# start main #
##############

require("template/" . $page_id . ".main.php");

if ( !empty($errors) ) {
	echo "Error stack: ";
	print_r($errors);
}

require("template/footer.php");
exit();

##############
# end main   #
##############
?>
