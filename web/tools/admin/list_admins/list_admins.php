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
require("../../../../config/tools/admin/list_admins/db.inc.php");
require("../../../../config/tools/admin/list_admins/local.inc.php");
require("../../../../config/db.inc.php");
include("lib/db_connect.php");
require("../../../../config/globals.php");
require_once("../../../common/cfg_comm.php");

$table=$config->table_list_admins;
$current_page="current_page_list_admins";

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
  if(!$_SESSION['read_only']){
	  $id = $_GET['id'];
          require("lib/".$page_id.".inc.php");
	  $listuname = $_POST['listuname'];	
	  $listfname = $_POST['listfname'];	
	  $listlname = $_POST['listlname'];	
          if ($form_valid) {
	  	if (($_POST['listpasswd']=="") || ($_POST['conf_passwd']=="")) {

			$sql = "UPDATE ".$table." SET username='".$listuname."', first_name='".$listfname."', last_name = '".$listlname.
				"' WHERE id=".$id;
			$resultset = $link->prepare($sql);
			$resultset->execute();
			$resultset->free();
			print "Admins info was modified, but password remained the same!\n";

		} else if (($_POST['listpasswd']!="") && ($_POST['conf_passwd']!="")) {
			if ($config->admin_passwd_mode==0) {
				$ha1  = "";
				$listpasswd = $_POST['listpasswd'];	
			} else if ($config->admin_passwd_mode==1) {
				echo "Admin passwd mode este : ".$config->admin_passwd_mode==1;
				$ha1 = md5($listuname.":".$_POST['listpasswd']);
				$listpasswd = '';	
			}

			$sql = "UPDATE ".$table." SET username='".$listuname."', first_name='".$listfname."', last_name = '".$listlname.
				"', password='".$listpasswd."', ha1='".addslashes($ha1)."' WHERE id=".$id;
			$resultset = $link->prepare($sql);
			$resultset->execute();
			$resultset->free();
			print "Admin's info was modified!\n";
		}

		$link->disconnect();
	   }
          if ($form_valid) {
                $action="edit";
          } else {
                print $form_error;
                $action="modify";
          }

  } else {
          $errors= "User with Read-Only Rights";
         }

}
#################
# end modify 	#
#################

####################
# start edit tools #
####################
if ($action=="edit_tools")
{

        //if(!$_SESSION['read_only']){

                extract($_POST);

                require("template/".$page_id.".edit_tools.php");
                require("template/footer.php");
                exit();
        //}else{
           //     $errors= "User with Read-Only Rights";
         //} 
}
##################
# end edit tools #
##################


######################
# start modify tools #
######################
if ($action=="modify_tools")
{
  if(!$_SESSION['read_only']){
	extract($_POST);
	$id = $_GET['id'];
	$uname = $_GET['uname'];
	$perm = "";
	$tool = "";
        $modules=get_modules();
	$state=$_POST['state'];
 	foreach($modules['Admin'] as $key=>$value ){
		$permissionKey = "permission_$key";
		//if (!empty($_POST["$permissionKey"])) {
			$perms[$key] = $_POST["$permissionKey"];
		//}
	}	
 	foreach($modules['Users'] as $key=>$value ){
		$permissionKey = "permission_$key";
		//if (isset($_POST["$permissionKey"])) {
			$perms[$key] = $_POST["$permissionKey"];
		//}
	}		
 	foreach($modules['System'] as $key=>$value ){
		$permissionKey = "permission_$key";
		//if (isset($_POST["$permissionKey"])) {
			$perms[$key] = $_POST["$permissionKey"];
		//}
	}
	$modules_nr = count($modules['Admin'])+count($modules['Users'])+count($modules['System']);
	if($modules_nr==count($state)) {
		$tools="all";
		if (!in_array('read-only',$perms)) {
			$permiss="all";
		} else {	
			foreach ($state as $key=>$val)
			{
				foreach($perms as $k=>$v)	
				if ($key==$k) {
					$perm .= $perms[$key].",";
				}
			
			}
			$permiss=substr($perm,0,-1);
		}	
	} else if (count($state)>0 && count($state)<$modules_nr) {
		foreach ($state as $key=>$val)
		{
			foreach($perms as $k=>$v)	
				if ($key==$k) {
					$perm .= $v.",";
					$tool .= $key.",";
			}
			
		}
		$tools=substr($tool,0,-1);
		$permiss=substr($perm,0,-1);
	} else if (count($state)==0) {
		$tools = "";
		$permiss = "";
	}
	$sql = "SELECT * FROM ".$table." where id=".$_GET['id']." AND username='".$_GET['uname']."' LIMIT 1";
	$result = $link->queryAll($sql);
	if(PEAR::isError($result)) {
        	die('Failed to issue query, error message : ' . $result->getMessage());
        }
	$uname=$result[0]['username'];
	$fname=$result[0]['first_name'];
	$lname=$result[0]['last_name'];
	$pass=$result[0]['password'];
	$ha1=$result[0]['ha1'];

        $sql = "UPDATE $table SET username='$uname', first_name='$fname', last_name = '$lname'".
    	       ", password='$pass', ha1='$ha1', available_tools='$tools', permissions='$permiss'  WHERE id=$id";
        $resultset = $link->prepare($sql);
        $resultset->execute();
        $resultset->free();
        $info="Admin credentials were modified";

                $link->disconnect();
  } else {
          $errors= "User with Read-Only Rights";
         } 


}

####################
# end modify tools #
####################


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
	                $sql = "DELETE FROM ".$alias_table." WHERE username='".$uname."' AND domain='".$domain."' AND id=".$id;
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
		$_SESSION['list_uname']="";
		$_SESSION['list_fname']="";
		$_SESSION['list_lname']="";
	} else if($search=="Search"){
		$_SESSION['list_uname']=$_POST['list_uname'];
		$_SESSION['list_fname']=$_POST['list_fname'];
		$_SESSION['list_lname']=$_POST['list_lname'];
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
