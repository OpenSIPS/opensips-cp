<?php
/*
* $Id$
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
require("../../../common/mi_comm.php");
require("../../../../config/globals.php");
include("lib/db_connect.php");

$table=$config->table_users;
$current_page="current_page_user_management";

foreach ($config->table_aliases as $key=>$value) {
        $options[]=array("label"=>$key,"value"=>$value);
}

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;


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

		if ($uname=="" || $domain=="" || $email==""){
			$errors = "Invalid data, the entry was not modified in the database";
		}
               if ($config->passwd_mode==0) {
                     $ha1  = "";
                     $ha1b = "";
		     $passwd = $_POST['passwd'];
               } else if ($config->passwd_mode==1) {
                     $ha1 = md5($uname.":".$domain.":".$_POST['passwd']);
                     $ha1b = md5($uname."@".$domain.":".$domain.":".$_POST['passwd']);
		     $passwd = "";
               }

		if ($errors=="") {
			if ($_POST['passwd']!="") {
				if (($r_passwd!="")&&($_POST['passwd']==$r_passwd)) {
					$sql = 'UPDATE '.$table.' SET username="'.$uname.'", domain="'.$domain.'",
						 email_address= "'.$email.'",password="'.$passwd.'",ha1="'.$ha1.'", ha1b="'.$ha1b.'" WHERE id='.$id;
					$resultset = $link->prepare($sql);
					$resultset->execute();
					$resultset->free();
					print "The user's info was modified";
					$link->disconnect();
				}	
			} else {
					$sql = 'UPDATE '.$table.' SET username="'.$uname.'", domain="'.$domain.'",
						 email_address= "'.$email.'" WHERE id='.$id;
					$resultset = $link->prepare($sql);
					$resultset->execute();
					$resultset->free();
					print "The user's info was modified, but password remained the same";
					$link->disconnect();
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

		$sql = "DELETE FROM ".$table." WHERE id=".$id;
		$link->exec($sql);
		for($i=0;$i<count($options);$i++){
			$alias_table = $options[$i]['value'];
	                $sql = 'DELETE FROM '.$alias_table.' WHERE username="'.$uname.'" AND domain="'.$domain.'"';
	                $link->exec($sql);

		}
		
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

	$_SESSION['list_id']=$_POST['list_id'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['lst_uname']="";
		$_SESSION['lst_domain']="";
		$_SESSION['lst_email']="";
		$_SESSION['users']="";
	} else if($search=="Search"){
		$_SESSION['lst_uname']=$_POST['lst_uname'];
		$_SESSION['lst_domain']=$_POST['lst_domain'];
		$_SESSION['lst_email']=$_POST['lst_email'];
		$_SESSION['users']=$_POST['users'];
	} 
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
                if ($config->passwd_mode==1) $passwd="";
                $sql = 'INSERT INTO '.$table.' (last_name, first_name, username,domain,password,email_address,ha1,ha1b,datetime_created) VALUES '.
                ' (\''.$lname.'\',\''.$fname.'\',\''. $uname . '\',\'' . $domain.'\',\''. $passwd.'\',\''.
                $email.'\',\''.$ha1.'\',\''.$ha1b.'\', NOW())';

                $resultset = $link->prepare($sql);

                $resultset->execute();
                $resultset->free();

                $sql = 'INSERT INTO '.$alias_type.' (username,domain,alias_username,alias_domain) VALUES '.
                ' (\''. $uname . '\',\'' . $domain.'\',\''. $alias.'\',\''.$domain.'\')';

                $resultset = $link->prepare($sql);

                $resultset->execute();
                $resultset->free();

                $link->disconnect();

                $lname=NULL;
                $fname=NULL;
                $uname=NULL;
                $email=NULL;
                $alias=NULL;
                $passwd=NULL;
                $confirm_passwd=NULL;

        }
          if ($form_valid) {
                print "New User added!";
                $action="add";
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
if($errors)
echo('!!! ');echo($errors);
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
