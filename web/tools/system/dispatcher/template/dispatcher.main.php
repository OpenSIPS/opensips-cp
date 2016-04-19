<!--
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
-->

<?php

$flag = array();
$sipURI = array();

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
for ($i=0;$i<count($mi_connectors);$i++){

		$comm_type=mi_get_conn_params($mi_connectors[$i]);
        $message=mi_command('ds_list',$errors,$status);
        print_r($errors);
        $status = trim($status);
}
if ($comm_type != "json"){
	preg_match_all('/URI\:\:\s+sip\:[0-9\.a-zA-Z]+\:\d+\s+state\=(Active|Inactive|Probing)/',$message,$matches);
	for($j=0; count($matches[0])>$j; $j++) {
		$temp = explode(" ",$matches[0][$j]);
		$sipURI[] = $temp[1];
		$fl = explode("=",$temp[2]);
		$flag[] = $fl[1];
	}
}
else {
	$message = json_decode($message,true);
	$message = $message['SET'];

	for ($j=0; $j<count($message); $j++){
		$sipURI[] 	= $message[$j]['children']['URI'][0]['value'];
		$flag[] 	= $message[$j]['children']['URI'][0]['attributes']['state'];
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
	$colspan = 11;
}else{
	$colspan = 8;
}
  ?>
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=dp_act" method="post">
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="dispatcherTitle"></td>
 </tr>
  <tr>
  <td class="searchRecord">Setid</td>
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
  <td colspan="2" class="dispatcherTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
<tr>
<td align="center">
<form action="<?=$page_name?>?action=add&clone=0" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>
</td>
</tr>
</table>
<br>


<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="dispatcherTitle">ID</th>
  <th class="dispatcherTitle">Setid</th>
  <th class="dispatcherTitle">Destination</th>
  <th class="dispatcherTitle">Socket</th>
  <th class="dispatcherTitle">Weight</th>
  <th class="dispatcherTitle">Attributes</th>
  <th class="dispatcherTitle">Description</th>
  <th class="dispatcherTitle">DB State</th>
  <?
  if(!$_SESSION['read_only']){
  	echo('<th class="dispatcherTitle">Memory State</th>

  	<th class="dispatcherTitle">Edit</th>
  	<th class="dispatcherTitle">Delete</th>');
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

			$edit_link = '<a href="'.$page_name.'?action=edit&clone=0&id='.$resultset[$i]['id'].'"><img src="images/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&clone=0&id='.$resultset[$i]['id'].'" onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';
			if ($state[$i]== "-") 
				$state_link = $state[$i];
			else
				$state_link = '<a href="'.$page_name.'?action=change_state&state='.$state[$i].'&group='.$resultset[$i]['setid'].'&address='.$resultset[$i]['destination'].'"><img align="center" name="status'.$i.'" src="images/'.strtolower($state[$i]).'.png" alt="'.$state[$i].'" onclick="return confirmStateChange(\''.$state[$i].'\')" border="0"></a>';
		}

?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['id']?></td>
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
  <th colspan="<?=$colspan?>" class="dispatcherTitle">
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


