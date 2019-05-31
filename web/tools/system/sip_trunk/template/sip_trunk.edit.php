<?php
/*
 * Copyright (C) 2019 OpenSIPS Project
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

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">

<?php
if ( isset($form_error) ) {
	echo('   <tr align="center">');
	echo('       <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
	echo('   </tr>');
}
$id = $_GET['id'];

$sql_command = "select * from ".$table." where id=?";
$stm = $link->prepare($sql_command);
if ($stm->execute(array($id)) === false)
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

$index_row=0;
?>
    <table width="350" cellspacing="2" cellpadding="2" border="0">
	    <tr align="center">
		     <td colspan="2" class="mainTitle">Edit SIP Trunk</td>
	    </tr>
<?php
	# populate row values to the form fields
	$ds_form['registrar'] = $resultset[0]['registrar'];
	$ds_form['proxy'] = $resultset[0]['proxy'];
	$ds_form['aor'] = $resultset[0]['aor'];
	$ds_form['third_party_registrant'] = $resultset[0]['third_party_registrant'];
	$ds_form['username'] = $resultset[0]['username'];
	$ds_form['password'] = $resultset[0]['password'];
	$ds_form['binding_uri'] = $resultset[0]['binding_uri'];
	$ds_form['binding_params'] = $resultset[0]['binding_params'];
	$ds_form['expiry'] = $resultset[0]['expiry'];
	$ds_form['forced_socket'] = $resultset[0]['forced_socket'];
	$ds_form['cluster_shtag'] = $resultset[0]['cluster_shtag'];

	require("sip_trunk.form.php");
?>

	    <tr>
		    <td colspan="2">
			    <table cellspacing=20>
				    <tr>
					    <td class="dataRecord" align="right" width="50%">
					       <input type="submit" name="save" value="Save" class="formButton"></td>
					    <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
				    </tr>
			    </table>
		    </td>
	    </tr>
    </table>
</form>
