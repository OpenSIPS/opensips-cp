<?php
/*
 * Copyright (C) 2021 OpenSIPS Project
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



function dr_get_name_of_sort_alg($char)
{
	if ($char=="N")
		return "none";
	else
	if ($char=="W")
		return "weight";
	else
	if ($char=="Q")
		return "quality";
	return "unknown";
} 


function dr_get_options_of_list_sort($selected) 
{
	
	if ($selected==NULL || $selected=="") {
		$selected = 'N';
		$out = "";
	} else if ($selected!="N" && $selected!="W" && $selected!="Q" ){
		$out = "<option value='".$selected."' selected>".$selected."(unknown)</option>";
	}

	$out .= "<option value='N' ".($selected=='N'?"selected":"").">none</option>";
	$out .= "<option value='W' ".($selected=='W'?"selected":"").">weight</option>";
	$out .= "<option value='Q' ".($selected=='Q'?"selected":"").">quality</option>";
	print $out;
}

?>
