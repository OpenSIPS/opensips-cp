<?
/*
* Copyright (C) 2017 OpenSIPS Project
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
?>

<script language="JavaScript">

function form_full_check() {
	elem = document.getElementsByTagName("input");
	ret = true;
	button = null;

	for(var i = 0; i < elem.length; i++) {
		if (elem[i].getAttribute("opt")!=null && elem[i].getAttribute("opt")!="") {
			if ( !(elem[i].getAttribute("opt")=="y" && elem[i].value=="") &&
				!(elem[i].getAttribute("valid")=="ok") )
				ret = false;
		} else if (elem[i].type=="submit")
			button = elem[i];
	}
	if (button!=null) {
		if ( ret )
			button.disabled = false;
		else
			button.disabled = true;
	}
}

function validate_input(field, output, regex){
	val = document.getElementById(field).value;
	re = new RegExp( regex,"g");
	if (val=="") {
		if (document.getElementById(field).getAttribute("opt")=="y")
			document.getElementById(output).innerHTML = '';
		else
			document.getElementById(output).innerHTML = '<img src="../../../images/share/must-icon.png">';
		document.getElementById(field).setAttribute("valid","ko");
		ret =-1;
	} else if (val.match(re) ) {
		document.getElementById(output).innerHTML = '<img src="../../../images/share/ok_small.png">';
		document.getElementById(field).setAttribute("valid","ok");
		ret = 1;
	} else {
		document.getElementById(output).innerHTML = '<img src="../../../images/share/ko_small.png">';
		document.getElementById(field).setAttribute("valid","ko");
		ret = -1;
	}

	form_full_check();
	return ret;
}
</script>

<?php
function form_generate_input_text($title,$tip,$id,$opt,$val,$mlen,$re) {

	if ($val!=null)
		$value=" value='".$val."' valid='ok'";
	else 
		$value = "";

	if ($re==null)
		$validate="";
	else
		$validate=" opt='".$opt."' oninput='validate_input(\"".$id."\", \"".$id."_ok\",\"".$re."\")'";

	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>
				<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div>
			</td>
			<td class='dataRecord' width='250'>
				<table><tr><td>
				<input type='text' name='".$id."'".$value." id='".$id."' maxlength='".$mlen."' class='dataInput'".$validate.">
				</td>
				<td width='20'>
				<div id='".$id."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
				</td></tr></table>
			</td>
		</tr>");
}

function form_generate_select($title,$tip,$id,$mlen,$val,$vals,$texts) {

	if ($val!=null)
		$value=" value='".$val."' valid='ok'";
	else 
		$value = "";

	if ($re==null)
		$validate="";
	else
		$validate=" opt='".$opt."' oninput='validate_input(\"".$id."\", \"".$id."_ok\",\"".$re."\")'";

	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>
				<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div>
			</td>
			<td class='dataRecord' width='250'>
				<table><tr><td>
				<select name='".$id."' id='".$id."' style='width: ".$mlen."px;' class='dataSelect'>");
	for($i = 0; $i < count($vals); ++$i){
		print("
					<option value='".$vals[$i]."'".(($val==$vals[$i])?" selected":"").">".$texts[$i]."</option>");
	}
	print("
				</select>
				</td>
				<td width='20'>
				<div id='".$id."_ok'></div>
				</td></tr></table>
			</td>
		</tr>");
}


// Helpers to build complet validation regexp

# FreeSWITCH url (fs://[username]:password@host[:port])
$re_fs_url ="(fs://[a-zA-Z0-9]*:[^@]+@[^:]+(:[0-9]+)?)";

# SIP URI
$re_sip_uri = "sip(s)?:([^@]+@)?[^:]+(:[0-9]+)?";

?>
