<?php
/*
 * * Copyright (C) 2011 OpenSIPS Project
 * *
 * * This file is part of opensips-cp, a free Web Control Panel Application for
 * * OpenSIPS SIP server.
 * *
 * * opensips-cp is free software; you can redistribute it and/or modify
 * * it under the terms of the GNU General Public License as published by
 * * the Free Software Foundation; either version 2 of the License, or
 * * (at your option) any later version.
 * *
 * * opensips-cp is distributed in the hope that it will be useful,
 * * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * * GNU General Public License for more details.
 * *
 * * You should have received a copy of the GNU General Public License
 * * along with this program; if not, write to the Free Software
 * * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * */
?>
<form action="<?=$page_name?>?action=add_verify" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="mainTitle">Add New Admin</td>
 </tr>
 <tr>
  <td class="dataRecord" >First Name</td>
  <td class="dataRecord" width="275"><input type="text" name="add_fname"
  value="<?=isset($fname)?$fname:""?>" class="dataInput"></td>
 </td>
 <tr>
  <td class="dataRecord" >Last Name</td>
  <td class="dataRecord" width="275"><input type="text" name="add_lname"
  value="<?=isset($lname)?$lname:""?>" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Username</td>
  <td class="dataRecord" width="275"><input type="text" name="add_uname"
  value="<?=isset($uname)?$uname:""?>" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Password</td>
  <td class="dataRecord" width="275"><input type="password" name="add_passwd"
  value="<?=isset($passwd)?$passwd:""?>" class="dataInput" autocomplete="off"></td>
 </tr>
 <tr>
  <td class="dataRecord" >Confirm Password</td>
  <td class="dataRecord" width="275"><input type="password" name="confirm_passwd"
  value="<?=isset($confirm_passwd)?$confirm_passwd:""?>" class="dataInput" autocomplete="off"></td>
 </tr>
 <tr>
  <td colspan="2">
    <table cellspacing=20>
      <tr>
        <td class="dataRecord" align="right" width="50%"><input type="submit" name="addadmin" value="Add" class="formButton"></td>
        <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
 </tr>
</table>
</form>
