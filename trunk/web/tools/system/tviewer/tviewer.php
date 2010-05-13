<?php
/*
* $Id: dispatcher.php 155 2009-12-10 12:46:38Z iulia_bublea $
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
include("lib/db_connect.php");

$table=$config->custom_table;
$current_page="current_page_tviewer";

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
	$fields="";
	$values="";
	extract($_POST);
	foreach ($config->custom_table_columns as $key => $value){
		$fields.=$value.",";
		$values.="'".$_POST[$value]."',";
	}	
	$fields = substr($fields,0,-1);
	$values = substr($values,0,-1);
	if(!$_SESSION['read_only']){


		if ($errors=="") {

                                $sql = "INSERT INTO ".$table."
                                (".$fields.") VALUES
                                (".$values.") ";
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

		
		$updatestring="";
        	extract($_POST);
	        foreach ($config->custom_table_columns as $key => $value){
                	$updatestring=$updatestring.$value."='".$_POST[$value]."',";
        	}
		
	        $updatestring = substr($updatestring,0,-1);
		if ($errors=="") {
				$sql = "UPDATE ".$table." SET ".$updatestring." WHERE ".$config->custom_table_primary_key."=".$_GET['id'];
				$resultset = $link->prepare($sql);
				$resultset->execute();
				$resultset->free();
				$info="The rule was modified";
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

		$sql = "DELETE FROM ".$table." WHERE ".$config->custom_table_primary_key."=".$id;
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
		foreach ($config->custom_table_columns as $key => $value){	
			$_SESSION[$value]="";
		}
	} else if($search=="Search"){
		foreach ($config->custom_table_columns as $key => $value){
                        $_SESSION[$value]=$_POST[$value];
                }
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

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
