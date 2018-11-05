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
require("../../../../config/globals.php");
require("../../../../config/tools/users/acl_management/local.inc.php");
require("../../../common/cfg_comm.php");
include("lib/db_connect.php");

$table=$config->table_acls;
$errors='';

$current_page="current_page_acl_management";
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
				
                $acl_username = $_POST['username'];
                $acl_domain = $_POST['domain'];
                $acl_grp = $_POST['acl_grp'];

                
                $sql = "INSERT INTO ".$table.
                       "(username, domain, grp, last_modified) VALUES ".
		       "(?, ?, ?, NOW())";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($acl_username, $acl_domain, $acl_grp) ) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
		} else {
               		$info="The new record was added";
                	print "New ACL added!";
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
                $acl_username=$_POST['username'];
                $acl_domain=$_POST['domain'];
                $acl_grp = $_POST['acl_grp'];

                if ($acl_username=="" || $acl_domain=="" || $acl_grp==""){
                        $errors = "Invalid data, the entry was not modified in the database";
                } else {
				
			$sql = "select count(*) from subscriber where username=? and domain=?";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			$stm->execute( array($acl_username, $acl_domain) );
			if ($stm->fetchColumn(0)<1) {
				$errors="This user does not exist !!!";
			} else {
	
	                	$sql = "SELECT * FROM ".$table." WHERE username=? AND domain=? AND grp=? AND id!=?";
				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				$stm->execute( array($acl_username, $acl_domain, $acl_grp, $id) );
				if ($stm->fetchColumn(0)>0) {
					$errors="The ACL already exists for this user !!!";
				}
			}
		}

                if ($errors=="") {
                        $sql = "UPDATE ".$table." SET username=?, domain=?, grp=? WHERE id=?";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			if ($stm->execute( array($acl_username, $acl_domain, $acl_grp, $id) ) == false) {
				$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
			} else {
                        	$info="The ACL was modified";
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
if ($action=="dp_act")
{
	if (isset($_GET['fromusrmgmt'])) {
		
		$fromusrmgmt=$_GET['fromusrmgmt'];
			
		$_SESSION['fromusrmgmt']=1;
		$_SESSION['acl_username']=$_GET['username'];
	        $_SESSION['acl_domain']=$_GET['domain'];
	}

        $_SESSION['acl_id']=$_POST['acl_id'];

        $_SESSION[$current_page]=1;
        extract($_POST);
        if ($show_all=="Show All") {
                $_SESSION['acl_username']="";
                $_SESSION['acl_domain']="";
                $_SESSION['acl_grp']="";
        } else if($search=="Search"){
                $_SESSION['acl_username']=$_POST['acl_username'];
                $_SESSION['acl_domain']=$_POST['acl_domain'];
                $_SESSION['acl_grp']=$_POST['acl_grp'];
        } else if($_SESSION['read_only']){

                $errors= "User with Read-Only Rights";

        }else if($delete=="Delete ACL"){
                $sql_query = "";
		$sql_vals = array();
                if( $_POST['acl_username'] != "" ) {
                        $acl_username = $_POST['acl_username'];
                        $sql_query .= " AND username like ?";
			array_push( $sql_vals, "%".$acl_username."%");
                }
                if( ($_POST['acl_domain']!="ANY") && ($_POST['acl_domain']!="") ) {
			$acl_domain = $_POST['acl_domain'];
                        $sql_query .= " AND domain like ?";
			array_push( $sql_vals, $acl_domain);
		}
		if ($_POST['acl_grp']!="ANY"){
			$acl_grp = $_POST['acl_grp'];
			$sql_query .= "AND grp =?";
			array_push( $sql_vals, $acl_grp);
		}
        	$sql = "DELETE FROM ".$table." WHERE (1=1) " . $sql_query;
                $stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( $acl_vals );
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
