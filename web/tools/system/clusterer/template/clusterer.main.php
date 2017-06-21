<?php
 /*
 * Copyright (C) 2016 OpenSIPS Project
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


$sql_search="";
$search_cid=$_SESSION['cl_cid'];
$search_url=$_SESSION['cl_url'];
if($search_cid!="") $sql_search.=" and cluster_id=".$search_cid;
if($search_url!="") $sql_search.=" and url like '%".$search_url."%'";

if(!$_SESSION['read_only']){
	$colspan = 8;
}else{
	$colspan = 6;
}
?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=search" method="post">
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="searchTitle"></td>
 </tr>
  <tr>
  <td class="searchRecord">Cluster ID</td>
  <td class="searchRecord" width="200"><input type="text" name="cl_cid" 
  value="<?=$search_cid?>" maxlength="16" class="searchInput"></td>
 </tr>
  <tr>
  <td class="searchRecord">URL</td>
  <td class="searchRecord" width="200"><input type="text" name="cl_url" 
  value="<?=$search_url?>" maxlength="16" class="searchInput"></td>
 </tr>
  <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="searchTitle">Cluster ID</th>
  <th class="searchTitle">Node ID</th>
  <th class="searchTitle">BIN URL</th>
  <th class="searchTitle">Max retries</th>
  <th class="searchTitle">In Use</th>
  <th class="searchTitle">Description</th>
  <?
  if(!$_SESSION['read_only']){

  	echo('<th class="searchTitle">Edit</th>
  		<th class="searchTitle">Delete</th>');
  }
  ?>
 </tr>
<?php
if ($sql_search=="") $sql_command="select * from ".$table;
else $sql_command="select * from ".$table." where (1=1) ".$sql_search;
$resultset = $link->queryAll($sql_command);
if(PEAR::isError($resultset)) {
	die('Failed to issue query, error message : ' . $resultset->getMessage());
}
if (count($resultset)==0)
	echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	for ($i=0;count($resultset)>$i;$i++)
	{
		if ($i%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if(!$_SESSION['read_only']){
			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'"onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
		}
		?>
		<tr>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['cluster_id']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['node_id']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['url']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['no_ping_retries']?></td>
			<?
			$state = ($resultset[$i]["state"]=="1")?"Active":"Inactive";
			if($_SESSION['read_only']){
				$state_info= '<img align="center" src="../../../images/share/'.strtolower($state).'.png" alt="'.$state.'">';
			} else {
			        $state_info= '<a href="'.$page_name.'?action=change_state&state='.$resultset[$i]['state'].'&id='.$resultset[$i]['id'].'"><img align="center" name="status'.$i.'" src="../../../images/share/'.strtolower($state).'.png" alt="'.$state.'" onclick="return confirmStateChange(\''.$state.'\')" border="0"></a>';
			}
			?>
			<td class="<?=$row_style?>">&nbsp;<?php echo $state_info?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['description']?></td>
 			<? 
 			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'" align="center">'.$edit_link.'</td>
		 			<td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
   			}
			?>  
		</tr>  
		<?php
	}
}
?>
 <tr>
   <th colspan="<?echo($colspan);?>" class="searchTitle"><img src="../../../images/spacer.gif" width="5" height="5"></th>
 </tr>

</table>
<br>


