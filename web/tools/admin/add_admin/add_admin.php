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
include("lib/db_connect.php");
require("../../../../config/tools/admin/add_admin/local.inc.php");
require("../../../../config/globals.php");
$table=$config->table_addadmin;

$current_page="current_page_add_admin";

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
                require("template/".$page_id.".main.php");
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
                if ($config->admin_passwd_mode==0) {
                	$ha1  = '';
                        $add_passwd = $_POST['add_passwd'];
                } else if ($config->admin_passwd_mode==1) {
                        $ha1 = md5($add_uname.":".$_POST['add_passwd']);
                        $add_passwd = '';
                }


		$sql = 'INSERT INTO '.$table.' (last_name,first_name,username,password,ha1) VALUES '. 
		' (\''.$add_lname.'\',\''.$add_fname.'\',\''. $add_uname . '\',\''. $add_passwd.'\',\''.$ha1.'\')';
		$resultset = $link->prepare($sql);

		$resultset->execute();
		$resultset->free();

		$link->disconnect();

		$lname=NULL;
		$fname=NULL;
		$uname=NULL;
		$passwd=NULL;
		$confirm_passwd=NULL;
	}
	  if ($form_valid) {
		print "New Admin added!";
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

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?>
