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
  if ($form_valid)
   if ($add_fname=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>First Name</b> field -";
                      }
  if ($form_valid)
   if ($add_lname=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Last Name</b> field -";
                    }
  if ($form_valid)
   if ($add_uname=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Username</b> field -";
                     }

  if ($form_valid)
	if ($add_passwd=="") {
		$form_valid=false;
		$form_error="=invalid <b>Password</b> field -";
	}
 
  if ($form_valid) 
	if ($confirm_passwd=="") {
		$form_valid=false;
		$form_error="=invalid <b>Password</b> field -";
	} 

  if ($form_valid) {
	if ($add_passwd != $confirm_passwd) {
		$form_valid=false;
		$form_error="- <b>Passwords do not match!<b> -";
	}
 }	
 
  if ($form_valid) {
		if ($config->admin_passwd_mode==0) {
  		     $ha1  = "";
	        } else if ($config->admin_passwd_mode==1) {
       	 	     $ha1 = md5($add_uname.":".$add_passwd);
 	        } else {
	  	   $form_valid = false;
                  $form_error = "- <b> Unknow value for password mode!<b> -";
	        }
	}


  if ($form_valid) {
                    $sql="select count(*) from ".$table." where username=?";
		    $stm = $link->prepare($sql);
		    if ($stm === FALSE)
		    	die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
		    $stm->execute(array($add_uname));
		    $data_no = $stm->fetchColumn(0);;
                    if ( $data_no>0 )
                    {
                     $form_valid=false;
                     $new_id=$add_uname;
                     $form_error="- <b>".$new_id."</b> is already exists as admin -";
                    }
                   }

?>
