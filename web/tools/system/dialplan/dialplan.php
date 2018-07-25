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
require("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");
include("lib/db_connect.php");
$table=$config->table_dialplan;

$current_page="current_page_dialplan";

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

###############
# start clone #
###############

if ($action=="clone")
{
	if(!$_SESSION['read_only']){
		extract($_POST);
		require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	}else{
		$errors= "User with Read-Only Rights";
	}

}

###############
# end clone   #
###############


####################
# start add verify #
####################
if ($action=="add_verify")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$dpid=$_POST['dpid'];
		$pr=$_POST['pr'];
		$match_op = $_POST['match_op'];
		$match_exp= $_POST['match_exp'];
		$match_flags= $_POST['match_exp_flags'];
		$subst_exp= $_POST['subst_exp'];
		$repl_exp= $_POST['repl_exp'];
		if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {

			$attrs= "";

		} else if ($dialplan_attributes_mode == 1 ) {

			$attrs= $_POST['attrs'];

		}

		if ($dpid=="" || $pr=="" || $match_exp==""){
			$errors = "Invalid data, the entry was not inserted in the database";
		}
		if($match_flags==NULL)
		$match_flags = 0;

		if ($errors=="") {
			$sql = "SELECT count(*) FROM ".$table." WHERE dpid=? AND match_exp= ?";
			$stm = $link->prepare($sql);
			if ($stm === FALSE)
				die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
			$stm->execute(array($dpid, $match_exp));
			if ( $stm->fetchColumn(0) > 0 ) {
				$errors="Duplicate rule";
			} else {
				for($i = 0; $i<sizeof($config->attrs_cb); $i++)
				{
					$attrs.=!isset($_POST[$config->attrs_cb[$i][0]])?
					"": $config->attrs_cb[$i][0];
					#$attrs.=' ';
				}

				$sql = "INSERT INTO ".$table."
				(dpid, pr, match_op, match_exp, match_flags, subst_exp, 
				repl_exp, attrs) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; 
				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( array($dpid,$pr,$match_op,$match_exp,$match_flags,$subst_exp,$repl_exp,$attrs) ) == false ) {
					$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
				} else {
					$info="The new rule was added";
				}
			}
		}
	}else{
		$errors= "User with Read-Only Rights";
	}

}

##################
# end add verify #
##################

##############################
# start add verify cloned dp #
##############################
if ($action=="add_verify_dp")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$src_dpid=$_POST['src'];
		$dst_dpid=$_POST['dst'];

		if ($src_dpid=="" || $dst_dpid==""){
			$errors = "Empty source or destination Dialplan ID";
		}else if($src_dpid==$dst_dpid){
			$errors = "Source the same as destination";
		}

		if ($errors=="") {

			$sql = "SELECT * FROM ".$table." WHERE dpid=?";
			$stm = $link->prepare($sql);
			if ($stm === FALSE)
				die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
			$stm->execute(array($src_dpid));
			$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

			if (count($resultset)==0) {
				$errors="No rules to duplicate";
			} else {
				for ($i=0; $i<count($resultset);$i++)
				{
					$sql = "INSERT INTO ".$table."(dpid, pr, match_op, match_exp, match_flags, subst_exp, repl_exp, attrs) ".
						"VALUES (?,?,?,?,?,?,?,?)";
					$stm = $link->prepare($sql);
					if ($stm === false) {
						die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
					}
					if ($stm->execute( array($dst_dpid,$resultset[$i]['pr'],$resultset[$i]['match_op'],$resultset[$i]['match_exp'],
					$resultset[$i]['match_flags'],$resultset[$i]['subst_exp'],$resultset[$i]['repl_exp'],
					$resultset[$i]['attrs']) ) == false )
						$errors .= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);



				}
				$info="The dialplan was cloned";
			}
		}
	}else{

		$errors= "User with Read-Only Rights";
	}

}

############################
# end add verify cloned dp #
############################

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

	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$id = $_GET['id'];
		$dpid=$_POST['dpid'];
		$pr=$_POST['pr'];
		$match_op = $_POST['match_op'];
		$match_exp= $_POST['match_exp'];
		$match_flags= $_POST['match_exp_flags'];
		$subst_exp= $_POST['subst_exp'];
		$repl_exp= $_POST['repl_exp'];

		if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {

			$attrs= "";

		} else if ($dialplan_attributes_mode == 1 ) {

			$attrs= $_POST['attrs'];

		}

		if ($dpid=="" || $pr=="" || $match_exp==""){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if($match_flags==NULL)
		$match_flags = 0;

		if ($errors=="") {

			$sql = "SELECT * FROM ".$table." WHERE dpid=? AND match_exp=? AND id!=?";
			$stm = $link->prepare($sql);
			if ($stm===FALSE) {
				die('Failed to issue query ['.$sql.'], error message : ' . $link->errorInfo()[2]);
			}
			$stm->execute( array($dpid,$match_exp) );
			if ($stm->fetchColumn(0)>0 ) {
				$errors="Duplicate rule";
			} else {

				if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {
					for($i = 0; $i<sizeof($config->attrs_cb); $i++)
					{
						$attrs.=!isset($_POST[$config->attrs_cb[$i][0]])
						?"": $config->attrs_cb[$i][0];
						#$attrs.=' ';
					}
				} else if ($dialplan_attributes_mode == 1 ) {
					//


				}
				$sql = "UPDATE ".$table." SET dpid=?, pr = ?, ".
				"match_op=?, match_exp =?, match_flags=?, subst_exp=?, repl_exp=?, attrs=? WHERE id=?";
				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( array($dpid,$pr,$match_op,$match_exp,$match_flags,$subst_exp,$repl_exp,$attrs,$id) ) == false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
				} else {
					$info="The new rule was modified";
				}
			}
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

		$sql = "DELETE FROM ".$table." WHERE id=?";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($id) );
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

	$_SESSION['dialplan_id']=$_POST['dialplan_id'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['dialplan_id']="";
	} else if($search=="Search"){
		$_SESSION['dialplan_id']=$_POST['dialplan_id'];
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
