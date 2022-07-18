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

require("../../../common/cfg_comm.php");
require("template/header.php");
require("lib/".$page_id.".main.js");
require("../../../../config/globals.php");

session_load();

csrfguard_validate();

foreach (get_settings_value("table_aliases") as $key=>$value) {
	$options[]=array("label"=>$key,"value"=>$value);
}

$current_page="current_page_alias_management";


include("lib/db_connect.php");

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
# the form comes already verified here  #
####################
if ($action=="add_verified")
{
        if(!$_SESSION['read_only']){
				
                $alias_username = $_POST['alias_username'];
                $alias_domain = $_POST['alias_domain'];
                $alias_type = $_POST['alias_type'];
                $username = $_POST['username'];
                $domain = $_POST['domain'];

                
                $sql = "INSERT INTO ".$alias_type."
                (alias_username, alias_domain, username, domain) VALUES (?, ?, ?, ?)";
                $stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($alias_username,$alias_domain,$username,$domain) ) == false) {
                	$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
		} else {
	                $info="The new record was added";
        	        print "New Alias added!";
		}
	}
        else
		print "User with Read-Only Rights";
}


##################
# end add verify #
##################


#################
# start edit    #
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
# end edit      #
#############


#################
# start modify  #
#################
if ($action=="modify")
{

        $info="";
        $errors="";

        if(!$_SESSION['read_only']){

                $id = $_GET['id'];
                $user_table = $_GET['table'];
                $alias_username=$_POST['alias_username'];
                $alias_domain=$_POST['alias_domain'];
                $username = $_POST['username'];
                $domain= $_POST['domain'];

                if ($alias_username=="" || $alias_domain=="" || $username=="" || $domain=="") {
                        $errors = "Invalid data, the entry was not modified in the database";
                } else {
			$sql = "SELECT count(*) FROM ".$user_table." WHERE alias_username=? AND alias_domain=? AND id!=?";
			$stm = $link->prepare($sql);
			if ($stm === FALSE)
				die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
			$stm->execute(array($alias_username, $alias_domain, $id));
			
			if ($stm->fetchColumn(0)>0) {
				$errors = "Alias already exists!";
			} else {

	                        $sql = "UPDATE ".$user_table." SET alias_username=?, alias_domain=?, username=?, domain=? WHERE id=?";
				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute(array($alias_username, $alias_domain, $username, $domain, $id)) == false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
				} else {
	                        	$info="The alias was modified";
				}
			}
                }
        }else{

                $errors= "User with Read-Only Rights";
        }

}
#################
# end modify    #
#################



################
# start search #
################
if ($action=="") {
	$_SESSION['fromusrmgmt']=0;
	$_SESSION['username']="";
	$_SESSION['domain']="";
	$_SESSION['alias_username']="";
	$_SESSION['alias_domain']="";
	$_SESSION['alias_type']="";
}

if ($action=="dp_act")
{


	if (isset($_GET['fromusrmgmt'])) {

		$fromusrmgmt=$_GET['fromusrmgmt'];
		$_SESSION['fromusrmgmt']=1;
		$_SESSION['username']=$_GET['username'];
		$_SESSION['domain']=$_GET['domain'];
	}

	$_SESSION['alias_id']=$_POST['alias_id'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		if (isset($_SESSION['fromusrmgmt']))
			$_SESSION['fromusrmgmt']=0;
		$_SESSION['username']="";
		$_SESSION['domain']="";
		$_SESSION['alias_username']="";
		$_SESSION['alias_domain']="";
		$_SESSION['alias_type']="";
	} else if($search=="Search"){
		$_SESSION['username']=$_POST['username'];
		$_SESSION['domain']=$_POST['domain'];
		$_SESSION['alias_username']=$_POST['alias_username'];
		$_SESSION['alias_domain']=$_POST['alias_domain'];
		$_SESSION['alias_type']=$_POST['alias_type'];
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}
}
##############
# end search #
##############

################
# start delete #
################
if ($action=="delete")
{
        if(!$_SESSION['read_only']){

                $id=$_GET['id'];
		$table=$_GET['table'];

                $sql = "DELETE FROM ".$table." WHERE id=?";
		$stm = $link->prepare($sql);
		if ($stm===FALSE) {
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
