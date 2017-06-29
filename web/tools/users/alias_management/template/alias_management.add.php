<?
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

if($clone =="1"){
	$id=$_GET['id'];

	$sql = "select * from ".$table." where id='".$id."'";
	$resultset = $link->queryAll($sql);
	$link->disconnect();
	$setid = $resultset[0]['setid'];
	$destination = $resultset[0]['destination'];
	$flags =$resultset[0]['flags'];
	$description =$resultset[0]['description'];
}

?>
<form id="addnewalias" action="<?=$page_name?>?action=add_verified&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="aliasTitle">New Alias</td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Username:</b></td>
  <td class="dataRecord" width="275"><input <?php if (isset($_SESSION['fromusrmgmt'])) if ($_SESSION['fromusrmgmt']) echo "readonly ";?> type="text" name="username" 
  value="<?=$username?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Domain:</b></td>
  <td class="dataRecord" width="275"><?php print_domains("domain",'',FALSE)?></td>
  <?php if (isset($_SESSION['fromusrmgmt'])) 
  			if ($_SESSION['fromusrmgmt']){ 
				echo "<script>\n";
				echo "setReadonly('domain');\n";
				echo "</script>\n";
			}
  ?>
 </tr>

 <tr>
  <td class="dataRecord"><b>Alias Username:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="alias_username"  value="<?=$alias_username?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Alias Domain:</b></td>
  <td class="dataRecord" width="275"><?php print_domains("alias_domain",'',FALSE)?></td>
 </tr>
 
 <tr>
  <td class="dataRecord"><b>Alias Type:</b></td>
  <td class="dataRecord" width="275"><?php print_aliasType('',FALSE)?></td>
 </tr>
  
 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="add" value="Add" class="formButton"  onClick ="return Form_Validator(<?php echo $config->alias_format; ?>);"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>
