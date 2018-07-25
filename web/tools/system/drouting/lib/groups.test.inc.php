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
  $form_valid=true;
  if ($form_valid)
   if ($username=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>Username</b> field -";
                      }
  if ($form_valid)
   if ($domain=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Domain</b> field -";
                    }
  if ($form_valid)
   if ($groupid=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Group ID</b> field -";
                     }
  if ($form_valid)
   if (!is_numeric($groupid)) {
                               $form_valid=false;
                               $form_error="- <b>Group ID</b> field must be numeric -";
                              }
  if ($form_valid)
   if ($groupid<0) {
                    $form_valid=false;
                    $form_error="- <b>Group ID</b> field must be a positive number -";
                   }
  if ($form_valid) {
                    $sql="select * from ".$table." where username=? and domain=?";
		    $stm = $link->prepare($sql);
		    if ($stm === false) {
		    	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		    }
		    $stm->execute( array($username,$domain) );
		    $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
                    $data_rows=count($resultset);
                    if (($data_rows>0) && (($resultset[0]['username']!=$id_username) || ($resultset[0]['domain']!=$id_domain)))
                    {
                     $form_valid=false;
                     $new_id=$username."@".$domain;
                     $form_error="- <b>".$new_id."</b> is already a valid user -";
                    }
                   }

?>
