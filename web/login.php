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
 $config = (object)array();

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
  $stmt = $link->prepare("SELECT * FROM ocp_admin_privileges WHERE username = ? and password = ?");
  $credentials = array($name, $password);

} else if ($config->admin_passwd_mode==1) {
  $ha1 = md5($name.":".$password);
  $password='';

  $stmt = $link->prepare("SELECT * FROM ocp_admin_privileges WHERE username = ? AND ha1 = ?");
  $credentials = array($name, $ha1);
}

if (!$stmt->execute($credentials)) {
	print_r("Failed to fetch credentials!");
	error_log(print_r($stmt->errorInfo(), true));
	die;
}

$resultset = $stmt->fetchAll();

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
