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
   if ($monit_user=="") {
                       $form_valid=false;
                       $form_error="- invalid <b>Monit User</b> field -";
                      }
  if ($form_valid)
   if ($monit_conn=="") {
                     $form_valid=false;
                     $form_error="- invalid <b>Monit conn</b> field -";
                    }
  if ($form_valid)
   if ($mi_conn=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Mi conn</b> field -";
                     }

  if ($form_valid)
	if ($monit_pass=="") {
		$form_valid=false;
		$form_error="=invalid <b>Password</b> field -";
	}
 
  if ($form_valid) 
	if ($confirm_monit_pass=="") {
		$form_valid=false;
		$form_error="=invalid <b>Password</b> field -";
	} 

  if ($form_valid) {
	if ($monit_pass != $confirm_monit_pass) {
		$form_valid=false;
		$form_error="- <b>Passwords do not match!<b> -";
	}
 }	

?>
