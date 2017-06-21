<?php
/*
* Copyright (C) 2017 OpenSIPS Project
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

if (!$_SESSION[read_only]) {

	if ($action=="edit") {
		## edit form
		$url = $page_name."?action=save&id=".$_GET['id'];
		$title = "Edit Domain name";
		# populate the initial values for the form
		$sql='SELECT domain FROM '.$table.' where id='.$_GET['id'];
		$domain_form = $link->queryRow($sql);
		$link->disconnect();
		$button = "Save";
	} else {
		## insert form
		$url = $page_name."?action=add";
		$title = "New Domain name";
		# populate the initial values for the form
		$domain_form['domain'] = null;
		$button = "Add";
	}
	?>

	<form action="<?=$url?>" method="post">
		<table width="400" cellspacing="2" cellpadding="2" border="0">
		<tr align="center">
			<td colspan="2" class="searchTitle">
			<?=$title?>
			</td>
	 	</tr>
		<?php
		require("domains.form.php"); ?>
		<tr>
			<td colspan="2" class="dataRecord" align="center">
			<input type="submit" name="add" disabled=true value="<?=$button?>" class="formButton">
			</td>
		</tr>
		<tr height="10">
			<td colspan="2" class="dataTitle">
			<img src="../../../images/share/spacer.gif" width="5" height="5">
			</td>
		</tr>
		</table>
	</form>
	<?php
}





$index_row=0;
$sql='SELECT * FROM '.$table.' ORDER BY domain ASC';
$resultset = $link->query($sql);
if(PEAR::isError($resultset)) {
	$errors = 'Failed to issue query, error message : ' . $resultset->getMessage();
	$data_no = 0;
} else {
	$data_no = $resultset->numRows();
}
$link->disconnect();

?>
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<br>

<table class="ttable" width="400" cellspacing="2" cellpadding="2" border="0">
	<tr>
  		<th align="center" class="searchTitle">Domain Name</th>
  		<th align="center" class="searchTitle">Last Modified</th>
		<th align="center" class="searchTitle">Edit</th>
		<th align="center" class="searchTitle">Delete</th>
	</tr>
	<?php
	if ($data_no==0) echo('<tr><td class="rowEven" colspan="4" align="center"><br>'.$no_result.'<br><br></td></tr>');
	else
	while($row = $resultset->fetchRow())
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";
		$edit_link='<a href="'.$page_name.'?action=edit&id='.$row['id'].'"><img src="../../../images/share/edit.gif" border="0"></a>';
		$delete_link='<a href="'.$page_name.'?action=delete&id='.$row['id'].'" onclick="return confirmDelete(\''.$row['domain'].'\')" ><img src="../../../images/share/trash.gif" border="0"></a>';
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
	?>
 	<tr>
  		<th colspan="4" class="searchTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></th>
 	</tr>
</table>
