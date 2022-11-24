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

include("db_connect.php");
  extract($_POST);
  $form_valid=true;

  if ($form_valid)
   if (!preg_match('/^[0-9a-zA-Z_\-]+$/',$gwid)){
                      $form_valid=false;
                      $form_error="- <b>GWID</b> is invalid, must contain alpha-numeric chars or '_' or '-' -";
                     }
  if ($form_valid)
   if ($address=="") {
                      $form_valid=false;
                      $form_error="- invalid <b>Address</b> field -";
                     }
  if ($form_valid)
   if ($strip=="") {
                    $form_valid=false;
                    $form_error="- invalid <b>Strip</b> field -";
                   }
  if ($form_valid)
   if (!is_numeric($strip)) {
                             $form_valid=false;
                             $form_error="- <b>Strip</b> field must be numeric -";
                            }
  if ($form_valid)
   if ($strip<0) {
                  $form_valid=false;
                  $form_error="- <b>Strip</b> field must be a positive number -";
                 }
  if ($form_valid)
    if ($socket != NULL && $socket != "" && get_settings_value("sockets") != "")
	   if (!preg_match('/^(sctp|tls|udp|tcp):(((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))|((([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])))(:([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5]))?$/i',$socket)) {
                             $form_valid=false;
                             $form_error="- <b>Socket</b> is invalid -";
                            }
	if ($form_valid && $action!="modify") {
		$sql="select count(*) from ".$table." where gwid = ?";
		$stm = $link->prepare($sql);
		if ($stm === FALSE)
			die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
		$stm->execute(array($gwid));
		$result = $stm->fetchColumn(0);
		if ($result > 0) {
			$form_valid=false;
			$form_error="- GWID already exists -";
		}

	}

  if ($form_valid) {
	  $gw_attributes_mode = get_settings_value("gw_attributes_mode");
	  if ($gw_attributes_mode == "input") {
		  $gw_attributes = get_settings_value("gw_attributes");
		  if (isset($gw_attributes['validation_regexp']) &&
			  !preg_match('/'.get_settings_value("gw_attributes")['validation_regexp'].'/i',$attrs)) {
			  $form_valid=false;
			  $form_error="- <b>".(isset($gw_attributes['display_name'])?$gw_attributes['display_name']:"Attributes")."</b> value is invalid: ".$gw_attributes['validation_error'];
		  }
	  }
  }

?>
