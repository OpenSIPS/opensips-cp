<?php
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

require("../../../common/forms.php");
?>

<form action="<?=$page_name?>?action=do_add&id=<?=$_GET['id']?>" method="post">
	<table width="400" cellspacing="2" cellpadding="2" border="0">
	
	<tr align="center">
		<td colspan="2" class="dispatcherTitle">
			Add New Destination
		</td>
	 </tr>

<?php
	if (isset($form_error)) {
		echo('<tr align="center">');
		echo('	<td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
		echo('</tr>');
	}

	form_generate_input_text("Set ID","The numerical ID of the dispatcher set/group for the new destination",
		"setid","n",null,128,"^[0-9]+$");

	form_generate_input_text("Destination","SIP URI pointing to the destination",
		"destination","n",null,192,"^sip:([^@]+@)?[^:]*(:[0-9]+)?$");

	form_generate_input_text("Socket","The OpenSIPS network listener (as proto:ip:port) to be used for reaching this destination (leave empty if not needed)",
		 "socket","y",null,128,"^([a-zA-Z]+:)?([0-9]{1,3}\\\.[0-9]{1,3}\\\.[0-9]{1,3}\\\.[0-9]{1,3})(:[0-9]+)?$");

	form_generate_select("State","The intial state (active or inactive) of the destination","state",200,array("0","1"),array("Active","Inactive"));

	form_generate_input_text("Weight","The weight of the destination inside the set - it can be a number or URL",
		"weight","n","1",128,"^[0-9]+$");

	form_generate_input_text("Attributes","An opaque attributes string to be provided in OpenSIPS script when the destination is selected",
		"attrs","y",null,128,null);

	form_generate_input_text("Description","Description in DB, not used by OpenSIPS",
		"description","y",null,128,null);
?>
	<tr>
		<td colspan="2" class="dataRecord" align="center">
			<input type="submit" name="add" disabled=true value="Add" class="formButton">
		</td>
	</tr>

	<tr height="10">
		<td colspan="2" class="dataTitle">
			<img src="../../../images/share/spacer.gif" width="5" height="5">
		</td>
	</tr>

	</table>
</form>
<?=$back_link?>
