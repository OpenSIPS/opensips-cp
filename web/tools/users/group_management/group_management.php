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

$errors='';
$current_page="current_page_group_management";

session_load();

csrfguard_validate();

$table=get_settings_value('table_groups');

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
				
                $group_username = $_POST['username'];
                $group_domain = $_POST['domain'];
                $group_grp = $_POST['group'];

                
                $sql = "INSERT INTO ".$table.
                       "(username, domain, grp, last_modified) VALUES ".
		       "(?, ?, ?, NOW())";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($group_username, $group_domain, $group_grp) ) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
		} else {
               		$info="The new record was added";
                	print "New Group added!";
		}
	}
        else
                $errors= "User with Read-Only Rights";
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
                $group_username=$_POST['username'];
                $group_domain=$_POST['domain'];
                $group_grp = $_POST['group'];

                if ($group_username=="" || $group_domain=="" || $group_grp==""){
                        $errors = "Invalid data, the entry was not modified in the database";
                } else {
				
			$sql = "select count(*) from subscriber where username=? and domain=?";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			$stm->execute( array($group_username, $group_domain) );
			if ($stm->fetchColumn(0)<1) {
				$errors="This user does not exist !!!";
			} else {
	
	                	$sql = "SELECT * FROM ".$table." WHERE username=? AND domain=? AND grp=? AND id!=?";
				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				$stm->execute( array($group_username, $group_domain, $group_grp, $id) );
				if ($stm->fetchColumn(0)>0) {
					$errors="The user already belongs in this group !!!";
				}
			}
		}

                if ($errors=="") {
                        $sql = "UPDATE ".$table." SET username=?, domain=?, grp=? WHERE id=?";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			if ($stm->execute( array($group_username, $group_domain, $group_grp, $id) ) == false) {
				$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
			} else {
                        	$info="The Group was modified";
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
	$_SESSION['grp_username']=NULL;
        $_SESSION['grp_domain']=NULL;
	$_SESSION['grp_group']=NULL;
}
if ($action=="dp_act")
{
	if (isset($_GET['fromusrmgmt'])) {
		
		$fromusrmgmt=$_GET['fromusrmgmt'];
			
		$_SESSION['fromusrmgmt']=1;
		$_SESSION['grp_username']=$_GET['username'];
	        $_SESSION['grp_domain']=$_GET['domain'];
	}

        $_SESSION['grp_id']=$_POST['grp_id'];

        $_SESSION[$current_page]=1;
        extract($_POST);
        if ($show_all=="Show All") {
                $_SESSION['grp_username']="";
                $_SESSION['grp_domain']="";
                $_SESSION['grp_group']="";
        } else if($search=="Search"){
                $_SESSION['grp_username']=$_POST['username'];
                $_SESSION['grp_domain']=$_POST['domain'];
                $_SESSION['grp_group']=$_POST['group'];
        } else if($_SESSION['read_only']){

                $errors= "User with Read-Only Rights";

	}
	if ($delete=="Delete") {
        	$sql_query = "";
		$sql_vals = array();
        	if( $_POST['username'] != "" ) {
                        $group_username = $_POST['username'];
                        $sql_query .= " AND username like ?";
			array_push( $sql_vals, "%".$group_username."%");
                }
                if( ($_POST['domain']!="ANY") && ($_POST['domain']!="") ) {
			$group_domain = $_POST['domain'];
                        $sql_query .= " AND domain like ?";
			array_push( $sql_vals, $group_domain);
		}
		if ($_POST['group']!="ANY"){
			$group_grp = $_POST['group'];
			$sql_query .= "AND grp =?";
			array_push( $sql_vals, $group_grp);
		}
        	$sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
                $stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( $sql_vals );
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
