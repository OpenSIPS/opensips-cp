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

if ( isset($_GET['clone']) )
    $clone = $_GET['clone'];
else
    $clone = 0;

if( $clone == "1" ) {
    $id=$_GET['id'];

    $sql = "select * from ".$table." where id=?";
    $stm = $link->prepare($sql);
    if ($stm === FALSE)
	die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
    $stm->execute( array($id) );
    $resultset= $stm->fetchAll(PDO::FETCH_ASSOC);

    $dpid = $resultset[0]['dpid'];
    $pr = $resultset[0]['pr'];
    $match_exp =$resultset[0]['match_exp'];
    $match_flags =$resultset[0]['match_flags'];
    $subst_exp =$resultset[0]['subst_exp'];
    $repl_exp  =$resultset[0]['repl_exp'];
    $attrs = $resultset[0]['attrs'];
}

if ( ( $dialplan_attributes_mode == 0 ) || ( !isset($dialplan_attributes_mode ) ) ) {
    $chech_boxes = "";
    for( $i=0; $i < sizeof($config->attrs_cb); $i++ )
    {
	if ( ( $i% $config->cb_per_row == 0 ) && ( $i != 0 ))
	    $check_boxes .= '<br>';

	$check_boxes .= '<input type="checkbox" name="' . $config->attrs_cb[$i][0];
	$check_boxes .= '" value="' . $config->attrs_cb[$i][1];
	if ( $clone == "1" && stristr( $row['attrs'], $config->attrs_cb[$i][0] ) ) {
	    $check_boxes .= '" checked>';
	} else {
	    $check_boxes .= '">';
	}

	$check_boxes.=$config->attrs_cb[$i][1];
    }
}

$match_op_sel = '<select name="match_op" id="match_op" size="1" class="dataSelect">';
if ( $clone == "1" ) {
    if ( $row['match_op'] == 1 ) {
	$match_op_sel .= '<option value="1" selected>REGEX</option>';
	$match_op_sel .= '<option value="0" >EQUAL</option>';
    } else {
	$match_op_sel .= '<option value="1" >REGEX</option>';
	$match_op_sel .= '<option value="0" selected>EQUAL</option>';
    }
} else {
    $match_op_sel .= '<option value="1" >REGEX</option>';
    $match_op_sel .= '<option value="0" >EQUAL</option>';
}
$match_op_sel.= '</select>';

?>
<form action="<?=$page_name?>?action=add_verify&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
    <table width="350" cellspacing="2" cellpadding="2" border="0">
	<tr align="center">
	    <td colspan="2" class="mainTitle">
		Add new Translation Rule</td>
	</tr>

	<?php
	# populate the initial values for the form
	$ds_form['dpid'] = null;
	$ds_form['pr'] = null;
	$ds_form['match_op_sel'] = null;
	$ds_form['match_exp'] = null;
	$ds_form['match_flags'] = null;
	$ds_form['subst_exp'] = null;
	$ds_form['repl_exp'] = null;
	$ds_form['attrs'] = null;

	require("dialplan.form.php");
	?>

	<tr>
	    <td colspan="2">
		<table cellspacing=20>
		    <tr>
			<td class="dataRecord" align="right" width="50%">
			    <input type="submit" name="add" value="Add" class="formButton"></td>
			    <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
		    </tr>
		</table>
	    </td>
	</tr>
    </table>
</form>
