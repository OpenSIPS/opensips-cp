<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
<?php
/*
 * $Id:$
 * Copyright (C) 2008 Voice Sistem SRL
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

 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	db_connect();
	$id=$_GET['id'];
	
	$result=mysql_query("select * from ".$table." where id='".$id."'") or die(mysql_error());
    $index_row=0;
    $row=mysql_fetch_array($result);
	db_close();

	$match_op_sel ='<select name="match_op" id="match_op" size="1" class="dataSelect">';
	if($row['match_op']==1) {
		$match_op_sel.='<option value="1" selected>REGEX</option>';
		$match_op_sel.= '<option value="0" >EQUAL</option>';
	} else {
		$match_op_sel.='<option value="1" >REGEX</option>';
		$match_op_sel.= '<option value="0" selected>EQUAL</option>';
	}
	$match_op_sel.= '</select>';
	
	if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {
	$chech_boxes = "";
	for($i=0; $i<sizeof($config->attrs_cb); $i++)
	{
		if(($i% $config->cb_per_row==0) && ($i!=0))
  			$check_boxes.='<br>';

	  	$check_boxes.='<input type="checkbox" name="'.$config->attrs_cb[$i][0];
  		$check_boxes.='" value="'.$config->attrs_cb[$i][1];
		if(stristr($row['attrs'],$config->attrs_cb[$i][0])) {
			$check_boxes.='" checked>';
		} else {
			$check_boxes.='">';
		}
		$check_boxes.=$config->attrs_cb[$i][1];
	}
	}

?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dialplanTitle">Edit Translation Rule</td>
 </tr>
<?php
?>
 <tr>
  <td class="dataRecord"><b>Dialplan ID:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="dpid" value="<?=$row['dpid']?>" maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Rule Priority:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="pr" value="<?=$row['pr']?>" maxlength="128" class="dataInput"></td>
 </tr>
 
<tr>
  <td class="dataRecord"><b>Matching Operator:</b></td>
  <td class="dataRecord" width="275"><?=$match_op_sel?></td>
 </tr>


<tr>
  <td class="dataRecord"><b>Matching Regular Expression:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="match_exp" value="<?=$row['match_exp']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Matching String Length:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="match_exp_len" 
  	value="<?=$row['match_len']?>" maxlength="128" class="dataInput"></td>
 </tr>

<tr>
  <td class="dataRecord"><b>Substitution Regular Expression:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="subst_exp" 
  	value="<?=$row['subst_exp']?>" maxlength="128" class="dataInput"></td>
 </tr>

<tr>
  <td class="dataRecord"><b>Replacement Expression:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="repl_exp" 
  	value="<?=$row['repl_exp']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Attributes:</b></td>
	<? if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {  ?>	
  	<td class="dataRecord"><?=$check_boxes?></td>
	<? } else if ($dialplan_attributes_mode == 1 ) {  ?>	

	  <td class="dataRecord" width="275"><input type="text" name="attrs" 
  	value="<?=$row['attrs']?>" maxlength="128" class="dataInput"></td>

	<? } ?>
  </td>
 </tr>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

