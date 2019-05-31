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

if ( isset($_GET['clone']) )
	$clone = $_GET['clone'];
else
	$clone = 0;

if ( $clone == "1" ) {
	$id = $_GET['id'];

	$sql = "select * from " . $table . " where id=?";
		$stm = $link->prepare($sql);
	if ($stm === FALSE)
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	$stm->execute( array($id) );
	$resultset= $stm->fetchAll(PDO::FETCH_ASSOC);

	$registrar = $resultset[0]['registrar'];
	$proxy = $resultset[0]['proxy'];
	$aor =$resultset[0]['aor'];
	$third_party_registrant =$resultset[0]['third_party_registrant'];
	$username =$resultset[0]['username'];
	$password  =$resultset[0]['password'];
	$binding_uri = $resultset[0]['binding_uri'];
	$binding_params = $resultset[0]['binding_params'];
	$expiry = $resultset[0]['expiry'];
	$forced_socket = $resultset[0]['forced_socket'];
	$cluster_shtag = $resultset[0]['cluster_shtag'];
}

?>
<form action="<?=$page_name?>?action=clone&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
	<table width="400" cellspacing="2" cellpadding="2" border="0">

	<tr align="center">
		<td colspan="2" class="mainTitle">
			Clone given SIP Trunk
		</td>
	</tr>

	<?php
	# populate the initial values for the form
	$ds_form['registrar'] = $registrar;
	$ds_form['proxy'] = $proxy;
	$ds_form['aor'] = $aor;
	$ds_form['third_party_registrant'] = $third_party_registrant;
	$ds_form['username'] = $username;
	$ds_form['password'] = $password;
	$ds_form['binding_uri'] = $binding_uri;
	$ds_form['binding_params'] = $binding_params;
	$ds_form['expiry'] = $expiry;
	$ds_form['forced_socket'] = $forced_socket;
	$ds_form['cluster_shtag'] = $cluster_shtag;

	require("sip_trunk.form.php");
	?>

	<tr>
	    <td colspan="2">
		    <table cellspacing=20>
		        <tr>
		            <td class="dataRecord" align="right" width="50%">
		                <input type="submit" name="add_verify" disabled=true value="Clone" class="formButton"></td>
		            <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
		        </tr>
		    </table>
	    </td>
	</tr>

	</table>
</form>
