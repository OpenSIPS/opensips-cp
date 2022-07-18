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
$sql_vals=array();
$search_cid=$_SESSION['cl_cid'];
$search_url=$_SESSION['cl_url'];
if($search_cid!="") {
	$sql_search.=" and cluster_id=?";
	array_push( $sql_vals, $search_cid);
}
if($search_url!="") {
	$sql_search.=" and url like ?";
	array_push( $sql_vals, "%".$search_url."%");
}

if(!$_SESSION['read_only']){
	$colspan = 10;
}else{
	$colspan = 8;
}
?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=search" method="post">
<?php csrfguard_generate(); ?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
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
  <td colspan="2" class="searchRecord border-bottom-devider" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>
</table>
</form>

<?php if (!$_SESSION['read_only']) { ?>
<form action="<?=$page_name?>?action=add" method="post">
<?php csrfguard_generate(); ?>
  <input type="submit" name="add_new" value="Add Node" class="formButton"> &nbsp;&nbsp;&nbsp;
  <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
</form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Cluster ID</th>
  <th class="listTitle">Node ID</th>
  <th class="listTitle">BIN URL</th>
  <th class="listTitle">Max retries</th>
  <th class="listTitle">In Use</th>
  <th class="listTitle">SIP address</th>
  <th class="listTitle">Flags</th>
  <th class="listTitle">Description</th>
  <?php
  if(!$_SESSION['read_only']){

  	echo('<th class="listTitle">Edit</th>
  		<th class="listTitle">Delete</th>');
  }
  ?>
 </tr>
<?php
if ($sql_search=="") $sql_command="select * from ".$table;
else $sql_command="select * from ".$table." where (1=1) ".$sql_search;
$stm = $link->prepare($sql_command);
if ($stm === false) {
	die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
}
$stm->execute( $sql_vals );
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

if (count($resultset)==0)
	echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	for ($i=0;count($resultset)>$i;$i++)
	{
		if ($i%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if(!$_SESSION['read_only']){
			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'"onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a>';
		}
		?>
		<tr>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['cluster_id']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['node_id']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['url']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['no_ping_retries']?></td>
			<?php
			$state = ($resultset[$i]["state"]=="1")?"Active":"Inactive";
			if($_SESSION['read_only']){
				$state_info= '<img src="../../../images/share/'.strtolower($state).'.png" alt="'.$state.'">';
			} else {
			        $state_info= '<a href="'.$page_name.'?action=change_state&state='.$resultset[$i]['state'].'&id='.$resultset[$i]['id'].'"><img name="status'.$i.'" src="../../../images/share/'.strtolower($state).'.png" alt="'.$state.'" onclick="return confirmStateChange(\''.$state.'\')" border="0"></a>';
			}
			?>
			<td class="<?=$row_style."Img"?>" align="center">&nbsp;<?php echo $state_info?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['sip_addr']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['flags']?></td>
			<td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['description']?></td>
 			<?php 
 			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'Img" align="center">'.$edit_link.'</td>
	 			<td class="'.$row_style.'Img" align="center">'.$delete_link.'</td>');
   			}
			?>  
		</tr>  
		<?php
	}
}
?>
</table>
<br>


