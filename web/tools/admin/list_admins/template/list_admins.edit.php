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

 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	$id=$_GET['id'];
	
	$sql = "select * from ".$table." where id='".$id."'";
	$resultset = $link->queryAll($sql);
    $index_row=0;
	$link->disconnect();

?>
<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr>
 <td colspan="2" class="listadminsTitle" align="center">Edit Admin Information</td>
 </tr>
 
 <tr>
  <td class="dataRecord"><b>Username</b></td>
  <td class="dataRecord" width="275"><input type="text" name="listuname" value="<?=$resultset[0]['username']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>First Name</b></td>
  <td class="dataRecord" width="275"><input type="text" name="listfname" value="<?=$resultset[0]['first_name']?>" maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Last Name</b></td>
  <td class="dataRecord" width="275"><input type="text" name="listlname" value="<?=$resultset[0]['last_name']?>" maxlength="128" class="dataInput"></td>
 </tr>
 
 <tr>
  <td class="dataRecord"><b>Password</b></td>
  <td class="dataRecord" width="275"><input type="password" name="listpasswd" value="<?=$resultset[0]['password']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Confirm Password</b></td>
  <td class="dataRecord" width="275"><input type="password" name="conf_passwd" value="<?=$resultset[0]['password']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<br>
<?=$back_link?>

