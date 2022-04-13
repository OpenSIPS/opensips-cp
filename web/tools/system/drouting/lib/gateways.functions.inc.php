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

function get_gw_types()
{
 $gateways = get_settings_value("gateway_types_file");
 $gw_types = array();
 foreach ($gateways as $key => $value) {
	 $gw_types[$key] = $key . " - " . $value;
 }
 return $gw_types;
}


function get_types($name, $set, $width=200)
{
 $gateways = get_gw_types();
 echo('<select name="'.$name.'" id="'.$name.'" size="1" class="dataSelect" style="width:'.$width.';">');
 if ($name=="search_type") echo('<option value="">- all types -</option>');
 
 foreach ($gateways as $key => $value) {
  if ($set==$key) $xtra = 'selected';
  else $xtra ='';
  echo('<option value="'.$key.'" '.$xtra.'>'.$value.'</option>');
 }
 echo('</select>');
 return;
}

function get_type($id)
{
 $gateways = get_settings_value("gateway_types_file");
 if (array_key_exists($id, $gateways)) {
  echo($id." - ".$gateways[$id]);
 }
 return;
}

?>
