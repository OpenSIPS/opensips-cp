<?php
/*
* $Id:$
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
		$match_len= $_POST['match_exp_len'];
		$subst_exp= $_POST['subst_exp'];
		$repl_exp= $_POST['repl_exp'];
		if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {

			$attrs= "";

		} else if ($dialplan_attributes_mode == 1 ) {

			$attrs= $_POST['attrs'];

		}

		if ($dpid=="" || $pr=="" || $match_exp=="" || $match_len <0){
			$errors = "Invalid data, the entry was not inserted in the database";
		}
		if($match_len=="")
		$match_len = "0";

		if ($errors=="") {
			$link = db_connect();
			if(get_magic_quotes_gpc()==0){
				$match_exp = mysql_real_escape_string($match_exp, $link);
			}
			$result=mysql_query("SELECT * FROM ".$table.
			" WHERE dpid=" .$dpid. " AND match_exp='" .$match_exp. "'")
			or die(mysql_error());
			if (mysql_num_rows($result)>0) {
				$errors="Duplicate rule";
			} else {
				for($i = 0; $i<sizeof($config->attrs_cb); $i++)
				{
					$attrs.=!isset($_POST[$config->attrs_cb[$i][0]])?
					"": $config->attrs_cb[$i][0];
					#$attrs.=' ';
				}

				if(get_magic_quotes_gpc()==0){
					if($subst_exp!="")
					$subst_exp=mysql_real_escape_string($subst_exp,	$link);
					if($repl_exp!="")
					$repl_exp=mysql_real_escape_string($repl_exp,	$link);
				}
				mysql_query("INSERT INTO ".$table."
				(dpid, pr, match_op, match_exp, match_len, subst_exp, 
				repl_exp, attrs) VALUES 
				(".$dpid.", ".$pr.",".$match_op.", '".$match_exp."',".
				$match_len.",'" .$subst_exp. "','" .$repl_exp. "','" .
				$attrs. "')")

				or die(mysql_error());

				$info="The new rule was added";
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
			$link = db_connect();

			$result=mysql_query("SELECT * FROM ".$table.
			" WHERE dpid=" .$src_dpid)
			or die(mysql_error());

			$nb_rows = mysql_num_rows($result);

			if ($nb_rows==0) {
				$errors="No rules to duplicate";
			} else {
				while($row=mysql_fetch_array($result))
				{
					mysql_query("INSERT INTO ".$table.
					"(dpid, pr, match_op, match_exp, match_len, subst_exp,
					repl_exp, attrs) VALUES (".$dst_dpid.", ".
					$row['pr'].", ".$row['match_op'].
					", '".$row['match_exp']."', ".$row['match_len'].
					", '" .$row['subst_exp']."', '".$row['repl_exp'].
					"', '".$row['attrs']."')")
					or die(mysql_error());

				}
				$info="The dialplan was cloned";
			}
			db_close();
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
		$match_len= $_POST['match_exp_len'];
		$subst_exp= $_POST['subst_exp'];
		$repl_exp= $_POST['repl_exp'];

		if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {

			$attrs= "";

		} else if ($dialplan_attributes_mode == 1 ) {

			$attrs= $_POST['attrs'];

		}

		if ($dpid=="" || $pr=="" || $match_exp=="" || $match_len <0){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if($match_len=="")
		$match_len = "0";

		if ($errors=="") {
			$link = db_connect();
			if(get_magic_quotes_gpc()==0){

				$match_exp = mysql_real_escape_string($match_exp, $link);
			}

			$result=mysql_query("SELECT * FROM ".$table.
			" WHERE dpid=" .$dpid. " AND match_exp='" .$match_exp. "'".
			" AND id!=".$id)
			or die(mysql_error());
			if (mysql_num_rows($result)>0) {
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
				if(get_magic_quotes_gpc()==0){
					if($subst_exp!="")
					$subst_exp	= mysql_real_escape_string($subst_exp,	$link);
					if($repl_exp!="")
					$repl_exp	= mysql_real_escape_string($repl_exp,	$link);
				}
				mysql_query("UPDATE ".$table." SET dpid=".$dpid.", pr = ".$pr.
				", match_op= ".$match_op.", match_exp ='".$match_exp.
				"', match_len=".$match_len.", subst_exp = '" .$subst_exp.
				"', repl_exp='" .$repl_exp. "', attrs= '".$attrs."'".
				" WHERE id=".$id)

				or die(mysql_error());

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

	$_SESSION['dialplan_id']=$_POST['dialplan_id'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['dialplan_id']="";
	} else if($search=="Search"){
		$_SESSION['dialplan_id']=$_POST['dialplan_id'];
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";
	}else if($clone=="Clone Dialplan"){

		require("template/".$page_id.".clone_dp.php");
		require("template/footer.php");
		exit();

	}else if($delete=="Delete Dialplan"){

		$dpid = $_POST['dialplan_id'];
		if($dpid =="")
		$errors = "Empty Dialplan ID";

		if($errors=="")
		{
			db_connect();
			$result=mysql_query("SELECT * FROM ".$table.
			" WHERE dpid=" .$dpid)
			or die(mysql_error());

			if (mysql_num_rows($result)==0) {
				$errors="No Rule with such Dialplan ID";
				$_SESSION['dialplan_id']="";

			}else{

				mysql_query("DELETE FROM ".$table." WHERE dpid=".$dpid)
				or die(mysql_error());
			}
			db_close();
		}
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
