<?php
/*
 * Copyright (C) 2016-2017 OpenSIPS Project
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

$id=$_GET['id'];
	
$sql = "select * from ".$table." where id=?";
$stm = $link->prepare($sql);
if ($stm === false) {
	die('Failed to issue query, error message: ' . print_r($link->errorInfo(), true));
}
$stm->execute( array($id) );
$cl_form = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
?>

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<?php csrfguard_generate(); ?>
	<table width="400" cellspacing="2" cellpadding="2" border="0">
	
	<tr align="center">
		<td colspan="2" class="mainTitle">
			Edit Cluster Node
		</td>
	 </tr>

	<?php
	require("clusterer.form.php");
	?>

	<tr>
	<td colspan="2">
		<table cellspacing=20>
		<tr>
		<td class="dataRecord" align="right" width="50%">
		<input type="submit" name="add" value="Save" class="formButton"></td>
		<td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
		</tr>
		</table>
  	</td>
	</tr>

	</table>
	<script> form_init_status(); </script>
</form>
