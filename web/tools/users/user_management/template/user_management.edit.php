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

$id=$_GET['id'];
	
$sql = "select * from ".$table." where id='".$id."'";
$resultset = $link->queryAll($sql);
$link->disconnect();
?>

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr>
 <td colspan="2" class="listTitle" align="center">Edit User Information</td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Username</b></td>
  <td class="dataRecord" width="200"><input type="text" name="uname" value="<?=$resultset[0]['username']?>" maxlength="128" class="dataInput"></td>
 </tr>
 
 <tr>
  <td class="dataRecord"><b>Domain</b></td>
  <td class="dataRecord" width="200"><?php print_domains("domain",$resultset[0]['domain'],FALSE)?></td>
 </tr>
 
 <tr>
  <td class="dataRecord"><b>Email</b></td>
  <td class="dataRecord" width="200"><input type="text" name="email" value="<?=$resultset[0]['email_address']?>" maxlength="128" class="dataInput"></td>
 </tr>

<?php
	foreach ( $config->subs_extra as $key => $value ) {
?>
 <tr>
  <td class="dataRecord"><b><?=$value?></b></td>
  <td class="dataRecord" width="200"><input type="text" name="extra_<?=$key?>" value="<?=$resultset[0][$key]?>" maxlength="128" class="dataInput"></td>
 </tr>
<?php
    }
?>

 <tr>
  <td class="dataRecord"><b>New Password</b></td>
  <td class="dataRecord" width="200"><input type="password" name="passwd" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Retype Password</b></td>
  <td class="dataRecord" width="200"><input type="password" name="r_passwd" maxlength="128" class="dataInput"></td>
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

