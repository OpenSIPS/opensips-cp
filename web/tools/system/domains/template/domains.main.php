<?php
/*
* $Id$
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
if ($form_domain!=null) {
	$form_action="save";
	$form_title="Edit Domain Name";
	$form_input='<input name="new_domain" type="text" class="newDomain" value="'.$form_domain.'">';
	$form_button='<input name="save" type="submit" value="Save Changes" class="Button">';
	$form_extra='<input name="old_domain" type="hidden" value="'.$form_domain.'"';
}
else {
	$form_action="add";
	$form_title="New Domain Name";
	$form_input='<input name="new_domain" type="text" class="newDomain" value="">';
	$form_button='<input name="add" type="submit" value="Add Domain" class="Button">';
}
?>

<?php

if (!$_SESSION[read_only]) {
?> 
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>
<form action="<?=$page_name?>?action=<?=$form_action?>" method="post">
<?=$form_extra?>
<table width="250" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="domainTitle" align="center"><?=$form_title?></td>
 </tr>
<?php
if ($error!="") echo('<tr><td class="rowOdd" align="center"><div class="formError">'.$error.'</div></td></tr>');
if ($info!="") echo('<tr><td class="rowOdd" align="center"><div class="formInfo">'.$info.'</div></td></tr>');
?>
 <tr>
  <td class="rowOdd" align="center"><?=$form_input?></td>
 </tr>
 <tr>
  <td class="rowOdd" align="center"><?=$form_button?></td>
 </tr>
 <tr>
  <td class="domainTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<br>
<?php
}
?>

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td align="center" class="domainTitle">Domain Name</td>
  <td align="center" class="domainTitle">Last Modified</td>
  <td align="center" class="domainTitle">Edit</td>
  <td align="center" class="domainTitle">Delete</td>
 </tr>
<?php
//include("lib/db_connect.php");
$index_row=0;
$sql='SELECT * FROM '.$table.' WHERE (1=1) ORDER BY domain ASC';
$resultset = $link->query($sql);
if(PEAR::isError($resultset)) {
	die('Failed to issue query, error message : ' . $resultset->getMessage());
}
$data_no = $resultset->numRows();

if ($data_no==0) echo('<tr><td class="rowEven" colspan="4" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
while($row = $resultset->fetchRow())
{
	$index_row++;
	if ($index_row%2==1) $row_style="rowOdd";
	else $row_style="rowEven";
	$edit_link='<a href="'.$page_name.'?action=edit&domain='.$row['domain'].'"><img src="images/edit.gif" border="0"></a>';
	$delete_link='<a href="'.$page_name.'?action=delete&domain='.$row['domain'].'" onclick="return confirmDelete(\''.$row['domain'].'\')" ><img src="images/trash.gif" border="0"></a>';
if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
  ?>
  <tr>
   <td class="<?=$row_style?>"><?=$row['domain']?></td>
   <td class="<?=$row_style?>"><?=$row['last_modified']?></td>
   <td class="<?=$row_style?>" align="center"><?=$edit_link?></td>
   <td class="<?=$row_style?>" align="center"><?=$delete_link?></td>
  </tr>
  <?php
}
$link->disconnect();
?>
 <tr>
  <td colspan="4" class="domainTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
