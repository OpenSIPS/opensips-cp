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


  extract($_POST);
  global $config;

  $form_valid=true;

  if ($uname=="") {
		$form_valid=false;
		$form_error="- invalid <b>Username</b> field -";
  } else 
  if ($domain=='' || $domain=='ANY') {
		$form_valid=false;
		$form_error="- invalid <b>Domain</b> field -";
  } else 
  if ($passwd=="") {
		$form_valid=false;
		$form_error="- invalid <b>Password</b> field -";
  } else 
  if ($confirm_passwd=="") {
		$form_valid=false;
		$form_error="- invalid <b>Confirm Password</b> field-";	
  } else 
  if ($passwd != $confirm_passwd) {
		$form_valid=false;
		$form_error="- <b>Passwords do not match!<b> -";
  } else {
  		// check for SIP account duplicate
		$sql="select count(*) from ".$table." where username=? and domain=?";
		$stm = $link->prepare($sql);
		if ($stm === FALSE)
			die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
		$stm->execute(array($uname, $domain));
		$data_no = $stm->fetchColumn(0);;
		if ($data_no!=0) {
			$form_valid=false;
			$new_id=$uname."@".$domain;
			$form_error="- <b>".$new_id."</b> is already a valid user -";
		} else if ($alias!=""){
  			// check for SIP alias duplicate
			$sql = "select count(*) from ".$alias_type." where alias_username=? and alias_domain=?";
			$stm = $link->prepare($sql);
			if ($stm === FALSE)
				die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
			$stm->execute(array($alias, $domain));
			$data_no = $stm->fetchColumn(0);;
			if ($data_no!=0) {
				$form_valid=false;
				$new_id=$alias."@".$domain;
				$form_error="- <b>".$new_id."</b> is already a valid alias -";
			}
		}
	}

?>
