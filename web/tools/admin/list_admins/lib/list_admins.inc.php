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
   if ($listfname=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>First Name</b> field -";
                      }
  if ($form_valid)
   if ($listlname=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Last Name</b> field -";
                    }
  if ($form_valid)
   if ($listuname=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Username</b> field -";
                     }

  if ($form_valid) {
	if ($listpasswd != $conf_passwd) {
		$form_valid=false;
		$form_error="- <b>Passwords do not match!<b> -";
	}
  
       if (!isset($config->admin_passwd_mode)) {
  	      $form_valid = false;
             $form_error = "- <b> Unknow value for password mode!<b> -";
       }

	
  }	


  if ($form_valid) {
                    $sql="select * from ".$table." where username='".$listuname."'";
		    $resultset = $link->queryAll($sql);
                    if(PEAR::isError($resultset)) {
                    	die('Failed to issue query, error message : ' . $resultset->getMessage());
                    }
                    $data_rows=count($resultset);
                    if (($data_rows>0) && ($resultset[0]['username']==$listuname) && ($resultset[0]['first_name']==$listfname) && ($resultset[0]['last_name']==$listlname) &&($resultset[0]['password']==$listpasswd) )
                    {
                     $form_valid=false;
                     $new_id=$uname;
                     $form_error="- <b>".$new_id."</b> is already a valid admin -";
                    }
                   }

?>
