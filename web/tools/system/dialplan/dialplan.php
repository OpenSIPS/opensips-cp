<?php
/*
 * Copyright (C) 2011-2019 OpenSIPS Project
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

require("../../../common/cfg_comm.php");
require("template/header.php");
require("lib/".$page_id.".main.js");
require("../../../common/mi_comm.php");
session_load();

csrfguard_validate();

$table=get_settings_value("table_dialplan");
$dialplan_attributes_mode = get_settings_value("dialplan_attributes_mode");
$current_page="current_page_dialplan";

include("lib/db_connect.php");

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

$info="";
$errors="";

if ( $_SESSION['read_only'] && $action!="search") {
	$errors= "User with Read-Only Rights";
} else

#################
# start add new #
#################

if ($action=="add")
{
	extract($_POST);
	require("template/".$page_id.".add.php");
	require("template/footer.php");
	exit();
} else

#################
# end add new   #
#################


###############
# start clone #
###############

if ($action=="clone")
{
	extract($_POST);
	require("template/".$page_id.".add.php");
	require("template/footer.php");
	exit();
} else

###############
# end clone   #
###############


####################
# start add verify #
####################

if ($action=="add_do")
{
	$dpid=$_POST['dpid'];
	if (!isset($dpid))
		$dpid = get_settings_value("dialplan_groups");
	$pr=$_POST['pr'];
	$match_op = $_POST['match_op'];
	$match_exp= $_POST['match_exp'];
	$match_flags= $_POST['match_flags'];
	$subst_exp= $_POST['subst_exp'];
	$repl_exp= $_POST['repl_exp'];
	$match_only= $_POST['match_only'];

	if ( !isset($dialplan_attributes_mode) || $dialplan_attributes_mode == 1) {
		$attrs= $_POST['attrs'];
	} else {
		$attrs="";
		foreach( get_settings_value("attrs_cb") as $key => $val )
			$attrs.=!isset($_POST["dp_attr_".$key]) ? "" : $key ;
	}
	if ($match_only == 1) {
		$subst_exp= NULL;
		$repl_exp= NULL;
	}

	$sql = "INSERT INTO ".$table."
		(dpid, pr, match_op, match_exp, match_flags, subst_exp, repl_exp, attrs) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; 
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array($dpid,$pr,$match_op,$match_exp,$match_flags,$subst_exp,$repl_exp,$attrs) ) == false ) {
		$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
	} else {
		$info="The new rule was added";
	}
} else

##################
# end add verify #
##################


#################
# start edit	#
#################

if ($action=="edit")
{
	extract($_POST);
	require("template/".$page_id.".edit.php");
	require("template/footer.php");
	exit();
} else

#############
# end edit  #
#############


#################
# start modify	#
#################
if ($action=="modify")
{
	$id = $_GET['id'];
	$dpid=$_POST['dpid'];
	if (!isset($dpid))
		$dpid = get_settings_value("dialplan_groups");
	$pr=$_POST['pr'];
	$match_op = $_POST['match_op'];
	$match_exp= $_POST['match_exp'];
	$match_flags= $_POST['match_flags'];
	$subst_exp= $_POST['subst_exp'];
	$repl_exp= $_POST['repl_exp'];
	$match_only= $_POST['match_only'];

	if ( !isset($dialplan_attributes_mode) || $dialplan_attributes_mode == 1) {
		$attrs= $_POST['attrs'];
	} else {
		$attrs="";
		foreach( get_settings_value("attrs_cb") as $key => $val )
			$attrs.=!isset($_POST["dp_attr_".$key]) ? "" : $key ;
	}
	if ($match_only == 1) {
		$subst_exp= NULL;
		$repl_exp= NULL;
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

} else 

#################
# end modify	#
#################


################
# start delete #
################

if ($action=="delete")
{
	$id=$_GET['id'];

	$sql = "DELETE FROM ".$table." WHERE id=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( array($id) );
} else

##############
# end delete #
##############


################
# start search #
################

if ($action=="search")
{

	extract($_POST);
	$_SESSION['dialplan_id']=$_POST['dialplan_id'];
	$_SESSION[$current_page]=1;

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
if ($errors!="") echo('<tr><td align="center"><div class="formError">'.$errors.'</div></td></tr>');
if ($info!="") echo('<tr><td  align="center"><div class="formInfo">'.$info.'</div></td></tr>');
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
