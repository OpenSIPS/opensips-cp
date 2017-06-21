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

$sql_search="";
$search_uname = $_SESSION['list_uname'];
$search_fname = $_SESSION['list_fname'];
$search_lname = $_SESSION['list_lname'];
if($search_uname !="") $sql_search.=" AND username like '" . $search_uname."%'";
else $sql_search.=" AND username like '%'";
if($search_fname !="") $sql_search.=" and first_name like '".$search_fname."%'";
else $sql_search.=" and first_name like '%'";
if($search_lname !="") $sql_search.=" and last_name like '".$search_lname."%'";
else $sql_search.=" and last_name like '%'";

require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
	$colspan = 5;
}else{
	$colspan = 3;
}
?>
<form action="<?=$page_name?>?action=dp_act" method="post">
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="listadminsTitle"></td>
 </tr>
 <tr>
  <td class="searchRecord">Username</td>
  <td class="searchRecord" width="200"><input type="text" name="list_uname" 
  value="<?=$search_uname?>" maxlength="32" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">First Name</td>
  <td class="searchRecord" width="200"><input type="text" name="list_fname" 
  value="<?=$search_fname?>" maxlength="32" class="searchInput"></td>
 </tr>
  <tr>
  <td class="searchRecord">Last Name</td>
  <td class="searchRecord" width="200"><input type="text" name="list_lname" 
  value="<?=$search_lname?>" maxlength="32" class="searchInput"></td>
 </tr>
  <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>

 <tr height="10">
  <td colspan="2" class="listadminsTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listadminsTitle">Username</th>
  <th class="listadminsTitle">Name</th>
  <th class="listadminsTitle">Access</th>
  <?
  if(!$_SESSION['read_only']){

  	echo('<th class="listadminsTitle">Edit Info</th>
  		<th class="listadminsTitle">Delete</th>');
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
	while (count($resultset)>$i)
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		$edit_tools_link = '<a href="'.$page_name.'?action=edit_tools&id='.$resultset[$i]['id'].'&uname='.$resultset[$i]['username'].'"><img src="images/access.png" border="0"></a>';

		if(!$_SESSION['read_only']){

			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'"onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
		}
?>
 <tr align = "center">
  <td class="<?=$row_style?>">&nbsp;<?php print $resultset[$i]['username']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $resultset[$i]['first_name'].' '.$resultset[$i]['last_name']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $edit_tools_link?></td>
   <? 
   if(!$_SESSION['read_only']){
   ?>
	<?php
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
  <th colspan="<?=$colspan?>" class="listadminsTitle">
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


