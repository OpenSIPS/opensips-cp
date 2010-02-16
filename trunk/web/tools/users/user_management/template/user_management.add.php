<form action="<?=$page_name?>?action=add_verify&id=<?=$_GET['id']?>" method="post">
<?php
/*
* $Id: user_management.add.php 56 2009-06-03 13:46:51Z iulia_bublea $
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
  <td colspan="2" height="10" class="listTitle">Add New User</td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Username:</td>
  <td class="listRecord" width="200"><input type="text" name="uname"
  value="<?=$uname?>" class="searchInput"></td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Domain:</td>
  <td class="listRecord" width="200"><?php print_domains("domain",'')?></td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Email:</td>
  <td class="listRecord" width="200"><input type="text" name="email"
  value="<?=$email?>" class="searchInput"></td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Alias Username:</td>
  <td class="listRecord" width="200"><input type="text" name="alias"
  value="" class="searchInput" maxlength=5></td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Alias Type:</td>
  <td class="listRecord" width="200"><?php print_aliasType(0)?></td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Password:</td>
  <td class="listRecord" width="200"><input type="password" name="passwd"
  value=""></td>
 </tr>
 <tr>
  <td class="listRecord" align="center">Confirm Password:</td>
  <td class="listRecord" width="200"><input type="password" name="confirm_passwd"
  value="" ></td>
 </tr>
 <tr>
  <td colspan="2" class="listRecord" align="center">
  <?php if (!$_SESSION['read_only']) {
	  echo('<input type="submit" name="adduser" value="Register" class="Button">&nbsp;&nbsp;&nbsp;');
	}
 ?>
 </tr>
 <tr>
  <td colspan="2" class="listTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>
<?=$back_link?>
