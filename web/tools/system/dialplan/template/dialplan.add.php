<?
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

$clone=$_GET['clone'];

if($clone =="1"){

	$id=$_GET['id'];

	$sql = "select * from ".$table." where id='".$id."'";
	$resultset = $link->queryAll($sql);
	if(PEAR::isError($resultset)) {
        	die('Failed to issue query, error message : ' . $resultset->getMessage());
	}
	$link->disconnect;
	$dpid = $resultset[0]['dpid'];
	$pr = $resultset[0]['pr'];
	$match_exp =$resultset[0]['match_exp'];
	$match_flags =$resultset[0]['match_flags'];
	$subst_exp =$resultset[0]['subst_exp'];
	$repl_exp  =$resultset[0]['repl_exp'];
	$attrs = $resultset[0]['attrs'];
}

if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {
	$chech_boxes = "";
	for($i=0; $i<sizeof($config->attrs_cb); $i++)
	{
		if(($i% $config->cb_per_row==0) && ($i!=0))
		$check_boxes.='<br>';

		$check_boxes.='<input type="checkbox" name="'.$config->attrs_cb[$i][0];
		$check_boxes.='" value="'.$config->attrs_cb[$i][1];
		if($clone=="1" && stristr($row['attrs'],$config->attrs_cb[$i][0])){
			$check_boxes.='" checked>';
		}else{
			$check_boxes.='">';
		}

		$check_boxes.=$config->attrs_cb[$i][1];
	}
}

$match_op_sel ='<select name="match_op" id="match_op" size="1" class="dataSelect">';
if($clone=="1"){
	if($row['match_op']==1){
		$match_op_sel.='<option value="1" selected>REGEX</option>';
		$match_op_sel.= '<option value="0" >EQUAL</option>';
	} else {
		$match_op_sel.='<option value="1" >REGEX</option>';
		$match_op_sel.= '<option value="0" selected>EQUAL</option>';
	}
}else{
	$match_op_sel.='<option value="1" >REGEX</option>';
	$match_op_sel.= '<option value="0" >EQUAL</option>';
}
$match_op_sel.= '</select>';


?>
<form action="<?=$page_name?>?action=add_verify&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dialplanTitle">Add new Translation Rule</td>
 </tr>
<?php
?>
 <tr>
  <td class="dataRecord"><b>Dialplan ID</b></td>
  <td class="dataRecord" width="275"><input type="text" name="dpid" 
  value="<?=$dpid?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Rule Priority</b></td>
  <td class="dataRecord" width="275"><input type="text" name="pr" 
  value="<?=$pr?>" maxlength="128" class="dataInput"></td>
 </tr>
 
<tr>
  <td class="dataRecord"><b>Matching Operator</b></td>
  <td class="dataRecord" width="275"><?=$match_op_sel?></td>
 </tr>


<tr>
  <td class="dataRecord"><b>Matching Regular Expression</b></td>
  <td class="dataRecord" width="275"><input type="text" name="match_exp" 
  value="<?=$match_exp?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Matching Flags</b></td>
  <td class="dataRecord" width="275"><input type="text" name="match_exp_flags" 
  value="<?=$match_flags?>" maxlength="128" class="dataInput"></td>
 </tr>

<tr>
  <td class="dataRecord"><b>Substitution Regular Expression</b></td>
  <td class="dataRecord" width="275"><input type="text" name="subst_exp" 
  value="<?=$subst_exp?>" maxlength="128" class="dataInput"></td>
 </tr>

<tr>
  <td class="dataRecord"><b>Replacement Expression</b></td>
  <td class="dataRecord" width="275"><input type="text" name="repl_exp" 
  value="<?=$repl_exp?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Attributes</b></td>

  <? if ( ($dialplan_attributes_mode == 0) || (!isset($dialplan_attributes_mode))) {  ?>	
  	<td class="dataRecord"><?=$check_boxes?></td>
	<? } else if ($dialplan_attributes_mode == 1 ) {  ?>	

	  <td class="dataRecord" width="275"><input type="text" name="attrs" 
  	value="<?=$row['attrs']?>" maxlength="128" class="dataInput"></td>

	<? } ?>
  
 </tr>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="add" value="Add" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dialplanTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

<?php

function display_attrs_cb()
{
	for($i=0; $i<sizeof($config->attrs_cb); $i++)
	{
		echo(' <input type="checkbox" name="'.$config->attrs_cb[$i][0].'" value="'.$config->attrs_cb[$i][1].'">'
		.$config->attrs_cb[$i][1]);
	}
}
?>

