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
if ($keepoverlay){
	echo "<script language='JavaScript'>";
	echo "show_contacts('".$_POST['username']."','".$_POST['domain']."')";
	echo "</script>";
}

$sql_search="";
$search_uname = $_SESSION['lst_uname'];
$search_domain = $_SESSION['lst_domain'];
$search_email = $_SESSION['lst_email'];

## check what other tools are available
require("../../../../config/modules.inc.php");
if ( file_exists("../acl_management") &&
$config_modules["users"]["modules"]["acl_management"]["enabled"]==true
)
	$has_acl = true;
else
	$has_acl = false;

if ( file_exists("../alias_management") &&
$config_modules["users"]["modules"]["alias_management"]["enabled"]==true
)
	$has_alias = true;
else
	$has_alias = false;


if ($search_uname !="") {
	if (strpos($search_uname,"%")===false)
		$sql_search.=" AND s.username = '".$search_uname."'";
	else
		$sql_search.=" AND s.username like '".$search_uname."'";
}
if (($search_domain!="ANY") && ($search_domain!="")) {
		$sql_search.=" AND s.domain = '".$search_domain."'";
}
if ($search_email!="") {
	if (strpos($search_email,"%")===false)
		$sql_search.=" AND s.email_address = '".$search_email."'";
	else
		$sql_search.=" AND s.email_address like '".$search_email."'";
}

if(!$_SESSION['read_only']){
	$colspan = 5;
}else{
	$colspan = 3;
}

if ($has_acl){
	$colspan++;
}

if ($has_alias){
	$colspan++;
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
<form action="<?=$page_name?>?action=dp_act" method="post">
<table width="350" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="listTitle"></td>
 </tr>
 <tr>
  <td class="searchRecord" align="left">Username</td>
  <td class="searchRecord" width="200"><input type="text" name="lst_uname" 
  value="<?=$search_uname?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr>	
  <td class="searchRecord" align="left">Domain</td>
  <td class="searchRecord" width="200"><?php print_domains("lst_domain",$search_domain,TRUE);?> 
 </tr>
 <tr>
  <td class="searchRecord" align="left">Email</td>
  <td class="searchRecord" width="200"><input type="text" name="lst_email" 
  value="<?=$search_email?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr>
  <td align="right" class="searchRecord"><input type="radio" name="users" 
  value="all_usr" <?php if ($users=="all_usr") echo "checked=\"true\"";?> ></td>
  <td class="searchRecord" align="left">All Users</td>
 </tr>
 <tr>	
  <td align="right" class="searchRecord"><input type="radio" name="users" 
  value="online_usr" $checkedOnline  <?php if ($users=="online_usr") echo "checked=\"true\"";?> ></td>
  <td class="searchRecord" align="left">Online Users</td>
 </tr>
 <tr>	
  <td align="right" class="searchRecord"><input type="radio" name="users" 
  value="offline_usr" <?php if ($users=="offline_usr") echo "checked=\"true\"";?>  ></td>
  <td class="searchRecord" align="let">Offline Users</td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>


 <tr height="10">
  <td colspan="2" class="listTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>
<br>
<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add" value="Add New" class="formButton">') ?>
</form>
<br>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Username</th>
  <th class="listTitle">Email Address</th>
  
  <?
	foreach ( $config->subs_extra as $key => $value ) {
    	echo ('<th class="listTitle">'.$value.'</th>');
		$colspan++;
	}
	
    echo ('<th class="listTitle">Contacts</th>');

	if ($has_alias) {
		echo('<th class="listTitle">Alias</th>');
	}
	
	if ($has_acl) {
		echo('<th class="listTitle">Group</th>');
	}
	
	if(!$_SESSION['read_only']){
		echo('<th class="listTitle">Edit</th>
			<th class="listTitle">Delete</th>');
	}

	echo('</tr>');

if ($users=="all_usr" || $users=="") {
	if ($sql_search!="") $sql_search = "WHERE ".substr($sql_search,4);
	$sql_command="from ".$table." s ".$sql_search;
	$sql_order=" order by s.id asc";
} else if ($users=="online_usr") {
	$sql_command="from ".$table." s, $config->table_location l where s.username=l.username AND s.domain=l.domain ".$sql_search;
	$sql_order=" order by s.id asc";
} else if ($users=="offline_usr") {
	//if ($sql_search!="") $sql_search = substr($sql_search,4);
	$sql_command="from ".$table." s where s.username NOT IN (select s.username from $table s,$config->table_location l where s.username=l.username AND s.domain=l.domain )".$sql_search;
	$sql_order=" order by s.id asc";
}

$data_no = $link->queryOne("select count(*) ".$sql_command);
if(PEAR::isError($data_no)) {
	die('Failed to issue query [select count(*) '.$sql_command.'], error message : ' . $data_no->getMessage());
} 
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
	if ($start_limit==0) $sql_order.=" limit ".$res_no;
	else $sql_order.=" limit ".$res_no." OFFSET " . $start_limit;
	$resultset = $link->queryAll("select * ".$sql_command.$sql_order);
        if(PEAR::isError($resultset)) {
                die('Failed to issue query [select * '.$sql_command.$sql_order.'], error message : ' . $resultset->getMessage());
        }
	$index_row=0;
	$i=0;
	while (count($resultset)>$i)
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if ($has_acl) {
			$group_link = '<a href="../acl_management/acl_management.php?action=dp_act&fromusrmgmt=1&username='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"><img src="images/group.png" border="0"></a>';
			
		}
		if ($has_alias) {
			$alias_link = '<a href="../alias_management/alias_management.php?action=dp_act&fromusrmgmt=1&username='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"><img src="images/alias.gif" border="0"></a>';
		}

		if(!$_SESSION['read_only']){
			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'&table='.$table.'"><img src="../../../images/share/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'&uname='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
		}
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['username'].'@'.$resultset[$i]['domain']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['email_address']?></td>

<?php
	foreach ( $config->subs_extra as $key => $value ) {
    	echo ('<td class="'.$row_style.'">'.$resultset[$i][$key].'</td>');
		$colspan++;
	}
?>

  <td class="<?=$row_style?>" align="center">
    <a href="javascript:;" onclick="show_contacts('<?=$resultset[$i]['username']?>','<?=$resultset[$i]['domain']?>')">
		<img src="images/contacts.png" border="0">
	</a>
  </td>

   <? 

	if ($has_alias){
		echo('<td class="'.$row_style.'" align="center">'.$alias_link.'</td>');
	}

	if ($has_acl){
		echo('<td class="'.$row_style.'" align="center">'.$group_link.'</td>');
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
  <th colspan="<?=$colspan?>" class="listTitle">
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



