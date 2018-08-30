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
?>


<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<?php
$sql_search="";
$qvalues = array();

if (isset($_SESSION['address_src']))
	$search_src=$_SESSION['address_src'];
else
	$search_src="";
if (isset($_SESSION['address_proto']))
	$search_proto=$_SESSION['address_proto'];
else
	$search_proto="";
if (isset($_SESSION['address_port']))
	$search_port=$_SESSION['address_port'];
else
	$search_port="";

if ($search_src != "") {
	$sql_search .= " and ip like ?";
	$qvalues[] = "%" . $search_src . "%";
}

if ($search_proto != "") {
	$sql_search .= " and proto like ?";
	$qvalues[] = "%" . $search_proto . "%";
}

if ($search_port != "") {
	$sql_search .= " and port like ?";
	$qvalues[] = "%" . $search_port . "%";
}

require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
	$colspan = 9;
}else{
	$colspan = 7;
}
  ?>
<form action="<?=$page_name?>?action=dp_act" method="post">
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord">IP</td>
  <td class="searchRecord" width="200"><input type="text" name="address_src" 
  value="<?=$search_src?>" maxlength="16" class="searchInput"></td>
 </tr>
  <tr>
  <td class="searchRecord">Protocol</td>
  <td class="searchRecord" width="200"><input type="text" name="address_proto" 
  value="<?=$search_proto?>" maxlength="16" class="searchInput"></td>
 </tr>
  <tr>
  <td class="searchRecord">Port</td>
  <td class="searchRecord" width="200"><input type="text" name="address_port" 
  value="<?=$search_port?>" maxlength="16" class="searchInput"></td>
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
  <input type="submit" name="add_new" value="Add Address" class="formButton"> &nbsp;&nbsp;&nbsp;
  <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
</form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Group</th>
  <th class="listTitle">IP</th>
  <th class="listTitle">Mask</th>
  <th class="listTitle">Port</th>
  <th class="listTitle">Protocol</th>
  <th class="listTitle">Pattern</th>
  <th class="listTitle">Context Info</th>
  <?php
  if(!$_SESSION['read_only']){

  	echo('<th class="listTitle">Edit</th>
  		<th class="listTitle">Delete</th>');
  }
  ?>
 </tr>
<?php

$sql_command="select * from ".$table." where (1=1) ".$sql_search;
$stm = $link->prepare($sql_command);
if ($stm->execute($qvalues) === false)
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

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
	$stm = $link->prepare($sql_command);
	if ($stm->execute($qvalues) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	require("lib/".$page_id.".main.js");
	$index_row=0;
	for ($i=0;count($resultset)>$i;$i++)
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if(!$_SESSION['read_only']){

			$edit_link = '<a href="'.$page_name.'?action=edit&clone=0&id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&clone=0&id='.$resultset[$i]['id'].'"onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a>';
		}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['grp']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['ip']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['mask']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['port']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['proto']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['pattern']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php echo $resultset[$i]['context_info']?></td>
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
 <tr>
  <th colspan="<?=$colspan?>">
    <table class="pagingTable">
     <tr>
      <th align="left">Page:
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


