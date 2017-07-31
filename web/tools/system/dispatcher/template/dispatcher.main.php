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
$sipURI = array();

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

// date input from the first box only
$message=mi_command('ds_list',$mi_connectors[0], $errors,$status);

$message = json_decode($message,true);
if ($message['PARTITION'])
	$message = $message['PARTITION'][0]['children'];
$message = $message['SET'];

# iterate through the SETs
for ($j=0; $j<count($message); $j++){
	# interate though the destinations from the set
	for ($i=0; $i<count($message[$j]['children']['URI']); $i++){
		$sipURI[] = $message[$j]['children']['URI'][$i]['value'];
		$flag[]   = $message[$j]['children']['URI'][$i]['attributes']['state'];
	}
}


$sql_search="";
$search_setid=$_SESSION['dispatcher_setid'];
$search_dest=$_SESSION['dispatcher_dest'];
$search_descr=$_SESSION['dispatcher_descr'];
if($search_setid !="") $sql_search.=" and setid=" . $search_setid;
if($search_dest !="") $sql_search.=" and destination like '%".$search_dest."%'";
if($search_descr !="") $sql_search.=" and description like '%".$search_descr."%'";
require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
	$colspan = 10;
}else{
	$colspan = 7;
}
  ?>
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=ds_search" method="post">
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="searchTitle"></td>
 </tr>
  <tr>
  <td class="searchRecord">SetID</td>
  <td class="searchRecord" width="200"><input type="text" name="dispatcher_setid" 
  value="<?=$search_setid?>" maxlength="16" class="searchInput"></td>
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
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>

 <tr height="10">
  <td colspan="2" class="searchTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
<tr>
<td align="center">
<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>
</td>
</tr>
</table>
<br>


<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="searchTitle">SetID</th>
  <th class="searchTitle">Destination</th>
  <th class="searchTitle">Socket</th>
  <th class="searchTitle">Weight</th>
  <th class="searchTitle">Attributes</th>
  <th class="searchTitle">Description</th>
  <th class="searchTitle">DB State</th>
  <?
  if(!$_SESSION['read_only']){
  	echo('<th class="searchTitle">Memory State</th>

  	<th class="searchTitle">Edit</th>
  	<th class="searchTitle">Delete</th>');
  }
  ?>
 </tr>
<?php
if ($sql_search=="") $sql_command="select * from ".$table." where (1=1) order by id asc";
else $sql_command="select * from ".$table." where (1=1) ".$sql_search." order by id asc";
$resultset = $link->queryAll($sql_command);
if(PEAR::isError($resultset)) {
	die('Failed to issue query, error message : ' . $resultset->getMessage());
}
$data_no=count($resultset);
if ($data_no==0) echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else
{
	$res_no=$config->results_per_page;
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

	$resultset = $link->queryAll($sql_command);
        if(PEAR::isError($resultset)) {
                die('Failed to issue query, error message : ' . $resultset->getMessage());
        }
	require("lib/".$page_id.".main.js");
	$index_row=0;
	$i=0;
	$state = array();
	while (count($resultset)>$i)
	{
		   switch ($resultset[$i]['state']) {
			case "0" : $db_state = "Active"; break;
			case "1" : $db_state = "Inactive"; break;
		   }
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if (in_array($resultset[$i]['destination'],$sipURI)) {
			$key = array_search($resultset[$i]['destination'],$sipURI);
			$state[$i] = $config->status[trim($flag[$key])];
		} else {
		        $state[$i] = "-";
		}

		if(!$_SESSION['read_only']){

			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'" onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
			if ($state[$i]== "-") 
				$state_link = $state[$i];
			else
				$state_link = '<a href="'.$page_name.'?action=change_state&state='.$state[$i].'&group='.$resultset[$i]['setid'].'&address='.$resultset[$i]['destination'].'"><img align="center" name="status'.$i.'" src="../../../images/share/'.strtolower($state[$i]).'.png" alt="'.$state[$i].'" onclick="return confirmStateChange(\''.$state[$i].'\')" border="0"></a>';
		}

?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['setid']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['destination']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['socket']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['weight']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['attrs']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['description']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$db_state?></td>
  <? 
   if(!$_SESSION['read_only']){
   	echo '<td class="'.$row_style.'" align="center">'.$state_link.'</td>';
   	echo '<td class="'.$row_style.'" align="center">'.$edit_link.'</td>';
	echo '<td class="'.$row_style.'" align="center">'.$delete_link.'</td>';
   }
	?>  
  </tr>  
<?php

	$i++;
	}
}
?>
 <tr>
  <th colspan="<?=$colspan?>" class="searchTitle">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <th align="left">
       &nbsp;Page:
       <?php
       if ($data_no==0) echo('<font class="pageActive">0</font>&nbsp;');
       else {
       	$max_pages = $config->results_page_range;
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


