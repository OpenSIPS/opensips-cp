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
 require("../config/modules.inc.php");
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
	$err = 1;
	if (isset($config->lockout_failed_attempts)) {
		/* get the user's profile to see if it has to be locked out */
		$stmt = $link->prepare("SELECT * FROM ocp_admin_privileges WHERE username = ?");
		if (!$stmt->execute(array($name))) {
			print_r("Failed to login!");
			error_log(print_r($stmt->errorInfo(), true));
			die;
		}
		$resultset = $stmt->fetchAll();
		if (isset($resultset) && count($resultset)!=0) {
			if ($resultset[0]['blocked'] == NULL) {
				if ($resultset[0]['failed_attempts'] + 1 >= $config->lockout_failed_attempts) {
					$query = "UPDATE ocp_admin_privileges SET blocked = NOW(), failed_attempts = failed_attempts + 1 WHERE username = ?";
					$err = 3;
				} else {
					$query = "UPDATE ocp_admin_privileges SET failed_attempts = failed_attempts + 1 WHERE username = ?";
				}
				$stmt = $link->prepare($query);
				if (!$stmt->execute(array($name))) {
					print_r("Failed to login!");
					error_log(print_r($stmt->errorInfo(), true));
					die;
				}
			} else {
				$block_time = strtotime($resultset[0]['blocked']);
				if ($block_time + $config->lockout_block_time < $_SERVER['REQUEST_TIME']) {		
					$query = "UPDATE  ocp_admin_privileges SET blocked = NULL, failed_attempts = 1 WHERE username = ?";
				} else {
					$err = 3;
				}
				if ($query != NULL) {
					$stmt = $link->prepare($query);
					if (!$stmt->execute(array($name))) {
						print_r("Failed to login!");
						error_log(print_r($stmt->errorInfo(), true));
						die;
					}
				}
			}
		}
	}
	header("Location:index.php?err=$err");
	exit();
}

if (isset($config->lockout_failed_attempts)) {
	$err = NULL;
	$query = NULL;
	if ($resultset[0]['blocked'] != NULL) {
		$block_time = strtotime($resultset[0]['blocked']);
		if ($block_time + $config->lockout_block_time < $_SERVER['REQUEST_TIME']) {
			$query = "UPDATE  ocp_admin_privileges SET blocked = NULL, failed_attempts = 0 WHERE username = ?";
		} else {
			$err = 3;
		}
	} else if ($resultset[0]['failed_attempts'] != 0) {
		$query = "UPDATE  ocp_admin_privileges SET failed_attempts = 0 WHERE username = ?";
	}
	if ($query != NULL) {
		$stmt = $link->prepare($query);
		if (!$stmt->execute(array($name))) {
			print_r("Failed to login!");
			error_log(print_r($stmt->errorInfo(), true));
			die;
		}
	}
	if ($err != NULL) {
		header("Location:index.php?err=$err");
		exit();
	}
}

$avail_tools = $resultset[0]['available_tools'];
$avail_perms = $resultset[0]['permissions'];

$_SESSION['temp_user_login'] = $name;
if (!is_null($resultset[0]['secret']))
	$_SESSION['secret'] = $resultset[0]['secret'];
else unset($_SESSION['secret']);

if ($avail_tools == "all") {;
	$_SESSION['temp_user_tabs'] = "*";
} else {
	$_SESSION['temp_user_tabs'] = $avail_tools;
}
if ($avail_perms == "all") { 
	$_SESSION['temp_user_priv'] = "*";
} else { 
	$_SESSION['temp_user_priv'] = $avail_perms; 
}
$login_ok=true;
$log = "[OK]  [".date("d-m-Y")." ".date("H:i:s")."] '$name' from '".$_SERVER['REMOTE_ADDR']."'\n";
if ($config->twoFactor)
	header("Location:auth_index.php");
else {
	$_SESSION['user_login'] = $_SESSION['temp_user_login'];
	$_SESSION['user_tabs'] = $_SESSION['temp_user_tabs'];
	$_SESSION['user_priv'] = $_SESSION['temp_user_priv'];

	
	$dashboard = false;
	$default_path = NULL;
	foreach ($config_modules as $menuitem => $menuitem_config) {
		if (!$menuitem_config['enabled'])
			continue;
		if (!isset($menuitem_config['modules']))
			continue;
		if (isset($menuitem_config['modules']['dashboard'])
			&& $menuitem_config['modules']['dashboard']['enabled'])
			$dashboard = true;
		foreach ($menuitem_config['modules'] as $module => $values) {
			if (isset($values['enabled']) && !$values['enabled'])
				continue;
			if (isset($values['default']) && $values['default']) {
				$default_path = 'tools/';
				if (!isset($value['path']))
					$default_path .= $menuitem . '/' . $module;
				else
					$default_path .= $value['path'];
				$default_path .= '/index.php';
				if (!file_exists($default_path))
					$default_path = NULL;
			}
		}
	}

	if ($default_path != NULL) {
		$_SESSION['path'] = $default_path;
	} else if ($dashboard) {
		$query = "SELECT COUNT(*) as panel_no FROM ocp_dashboard;";
		$stmt = $link->prepare($query);
		if (!$stmt->execute(NULL)) {
			print_r("Failed to fetch db!");
			error_log(print_r($stmt->errorInfo(), true));
			die;
		}
		$resultset = $stmt->fetchAll();
		if ($resultset[0]['panel_no'] > 0)
			$_SESSION['path'] = "tools/system/dashboard/dashboard.php";
	}
	header("Location:main.php");
}
exit();
?>
