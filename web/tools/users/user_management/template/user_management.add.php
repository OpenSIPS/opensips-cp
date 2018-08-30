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
<form action="<?=$page_name?>?action=add_verify&id=<?=$_GET['id']?>" method="post">
<table width="350" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="mainTitle">Add New User</td>
 </tr>
 <tr>
  <td class="dataRecord" >Username</td>
  <td class="dataRecord" width="200"><input type="text" name="uname" value="" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Domain</td>
  <td class="dataRecord" width="200"><?php print_domains("domain",'',FALSE)?></td>
 </tr>
 <tr>
  <td class="dataRecord" >Email</td>
  <td class="dataRecord" width="200"><input type="text" name="email" value="" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Alias Username</td>
  <td class="dataRecord" width="200"><input type="text" name="alias" value="" class="dataInput" maxlength=5></td>
 </tr>
 <tr>
  <td class="dataRecord" >Alias Type</td>
  <td class="dataRecord" width="200"><?php print_aliasType(0)?></td>
 </tr>

<?php
	foreach ( $config->subs_extra as $key => $value ) {
?>
 <tr>
  <td class="dataRecord"><?=$value?></td>
  <td class="dataRecord" width="200"><input type="text" name="extra_<?=$key?>" value="" maxlength="128" class="dataInput"></td>
 </tr>
<?php
	}
?>

 <tr>
  <td class="dataRecord" >Password</td>
  <td class="dataRecord" width="200"><input type="password" name="passwd" value="" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord" >Confirm Password</td>
  <td class="dataRecord" width="200"><input type="password" name="confirm_passwd" value="" class="dataInput"></td>
 </tr>

 <tr>
  <td colspan="2">
    <table cellspacing=20>
      <tr>
	<td class="dataRecord" align="right" width="50%">
 	<?php if (!$_SESSION['read_only']) {
		echo('<input type="submit" name="adduser" value="Register" class="formButton">&nbsp;&nbsp;&nbsp;');
	}?>
	</td>
        <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
 </tr>


 <tr align="center">
  <td colspan="2" class="dataRecord" >
 </tr>

</table>
</form>
