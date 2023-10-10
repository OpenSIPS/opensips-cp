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


$flag = array();
$errors = array();
$sipURI = array();

$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

// date input from the first box only
$message=mi_command("ds_list", NULL, $mi_connectors[0], $errors);

if ($message!=NULL) {
	if ($message['PARTITIONS'])
		$message = $message['PARTITIONS'][0];

	if (isset($message['SETS']))
		$message = $message['SETS'];
	else
		$message = array();

	# iterate through the SETs
	for ($j=0; $j<count($message); $j++){
		# interate though the destinations from the set
		for ($i=0; $i<count($message[$j]['Destinations']); $i++){
			$sipURI[] = $message[$j]['Destinations'][$i]['URI'];
			$flag[] = $message[$j]['Destinations'][$i]['state'];
		}
	}
}

$sql_search="";
$sql_vals=array();

$search_setid=$_SESSION['dispatcher_setid'];
$search_dest=$_SESSION['dispatcher_dest'];
$search_descr=$_SESSION['dispatcher_descr'];

$dispatcher_group = get_settings_value("dispatcher_groups");
$dispatcher_group_mode = get_settings_value("dispatcher_groups_mode");
switch ($dispatcher_group_mode) {
	case "database":
		$set_cache = array();
		$query = "SELECT " . $dispatcher_group['id'] . " AS id, " .
			$dispatcher_group['name'] . " AS name " .
			"FROM " . $dispatcher_group['table'];

		$stm = $link->prepare($query);
		if ($stm===FALSE) {
			die('Failed to issue query [' . $query . '], error message : ' . $link->errorInfo()[2]);
		}
		$stm->execute();
		$results = $stm->fetchAll();
		foreach ($results as $key => $value)
			$set_cache[$value['id']] = $value['name'];
		break;

	case "array":
		$set_cache = $dispatcher_group;
		break;

	case "static":
		$search_setid = $dispatcher_group; /* always force the used dispatcher group */
		break;
}

if ($search_setid != "") {
	$sql_search.=" and setid=?";
	array_push( $sql_vals, $search_setid);
}

if($search_dest !="") {
	$sql_search.=" and destination like ?";
	array_push( $sql_vals, "%".$search_dest."%");
}
if($search_descr !="") {
	$sql_search.=" and description like ?";
	array_push( $sql_vals, "%".$search_descr."%");
}
require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
	$colspan = 10;
}else{
	$colspan = 7;
}
if ($dispatcher_group_mode == "static")
	$colspan--;
  ?>
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=ds_search" method="post">
<?php csrfguard_generate(); ?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord">SetID</td>
  <td class="searchRecord" width="200">
<?php if ($dispatcher_group_mode == "input") { ?>
  <input type="text" name="dispatcher_setid" value="<?=$search_setid?>" maxlength="16" class="searchInput">
<?php } else { ?>
 <select name="dispatcher_setid" id="dispatcher_setid" size="1" class="dataSelect" style="width:200;">
   <option value="">any</option>
<?php foreach ($dispatcher_group as $key=>$value) { ?>
<?php 	$selected = ($key == $search_setid)?"selected":""; ?>
<option value="<?=$key?>" <?=$selected?>><?=$value?></option>
<?php } ?>
 </select>
<?php } ?>
  </td>
 <tr>
 <td class="searchRecord">Destination</td>
 <td class="searchRecord" width="200"><input type="text" name="dispatcher_dest" 
 value="<?=$search_dest?>" maxlength="16" class="searchInput"></td>
</tr>
  <tr>
  <td class="searchRecord" >Description</td>
  <td class="searchRecord" width="200"><input type="text" name="dispatcher_descr" 
  value="<?=$search_descr?>" maxlength="16" class="searchInput"></td>
 </tr>
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
  <input type="submit" name="add_new" value="Add Destination" class="formButton"> &nbsp;&nbsp;&nbsp;
  <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
</form>
<?php } ?>



<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
<?php if ($dispatcher_group_mode != "static") { ?>
  <th class="listTitle">SetID</th>
<?php } ?>
  <th class="listTitle">Destination</th>
  <th class="listTitle">Socket</th>
  <th class="listTitle">Weight</th>
  <th class="listTitle">Attributes</th>
  <th class="listTitle">Description</th>
  <th class="listTitle">DB State</th>
  <?php
  if(!$_SESSION['read_only']){
  	echo('<th class="listTitle">Memory State</th>

  	<th class="listTitle">Edit</th>
  	<th class="listTitle">Delete</th>');
  }
  ?>
 </tr>
<?php
if ($sql_search=="") $sql_command="from ".$table." order by setid asc";
else $sql_command="from ".$table." where (1=1) ".$sql_search." order by id asc";
$stm = $link->prepare("select count(*) ".$sql_command);
if ($stm===FALSE) {
	die('Failed to issue query [select count (*) '.$sql_command.'], error message : ' . $link->errorInfo()[2]);
}
$stm->execute( $sql_vals );
$data_no = $stm->fetchColumn(0);
if ($data_no==0) echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	$res_no=get_settings_value("results_per_page");
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/$res_no);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	$start_limit=($page-1)*$res_no;
	//$sql_command.=" limit ".$start_limit.", ".$res_no;
	if ($start_limit==0) $sql_command.=" limit ".$res_no;
	else $sql_command.=" limit ".$res_no." OFFSET " . $start_limit;

	$stm = $link->prepare("select * ".$sql_command);
	if ($stm===FALSE)
		die('Failed to issue query [select * '.$sql_command.'], error message : ' . print_r($link->errorInfo(), true));
	$stm->execute( $sql_vals );
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	require("lib/".$page_id.".main.js");
	$index_row=0;
	$i=0;
	$state = array();
	while (count($resultset)>$i)
	{
		   switch ($resultset[$i]['state']) {
			case "0" : $db_state = "Active"; break;
			case "1" : $db_state = "Inactive"; break;
			case "2" : $db_state = "Probing"; break;
		   }
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if (in_array($resultset[$i]['destination'],$sipURI)) {
			$key = array_search($resultset[$i]['destination'],$sipURI);
			$state[$i] = trim($flag[$key]);
		} else {
		        $state[$i] = "-";
		}

		if(!$_SESSION['read_only']){

			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'" onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a>';
			if ($state[$i]== "-") 
				$state_link = $state[$i];
			else
				$state_link = '<a href="'.$page_name.'?action=change_state&state='.$state[$i].'&group='.$resultset[$i]['setid'].'&address='.$resultset[$i]['destination'].'"><img align="center" name="status'.$i.'" src="../../../images/share/'.strtolower($state[$i]).'.png" alt="'.$state[$i].'" onclick="return confirmStateChange(\''.$state[$i].'\')" border="0"></a>';
		}

?>
 <tr>
<?php switch ($dispatcher_group_mode) {
case "static":
	break;
case "input":
	echo('<td class="'.$row_style.'">&nbsp;'.$resultset[$i]['setid'].'</td>');
	break;
default:
	echo('<td class="'.$row_style.'">&nbsp;'.(isset($set_cache[$resultset[$i]['setid']])?$set_cache[$resultset[$i]['setid']]:$resultset[$i]['setid']).'</td>');
	break;
} ?>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['destination']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['socket']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['weight']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['attrs']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['description']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$db_state?></td>
  <?php 
   if(!$_SESSION['read_only']){
   	echo '<td class="'.$row_style.'Img" align="center">'.$state_link.'</td>';
   	echo '<td class="'.$row_style.'Img" align="center">'.$edit_link.'</td>';
	echo '<td class="'.$row_style.'Img" align="center">'.$delete_link.'</td>';
   }
	?>  
  </tr>  
<?php

	$i++;
	}
}
?>
 <tr>
  <th colspan="<?=$colspan?>">
    <table class="pagingTable">
     <tr>
      <th align="left">Page:
       <?php
       if ($data_no==0) echo('<font class="pageActive">0</font>&nbsp;');
       else {
       	$max_pages = get_settings_value("results_page_range");
       	// start page
       	if ($page % $max_pages == 0) $start_page = $page - $max_pages + 1;
       	else $start_page = $page - ($page % $max_pages) + 1;
       	// end page
       	$end_page = $start_page + $max_pages - 1;
       	if ($end_page > $page_no) $end_page = $page_no;
       	// back block
       	if ($start_page!=1) echo('&nbsp;<a href="'.$page_name.'?page='.($start_page-$max_pages).'" class="menuItem"><b>&lt;&lt;</b></a>&nbsp;');
       	// current pages
       	for($i=$start_page;$i<=$end_page;$i++)
       	if ($i==$page) echo('<font class="pageActive">'.$i.'</font>&nbsp;');
       	else echo('<a href="'.$page_name.'?page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
       	// next block
       	if ($end_page!=$page_no) echo('&nbsp;<a href="'.$page_name.'?page='.($start_page+$max_pages).'" class="menuItem"><b>&gt;&gt;</b></a>&nbsp;');
       }
       ?>
      </th>
      <th align="right">Total Records: <?=$data_no?>&nbsp;</th>
     </tr>
    </table>
  </th>
 </tr>
</table>
<br>


