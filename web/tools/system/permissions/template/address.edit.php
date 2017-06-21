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
<table width="400" cellspacing="2" cellpadding="2" border="0">

<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	$id=$_GET['id'];
	
	$sql = "select * from ".$table." where id='".$id."'";
	$index_row=0;
	$resultset = $link->queryAll($sql);
$link->disconnect();
?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="permissionsTitle">Edit Address</td>
 </tr>
<?php
?>
 <tr>
  <td class="dataRecord"><b>Group:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="grp" value="<?=$resultset[0]['grp']?>" maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>IP:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="src_ip" value="<?=$resultset[0]['ip']?>" maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Mask:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="mask" value="<?=$resultset[0]['mask']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Port:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="port" value="<?=$resultset[0]['port']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Protocol:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="proto" value="<?=$resultset[0]['proto']?>" maxlength="128" class="dataInput"></td>
 </tr>
 
<tr>
  <td class="dataRecord"><b>From pattern:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="from_pattern" value="<?=$resultset[0]['pattern']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Context Info:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="context_info" value="<?=$resultset[0]['context_info']?>" maxlength="128" class="dataInput"></td>
 </tr>


 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

