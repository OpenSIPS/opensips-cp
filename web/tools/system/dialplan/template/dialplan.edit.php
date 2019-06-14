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
?>

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="350" cellspacing="2" cellpadding="2" border="0">

<?php
if (isset($form_error)) {
	echo('   <tr align="center">');
	echo('      <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
	echo('   </tr>');
}
$id=$_GET['id'];

$sql_command = "select * from ".$table." where id=?";
$stm = $link->prepare($sql_command);
if ($stm->execute(array($id)) === false)
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

$index_row=0;

$match_op_sel ='<select name="match_op" id="match_op" size="1" class="dataSelect">';
if($resultset[0]['match_op']==1) {
	$match_op_sel.='<option value="1" selected>REGEX</option>';
	$match_op_sel.= '<option value="0" >EQUAL</option>';
} else {
	$match_op_sel.='<option value="1" >REGEX</option>';
	$match_op_sel.= '<option value="0" selected>EQUAL</option>';
}
$match_op_sel.= '</select>';

if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {
	$chech_boxes = "";
	for( $i=0; $i<sizeof($config->attrs_cb); $i++ ) {
		if(($i% $config->cb_per_row==0) && ($i!=0))
			$check_boxes.='<br>';

		$check_boxes.='<input type="checkbox" name="'.$config->attrs_cb[$i][0];
		$check_boxes.='" value="'.$config->attrs_cb[$i][1];
		if(stristr($resultset[0]['attrs'],$config->attrs_cb[$i][0])) {
			$check_boxes.='" checked>';
		} else {
			$check_boxes.='">';
		}
		$check_boxes.=$config->attrs_cb[$i][1];
	}
}

?>
	<table width="350" cellspacing="2" cellpadding="2" border="0">
		<tr align="center">
			<td colspan="2" class="mainTitle">Edit Translation Rule</td>
		</tr>

<?php
	# populate row values to the form fields
	$ds_form['dpid'] = $resultset[0]['dpid'];
	$ds_form['pr'] = $resultset[0]['pr'];
	$ds_form['match_op_sel'] = $resultset[0]['match_op_sel'];
	$ds_form['match_exp'] = $resultset[0]['match_exp'];
	$ds_form['match_flags'] = $resultset[0]['match_flags'];
	$ds_form['subst_exp'] = $resultset[0]['subst_exp'];
	$ds_form['repl_exp'] = $resultset[0]['repl_exp'];
	$ds_form['attrs'] = $resultset[0]['attrs'];

	require("dialplan.form.php");
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
