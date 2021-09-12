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


if (!$_SESSION['read_only']) {

	if ($action=="edit") {
		## edit form
		$url = $page_name."?action=save&id=".$_GET['id'];
		$title = "Edit Domain name";
		# populate the initial values for the form
		$sql='SELECT domain, attrs FROM '.$table.' where id=?';
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($_GET['id']) );
		$domain_form = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
		$button = "Save Domain";
	} else {
		## insert form
		$url = $page_name."?action=add";
		$title = "New Domain name";
		# populate the initial values for the form
		$domain_form['domain'] = null;
		$button = "Add New Domain";
	}
	?>

	<form action="<?=$url?>" method="post">
		<table  width="350" cellspacing="2" cellpadding="2" border="0">
		<tr align="center">
			<td colspan="2" class="mainTitle">
			<?=$title?>
			</td>
	 	</tr>
		<?php
		require("domains.form.php"); ?>
            <!--   Add attribute  -->
            <tr >
                <td class="dataRecord" style="margin-top: 1.1rem">
                    <b>Attribute</b>
                    <div class="tooltip"><sup>?</sup>
                        <span class="tooltiptext"></span>
                    </div>
                </td>
                <td class="dataRecord" width="250" style="margin-top: 1.1rem">
                    <table style="width:100%">
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="attr" value="<?php print(!empty($attrs) ? $attrs : "")?>" id="attr" maxlength="128" class="dataInput" opt="n">
                            </td>
                            <td width="20">
                                <div id="domain_ok"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
		<tr>
			<td colspan="2" align="center">
			    <input type="submit" name="add" disabled=true value="<?=$button?>" class="formButton">&nbsp;&nbsp;&nbsp;
			    <?php if ($action=="edit") {?>
			    <?php print_back_input(); ?>
			    <?php } else { ?>
  			    <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
			    <?php } ?>
			</td>
		</tr>
		</table>
	</form>
	<?php
}





$sql='SELECT * FROM '.$table.' ORDER BY domain ASC';
$stm = $link->prepare( $sql );
if ($stm===FALSE)
	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
$stm->execute( array() );
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
$data_no = count($resultset);
$link=NULL;

?>
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<br>

<table class="ttable" cellspacing="2" cellpadding="2" border="0">
    <thead>
    <tr>
        <th align="center" class="listTitle">Domain Name</th>
        <!--        Add new column : Attribute -->
        <th align="center" class="listTitle">Attribute</th>
        <th align="center" class="listTitle">Last Modified</th>
        <th align="center" class="listTitle">Edit</th>
        <th align="center" class="listTitle">Delete</th>
    </tr>
    </thead>
	<?php
	$index_row=0;
    if ($data_no==0) {
        if (isset($_SESSION['ntl_toolbar']) && $_SESSION['ntl_toolbar'])
            echo($no_result);
        else
            echo('<tr><td colspan="4" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
    }
	else
	while( $index_row<$data_no )
	{
		$row = $resultset[$index_row++];
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";
		$edit_link='<a href="'.$page_name.'?action=edit&id='.$row['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
		$delete_link='<a href="'.$page_name.'?action=delete&id='.$row['id'].'" onclick="return confirmDelete(\''.$row['domain'].'\')" ><img src="../../../images/share/delete.png" border="0"></a>';
		if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
		?>
	<tr>
   		<td class="<?=$row_style?>"><?=$row['domain']?></td>
        <!--        Add new column : Attribute @ntlToolbar-->
        <td class="<?= $row_style ?>"><?= $row['attrs'] ?></td>
		<td class="<?=$row_style?>"><?=$row['last_modified']?></td>
		<td class="<?=$row_style."Img"?>" align="center"><?=$edit_link?></td>
  	 	<td class="<?=$row_style."Img"?>" align="center"><?=$delete_link?></td>
  	</tr>
	<?php
	}
	?>
</table>
