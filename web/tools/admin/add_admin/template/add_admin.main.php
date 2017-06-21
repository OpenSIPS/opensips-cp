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
<form action="<?=$page_name?>?action=add_verify" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="addadminTitle">Add New Admin</td>
 </tr>
 <tr>
  <td class="dataRecord" >First Name</td>
  <td class="dataRecord" width="275"><input type="text" name="add_fname"
  value="<?=$fname?>" class="dataInput"></td>
 </td>
 <tr>
  <td class="dataRecord" >Last Name</td>
  <td class="dataRecord" width="275"><input type="text" name="add_lname"
  value="<?=$lname?>" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Username</td>
  <td class="dataRecord" width="275"><input type="text" name="add_uname"
  value="<?=$uname?>" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Password</td>
  <td class="dataRecord" width="275"><input type="password" name="add_passwd"
  value="<?=$passwd?>" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Confirm Password</td>
  <td class="dataRecord" width="275"><input type="password" name="confirm_passwd"
  value="<?=$confirm_passwd?>" class="dataInput"></td>
 </tr>
 <tr>
  <td colspan="2" class="dataRecord" align="center">
  <?php if(!$_SESSION['read_only']) {  
		echo('<input type="submit" name="addadmin" value="Add" class="formButton">');
	}
  ?>
  </td>
 </tr>
 <tr>
  <td colspan="2" class="addadminTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>
