<form action="<?=$page_name?>?action=add_verify" method="post">
<?php
/*
* $Id: add_admin.main.php 56 2009-06-03 13:46:51Z iulia_bublea $
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
?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="addadminTitle">Add New Admin</td>
 </tr>
 <tr>
  <td class="addRecord" align="center">First Name:</td>
  <td class="addRecord" width="200"><input type="text" name="add_fname"
  value="<?=$fname?>" class="searchInput"></td>
 </td>
 <tr>
  <td class="addRecord" align="center">Last Name:</td>
  <td class="addRecord" width="200"><input type="text" name="add_lname"
  value="<?=$lname?>" class="searchInput"></td>
 </tr>
 <tr>
  <td class="addRecord" align="center">Username:</td>
  <td class="addRecord" width="200"><input type="text" name="add_uname"
  value="<?=$uname?>" class="searchInput"></td>
 </tr>
 <tr>
  <td class="addRecord" align="center">Password:</td>
  <td class="addRecord" width="200"><input type="password" name="add_passwd"
  value="<?=$passwd?>"></td>
 </tr>
 <tr>
  <td class="addRecord" align="center">Confirm Password:</td>
  <td class="addRecord" width="200"><input type="password" name="confirm_passwd"
  value="<?=$confirm_passwd?>" ></td>
 </tr>
 <tr>
  <td colspan="2" class="addRecord" align="center">
  <?php if(!$_SESSION['read_only']) {  
		echo('<input type="submit" name="addadmin" value="Register" class="Button">&nbsp;&nbsp;&nbsp;');
	}
  ?>
  </td>
 </tr>
 <tr>
  <td colspan="2" class="addadminTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>
