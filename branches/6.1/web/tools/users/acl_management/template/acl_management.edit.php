<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>&table=<?=$_GET['table']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
<?php
/*
 * $Id: alias_management.edit.php 210 2010-03-08 18:09:33Z bogdan_iancu $
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
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="aclTitle">Edit ACL</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Username</b></td>
  <td class="dataRecord" width="275"><input readonly type="text" name="username" value="<?=$resultset[0]['username']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Domain</b></td>
  <td class="searchRecord" width="200"><?php print_domains("domain",$resultset[0]['domain']);?></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Group</b></td>
  <td class="dataRecord" width="275"><?php print_groups("acl_grp",$resultset[0]['grp']);?></td>
  </tr>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

