<!--
 /*
 * $Id: tviewer.main.php 133 2009-10-29 18:05:56Z iulia_bublea $
 * Copyright (C) 2008 Voice Sistem SRL
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

<form action="<?=$page_name?>?action=dp_act" method="post">
<?php
$sql_search="";
foreach ($config->custom_table_columns as $key => $value){
    	if (isset($_SESSION[$value]))
		if ($_SESSION[$value] !="")
			$sql_search.=" and ".$value." like '%".$_SESSION[$value]."%'";
}
//$search_setid=$_SESSION['tviewer_setid'];
//$search_dest=$_SESSION['tviewer_dest'];
//$search_descr=$_SESSION['tviewer_descr'];
//if($search_setid !="") $sql_search.=" and setid=" . $search_setid;
//if($search_dest !="") $sql_search.=" and destination like '%".$search_dest."%'";
//if($search_descr !="") $sql_search.=" and description like '%".$search_descr."%'";
require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
	$colspan = count($config->custom_table_columns)+4;
}else{
	$colspan = count($config->custom_table_columns);
}
  ?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="tviewerTitle"></td>
 </tr>
<?php
  foreach ($config->custom_table_columns as $key => $value){
?>
  <tr>
  <td class="searchRecord" align="left"><?php echo $key; ?>:</td>
  <td class="searchRecord" width="200"><input type="text" name="<?=$value; ?>" value="<?=$_SESSION[$value]?>" class="searchInput"></td>
  </tr>
<?php } ?>
  <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>

 <tr height="10">
  <td colspan="2" class="tviewerTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>

<table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <?php
  	foreach ($config->custom_table_columns as $key => $value){
  ?>
  <td class="tviewerTitle"><?php echo $key; ?></td>
  <?
  }
  if(!$_SESSION['read_only']){

  	echo('<td class="tviewerTitle">Edit</td>
  		<td class="tviewerTitle">Delete</td>');
  }
  ?>
 </tr>
<?php
if ($sql_search=="") $sql_command="select * from ".$config->custom_table." where (1=1) order by ".$config->custom_table_primary_key." asc";
else $sql_command="select * from ".$config->custom_table." where (1=1) ".$sql_search." order by id asc";
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
	while (count($resultset)>$i)
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if(!$_SESSION['read_only']){

			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i][$config->custom_table_primary_key].'"><img src="images/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&clone=0&id='.$resultset[$i][$config->custom_table_primary_key].'"onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';
		}
?>
 <tr>
  <?php
        foreach ($config->custom_table_columns as $key => $value){
  ?>
  <td class="<?=$row_style?>">&nbsp;<?
					if ($value == "timeout")
			                        echo date("Y-m-d  H:i:s", $resultset[$i][$value]);
			                else
                        			echo $resultset[$i][$value]
					?></td>
   <?
  } 
   if(!$_SESSION['read_only']){
   	echo('<td class="'.$row_style.'" align="center">'.$edit_link.'</td>
			  <td class="'.$row_style.'" align="center">'.$delete_link.'</td>');
   }
	?>  
  </tr>  
<?php

	$i++;
	}
}
?>
 <tr>
  <td colspan="<?=$colspan?>" class="tviewerTitle">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
     <tr>
      <td align="left">
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
      </td>
      <td align="right">Total Records: <?=$data_no?>&nbsp;</td>
     </tr>
    </table>
  </td>
 </tr>
</table>
<br>


