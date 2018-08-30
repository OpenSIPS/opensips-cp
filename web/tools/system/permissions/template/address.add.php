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

$clone=$_GET['clone'];

if($add_verify =="1"){
	$id=$_GET['id'];

	$sql = "select * from ".$table." where id = ?";
	$stm = $link->prepare($sql);
	if ($stm->execute(array($id)) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

	$grp = $resultset[0]['grp'];
	$src_ip = $resultset[0]['src_ip'];
	$mask = $resultset[0]['mask'];
	$port = $resultset[0]['port'];
	$proto = $resultset[0]['proto'];
	$from_pattern =$resultset[0]['from_pattern'];
	$context_info =$resultset[0]['context_info'];
}

?>
<form action="<?=$page_name?>?action=add_verify&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="mainTitle">Add new Address rule</td>
 </tr>
<?php
?>
 <tr>
  <td class="dataRecord">Group</td>
  <td class="dataRecord" width="275"><input type="text" name="grp" 
  value="<?=$grp?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord">IP</td>
  <td class="dataRecord" width="275"><input type="text" name="src_ip" 
  value="<?=$src_ip?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord">Mask</td>
  <td class="dataRecord" width="275"><input type="text" name="mask" 
  value="<?=$mask?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord">Port</td>
  <td class="dataRecord" width="275"><input type="text" name="port" 
  value="<?=$port?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord">Protocol</td>
  <td class="dataRecord" width="275"><input type="text" name="proto" 
  value="<?=$proto?>" maxlength="128" class="dataInput"></td>
 </tr>
 
<tr>
  <td class="dataRecord">Pattern</td>
  <td class="dataRecord" width="275"><input type="text" name="from_pattern" 
  value="<?=$from_pattern?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord">Context Info</td>
  <td class="dataRecord" width="275"><input type="text" name="context_info" 
  value="<?=$context_info?>" maxlength="128" class="dataInput"></td>
 </tr>

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
