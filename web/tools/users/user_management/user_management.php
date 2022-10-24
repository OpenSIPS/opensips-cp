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
load_db_config();
require("template/header.php");
require("lib/".$page_id.".main.js");
require("../../../common/mi_comm.php");
require("../../../../config/globals.php");

session_load();

csrfguard_validate();

$table=get_settings_value("table_users");
$current_page="current_page_user_management";
$errors='';
$keepoverlay = false;
$current_tool = $page_id;

include("lib/db_connect.php");

foreach (get_settings_value("table_aliases") as $key=>$value) {
        $options[]=array("label"=>$key,"value"=>$value);
}

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

###############
# del_contact #
###############
if ($action=="delcon"){
    $mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
    for ($i=0;$i<count($mi_connectors);$i++){
	$params = array( "table_name"=>"location", "aor"=>$_POST["username"]."@".$_POST["domain"] , "contact"=>$_POST["contact"]);
        $mess=mi_command( "ul_rm_contact", $params, $mi_connectors[$i], $errors);
    }
    $keepoverlay = true;
}
###################
# end del_contact #
###################

##############
# start edit #
##############
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
##############
# end edit   #
##############

#################
# start modify	#
#################
if ($action=="modify")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$id = $_GET['id'];
		$uname = $_POST['uname'];
		$domain = $_POST['domain'];
		$email = $_POST['email'];
		$r_passwd = $_POST['r_passwd'];

		if ($uname=="" || $domain==""){
			$errors = "Invalid data (username and domain are mandatory)! No DataBase change performed";
		} else {
			if ($_POST['passwd']!="") {
				if (get_settings_value("passwd_mode")==0) {
					$ha1  = "";
		                        $sha256 = "";
		                        $sha512t256 = "";
					$passwd = $_POST['passwd'];
				} else if (get_settings_value("passwd_mode")==1) {
					$ha1 = md5($uname.":".$domain.":".$_POST['passwd']);
		                        $sha256 = hash("sha256", $uname.":".$domain.":".$_POST['passwd']);
		                        $sha512t256 = hash("sha512/256", $uname.":".$domain.":".$_POST['passwd']);
					$passwd = "";
				}
				$sql = "UPDATE ".$table." SET username=?, domain=?,
					 password=?, ha1=?, ha1_sha256=?, ha1_sha512t256=?";
				$sql_vals = array($uname,$domain,$passwd,$ha1,$sha256,$sha512t256);
				foreach ( get_settings_value("subs_extra") as $key => $value ) {
					if (!isset($_POST["extra_".$key]) || $_POST["extra_".$key] == "") {
						$value = (isset($value["default"])?$value["default"]:NULL);
					} else {
						$value = $_POST["extra_".$key];
					}
					$sql .= ", ".$key."=?";
					array_push( $sql_vals, $value);
				}
				$sql .= " WHERE id=?";
				array_push( $sql_vals, $id);

				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( $sql_vals )==false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
				} else {
					print "The user's info was modified";
				}
			} else {
				$sql = "UPDATE ".$table." SET username=?, domain=?";
				$sql_vals = array($uname,$domain);
				foreach ( get_settings_value("subs_extra") as $key => $value ) {
					if (!isset($_POST["extra_".$key]) || $_POST["extra_".$key] == "") {
						$value = (isset($value["default"])?$value["default"]:NULL);
					} else {
						$value = $_POST["extra_".$key];
					}
					$sql .= ", ".$key."=?";
					array_push( $sql_vals, $value);
				}
				$sql .= " WHERE id=?";
				array_push( $sql_vals, $id);

				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( $sql_vals ) == false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
				} else {
					print "The user's info was modified, password not changed";
				}
			}
		}
	}else{

		$errors= "User with Read-Only Rights";
	}

}
#################
# end modify 	#
#################

################
# start delete #
################
if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id = $_GET['id'];
		$uname = $_GET['uname'];
		$domain = $_GET['domain'];

		$sql = "DELETE FROM ".$table." WHERE id=?";
		$stm = $link->prepare($sql);
		if ($stm===FALSE) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($id) );

		for($i=0;$i<count($options);$i++){
			$alias_table = $options[$i]['value'];
	                $sql = "DELETE FROM ".$alias_table." WHERE username=? AND domain=?";
			$stm = $link->prepare($sql);
			if ($stm===FALSE) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			$stm->execute( array($uname, $domain) );
		}
		$sql = "DELETE FROM grp WHERE username=? AND domain=?";
		$stm = $link->prepare($sql);
		if ($stm===FALSE) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($uname, $domain) );
		
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

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['lst_uname']="";
		$_SESSION['lst_domain']="";
		$_SESSION['users']="";
		foreach (get_settings_value("subs_extra") as $key => $value)
			$_SESSION['extra_'.$key] = "";
	} else if($search=="Search"){
		$_SESSION['lst_uname']=isset($_POST['lst_uname'])?$_POST['lst_uname']:"";
		$_SESSION['lst_domain']=isset($_POST['lst_domain'])?$_POST['lst_domain']:"";
		$_SESSION['users']=$_POST['users'];
		foreach (get_settings_value("subs_extra") as $key => $value)
			if ((isset($_POST['extra_'.$key]) && $_POST['extra_'.$key]!=''))
				$_SESSION['extra_'.$key] = $_POST['extra_'.$key];
	} 
}

if ($action=="users") {
	$_SESSION['lst_uname']="";
	$_SESSION['lst_domain']="";
	$_SESSION['users']="";
}

##############
# end search #
##############


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


#################
# start add new #
#################
if ($action=="add_verify")
{
  if(!$_SESSION['read_only']){
          require("lib/".$page_id.".test.inc.php");
          if ($form_valid) {
                if (get_settings_value("passwd_mode")==1) {
		    $ha1 = md5($uname.":".$domain.":".$passwd);
		    $sha256 = hash("sha256", $uname.":".$domain.":".$passwd);
		    $sha512t256 = hash("sha512/256", $uname.":".$domain.":".$passwd);
			$passwd="";
                } else {
		    $ha1 = "";
		    $sha256 = "";
		    $sha512t256 = "";
                }
                $sql = 'INSERT INTO '.$table.' (username,domain,password,ha1,ha1_sha256,ha1_sha512t256';
		foreach ( get_settings_value("subs_extra") as $key => $value )
			if (isset($_POST['extra_'.$key]) && $_POST['extra_'.$key]!='')
				$sql .= ','.$key;
		$sql .= ') VALUES (?, ?, ?, ?, ?, ? ';
		$sql_vals = array($uname,$domain,$passwd,$ha1,$sha256,$sha512t256);
		foreach ( get_settings_value("subs_extra") as $key => $value ) {
			if (!isset($_POST['extra_'.$key]) || $_POST["extra_".$key] == "") {
				if (!isset($value["default"]))
					continue;
				$value = $value["default"];
			} else {
				$value = $_POST["extra_".$key];
			}
			$sql .= ', ?';
			array_push( $sql_vals, $value);
		}
		$sql .= ')';

                $stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( $sql_vals ) == false) {
			$errors= "Inserting user record into DB failed: ".print_r($stm->errorInfo(), true);
		} else {

			if ($alias!="") {
				$sql = 'INSERT INTO '.$alias_type.' (username,domain,alias_username,alias_domain) VALUES (?, ?, ?, ?)';
        	       		$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( array($uname,$domain,$alias,$domain) )==false) {
					$errors= "Inserting alias record into DB failed: ".print_r($stm->errorInfo(), true);
				}
			}

			$lname=NULL;
			$fname=NULL;
			$uname=NULL;
			$alias=NULL;
			$passwd=NULL;
			$confirm_passwd=NULL;

                	print "New User added!";
                	$action="add";
		}
          } else {
                print $form_error;
                $action="add_verify";
          }

} else {
        $errors= "User with Read-Only Rights";
        }
}
###############
# end add new #
###############


##############
# start main #
##############

require("template/".$page_id.".main.php");
if($errors) echo($errors);
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
