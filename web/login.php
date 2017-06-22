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

 session_start();
 include("db_connect.php"); 
 require("../config/globals.php");
 global $config;	
 $super_admin=0;

 if (isset($_POST['name'])) {
	$name = $_POST['name'];
 } else {
	$name = "";
 }


 if (isset($_POST['password'])) $password = $_POST['password'];
  else $password = "";

 if ($name=="" || $password=="") {
  				      $log = "[NOK] [".date("d-m-Y")." ".date("H:i:s")."] '$name' / '$password' from '".$_SERVER['REMOTE_ADDR']."'\n";
                                      header("Location:index.php?err=1");
                                      exit();
                                     }

$login_ok = false;

if ($config->admin_passwd_mode==0) {
    $ha1  = '';

    $sql = "select * from ocp_admin_privileges where username = ? and password = ?";

    $sth = $link->prepare($sql);
    $credentials = array($name,$password);
    $result1 = $sth->execute($credentials);
    if(PEAR::isError($result1)) {
        die('Failed to issue query, error message : ' . $result1->getMessage());
    }
    $resultset = $result1->fetchAll();
    $sth->free();

} else if ($config->admin_passwd_mode==1) {
    $ha1 = md5($name.":".$password);
    $password='';

    $sql = "SELECT * FROM ocp_admin_privileges WHERE username= ? AND ha1= ?";

    $sth = $link->prepare($sql,MDB2_PREPARE_RESULT);
    $credentials = array($name,$ha1);
    $result2 = $sth->execute($credentials);
    if(PEAR::isError($result2)) {
        die('Failed to issue query, error message : ' . $result2->getMessage());
    }
    $resultset = $result2->fetchAll();
    $sth->free();
}

if (isset($resultset) && count($resultset)==0) {
    $log = "[NOK] [".date("d-m-Y")." ".date("H:i:s")."] '$name' / '$password' from '".$_SERVER['REMOTE_ADDR']."'\n";
    header("Location:index.php?err=1");
        exit();
}

$avail_tools = $resultset[0]['available_tools'];
$avail_perms = $resultset[0]['permissions'];

$_SESSION['user_login'] = $name;

if ($avail_tools == "all") {;
	$_SESSION['user_tabs'] = "*";
} else {
	$_SESSION['user_tabs'] = $avail_tools;
}
if ($avail_perms == "all") { 
	$_SESSION['user_priv'] = "*";
} else { 
	$_SESSION['user_priv'] = $avail_perms; 
}
$login_ok=true;
$log = "[OK]  [".date("d-m-Y")." ".date("H:i:s")."] '$name' from '".$_SERVER['REMOTE_ADDR']."'\n";
header("Location:main.php");
exit();
?>
