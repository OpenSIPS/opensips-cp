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


require  __DIR__.'/../googleAuth/FixedBitNotation.php';
require  __DIR__.'/../googleAuth/GoogleAuthenticatorInterface.php';
require  __DIR__.'/../googleAuth/GoogleAuthenticator.php';
require  __DIR__.'/../googleAuth/GoogleQrUrl.php';
require  __DIR__.'/../googleAuth/RuntimeException.php';

include("db_connect.php"); 
session_start();

if (isset($_POST['otp'])) {
	$otp = $_POST['otp'];
} else die;

if (!is_null($_SESSION['secret']))
	$secret = $_SESSION['secret'];
else $secret = $_SESSION['temp_secret'];

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
if ($g->checkCode($secret, $otp)) {
	if (is_null($_SESSION['secret'])) {
		$stmt = $link->prepare("UPDATE ocp_admin_privileges SET secret = ? where username = ?");
		if (!$stmt->execute(array($_SESSION['temp_secret'], $_SESSION['temp_user_login']))) {
			print_r("Failed to change db!");
			error_log(print_r($stmt->errorInfo(), true));
			die;
		}
		unset($_SESSION['temp_secret']);
	}

	$_SESSION['user_login'] = $_SESSION['temp_user_login'];
	$_SESSION['user_tabs'] = $_SESSION['temp_user_tabs'];
	$_SESSION['user_priv'] = $_SESSION['temp_user_priv'];

	header("Location:main.php");
} else {
	print_r("Incorrect code");
	die;
}
?>