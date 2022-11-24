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
	  if ($gwlist=="") {
		  $form_valid=false;
		  $form_error="- invalid <b>Gateway List</b> field -";
	  }
  
  if ($form_valid) {
	  // make $gwlist
	  if (substr($gwlist,strlen($gwlist)-1,1)==",") $gwlist=substr($gwlist,0,strlen($gwlist)-1);
  }
  if ($action != "modify"){
	  $sql="select count(*) from ".$table." where carrierid=?";
	  $stm = $link->prepare($sql);
	  if ($stm===FALSE) {
		  die('Failed to issue query ['.$sql.'], error message : ' . $link->errorInfo()[2]);
	  }
	  $stm->execute( array($gwlist) );
	  $data_rows = $stm->fetchColumn(0);
	  if (($data_rows>0))
	  {
		  $form_valid=false;
		  $form_error="- this carrier already exists -";
	  }
  }
  if ($form_valid) {
	  $carrier_attributes_mode = get_settings_value("carrier_attributes_mode");
	  if ($carrier_attributes_mode == "input") {
		  $carrier_attributes = get_settings_value("carrier_attributes");
		  if (isset($carrier_attributes['validation_regexp']) &&
			  !preg_match('/'.get_settings_value("carrier_attributes")['validation_regexp'].'/i',$attrs)) {
			  $form_valid=false;
			  $form_error="- <b>".(isset($carrier_attributes['display_name'])?$carrier_attributes['display_name']:"Attributes")."</b> value is invalid: ".$carrier_attributes['validation_error'];
		  }
	  }
  }

?>
