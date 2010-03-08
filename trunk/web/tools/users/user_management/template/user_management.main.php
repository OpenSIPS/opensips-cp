<!--
 /*
 * $Id$
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
$search_uname = $_SESSION['lst_uname'];
$search_domain = $_SESSION['lst_domain'];
$search_email = $_SESSION['lst_email'];
if($search_uname !="") $sql_search.=" AND s.username like '" . $search_uname."%'";
else $sql_search.=" AND s.username like '%'";
if(($search_domain =="ANY") || ($search_domain == "")) $sql_search.=" and s.domain like '%'";
else $sql_search.=" AND s.domain ='" . $search_domain."'";
if($search_email !="") $sql_search.=" and s.email_address like '".$search_email."%'";
else $sql_search.=" and s.email_address like '%'";
require("lib/".$page_id.".main.js");
if(!$_SESSION['read_only']){
	$colspan = 4;
}else{
	$colspan = 2;
}

if ( $users == "online_usr" ) {
	$checkedAll="";
	$checkedOnline="checked";
	$checkedOffline="";
} else if ( $users == "offline_usr" ) {
	$checkedAll="";
	$checkedOnline="";
	$checkedOffline="checked";
} else {
	$users = "all_usr";
	$checkedAll='checked';
	$checkedOnline="";
	$checkedOffline="";
}
  ?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="listTitle"></td>
 </tr>
 <tr>
  <td class="searchRecord" align="center">Username</td>
  <td class="searchRecord" width="200"><input type="text" name="lst_uname" 
  value="<?=$search_uname?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr>	
  <td class="searchRecord" align="center">Domain</td>
  <td class="searchRecord" width="200"<?php print_domains("list_domain","ANY")?></td>
 </tr>
 <tr>
  <td class="searchRecord" align="center">Email:</td>
  <td class="searchRecord" width="200"><input type="text" name="lst_email" 
  value="<?=$search_email?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr>
  <td align="right"><input type="radio" name="users" 
  value="all_usr" <?php if ($users=="all_usr") echo "checked=\"true\"";?> ></td>
  <td class="searchRecord" align="center">All Users:</td>
 </tr>
 <tr>	
  <td align="right"><input type="radio" name="users" 
  value="online_usr" $checkedOnline  <?php if ($users=="online_usr") echo "checked=\"true\"";?> ></td>
  <td class="searchRecord" align="center">Online Users:</td>
 </tr>
 <tr>	
  <td align="right"><input type="radio" name="users" 
  value="offline_usr" <?php if ($users=="offline_usr") echo "checked=\"true\"";?>  ></td>
  <td class="searchRecord" align="center">Offline Users:</td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>


 <tr height="10">
  <td colspan="2" class="listTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>

<table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="listTitle">Username</td>
  <td class="listTitle">Email Address</td>
  <?
  if(!$_SESSION['read_only']){

  	echo('<td class="listTitle">Edit</td>
  		<td class="listTitle">Delete</td>');
  }
  ?>
 </tr>
<?php
if ($users=="all_usr" || $users=="") {
	if ($sql_search=="") $sql_command="select * from ".$table." s where (1=1) order by s.id asc";
	else $sql_command="select * from ".$table." s where (1=1) ".$sql_search." order by s.id asc";
} else if ($users=="online_usr") {
	if ($sql_search=="") $sql_command="select * from ".$table." s, $config->table_location l where (1=1) s.username=l.username AND s.domain=l.domain order by s.id asc";
	else $sql_command="select * from ".$table." s, $config->table_location l where (1=1) AND s.username=l.username AND s.domain=l.domain ".$sql_search." order by s.id asc";
} else if ($users=="offline_usr") {
	if ($sql_search=="") $sql_command="select * from ".$table." s where (1=1) AND s.username NOT IN (select s.username from $table s, $config->table_location l where s.username=l.username AND s.domain=l.domain) order by s.id asc";
	else $sql_command="select * from ".$table." s where (1=1) AND s.username NOT IN (select s.username from $table s,$config->table_location l where s.username=l.username AND s.domain=l.domain )".$sql_search." order by s.id asc";
}

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

			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'&table='.$table.'"><img src="images/edit.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'&uname='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"onclick="return confirmDelete()"><img src="images/delete.png" border="0"></a>';
		}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['username'].'@'.$resultset[$i]['domain']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['email_address']?></td>
   <? 
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
  <td colspan="<?=$colspan?>" class="listTitle">
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

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add" value="Add New" class="formButton">') ?>
</form>

<br>


