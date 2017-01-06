<?php
/*
* $Id$
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

$available_probe_modes = array(
	/* format is short tag - long tag */
	array("No probing", "No probing"),
	array("When disabled", "Probing only when the destination is in disabled mode"),
	array("Always probing", "Probing all the time")
);

function get_probe_types($name, $set)
{
	global $available_probe_modes;
	echo('<select name="'.$name.'" id="'.$name.'" size="1" class="dataSelect">');
	if ($name=="search_type") echo('<option value="">- all types -</option>');
	for ($i=0; $i<sizeof($available_probe_modes); $i++)
	{
		if ($set == $available_probe_modes[$i][1]) $xtra = 'selected';
		else $xtra ='';
		if(!empty($available_probe_modes[$i][1]))
			echo('<option value="'.$i.'" '.$xtra.'>'.$available_probe_modes[$i][1].'</option>');
	}
	echo('</select>');
}

function get_probe_mode($probe_idx)
{
	global $available_probe_modes;
	if ($probe_idx < sizeof($available_probe_modes))
		return $available_probe_modes[$probe_idx][0];
	return '';
}

?>
