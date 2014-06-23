<!--
 /*
 * $Id: alias_management.main.php 210 2010-03-08 18:09:33Z bogdan_iancu $
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
<form action="<?=$page_name?>?action=dp_act" method="post">

<?php
$sql_search="";
$search_ausername=$_SESSION['acl_username'];
$search_adomain=$_SESSION['acl_domain'];
$search_agrp=$_SESSION['acl_grp'];
if($search_ausername !="") $sql_search.=" and username like '%" . $search_ausername."%'";
else $sql_search.=" and username like '%'";
if(($search_adomain == 'ANY') || ($search_adomain == "")) $sql_search.=" and domain like '%'";
else $sql_search.=" and domain='".$search_adomain."'";
if(($search_agrp =='ANY') || ($search_agrp =="")) $sql_search.=" and grp like '%'";
else $sql_search.=" and grp='".$search_agrp."'"; 

//require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
        $colspan = 8;
}else{
        $colspan = 6;
}

?>

<table width="50%" cellspacing="2" cellpadding="2" border="0">
<tr align="center">
<td colspan="2" height="10" class="aclTitle"></td>
</tr>
<tr>
<td class="searchRecord" align="left">Username</td>
<td class="searchRecord" width="200"><input type="text" name="acl_username"
value="<?=$search_ausername?>" maxlength="16" class="searchInput"></td>
<tr>
<td class="searchRecord" align="left">Domain</td>
<td class="searchRecord" width="200"> <?php print_domains("acl_domain",$search_adomain)?></td>
</tr>
<tr>
<td class="searchRecord" align="left">Group</td>
<td class="searchRecord" width="200"> <?php print_groups("acl_grp",$search_agrp)?></td>
</tr>
</tr>
<tr height="10">
<td colspan="2" class="searchRecord" align="center">
<input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
<input type="submit" name="show_all" value="Show All" class="searchButton"></td>
</tr>
<?
if(!$_SESSION['read_only']){
echo('<tr height="10">
<td colspan="2" class="searchRecord" align="center">
<input type="submit" class="formButton" name="delete" value="Delete ACL" onclick="return confirmDeleteACL()">
</td>
</tr>');

}
?>

 <tr height="10">
  <td colspan="2" class="aclTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>

<br>
<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add" value="Add New" class="formButton">') ?>
</form>
<br>

<table width="95%" cellspacing="2" cellpadding="2" border="0">
<tr align="center">
<td class="aclTitle">ID</td>
<td class="aclTitle">Username</td>
<td class="aclTitle">Domain</td>
<td class="aclTitle">Group</td>
<?
if(!$_SESSION['read_only']){

echo('<td class="aclTitle">Edit</td>
	<td class="aclTitle">Delete</td>');
}
?>
</tr>

<?php
if (($search_agrp=='ANY') || ($search_agrp=='')) {
		
		if ($sql_search=="") $sql_command="select * from ".$table." where (1=1) order by id asc";
		else $sql_command="select * from ".$table." where (1=1) ".$sql_search." order by id asc";
		$resultset = $link->queryAll($sql_command);
		if(PEAR::isError($resultset)) {
		        die('Failed to issue query, error message : ' . $resultset->getMessage());
		}	
$data_no=count($resultset);
if ($data_no==0)   echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>'); 
else { 

        $res = $config->results_per_page;
        $page=$_SESSION[$current_page];
        $page_no=ceil($data_no/$res);
        if ($page>$page_no) {
                $page=$page_no;
                $_SESSION[$current_page]=$page;
        }
        $start_limit=($page-1)*$res;
        if ($start_limit==0) $sql_command.=" limit ".$res;
        else $sql_command.=" limit ".$res." OFFSET " . $start_limit;
        $resultset = $link->queryAll($sql_command);
        if(PEAR::isError($resultset)) {
                die('Failed to issue query, error message : ' . $resultset->getMessage());
        }
        //require("lib/".$page_id.".main.js");
        $index_row=0;
        $i=0;
        while (count($resultset)>$i)
        {
                $index_row++;
                if ($index_row%2==1) $row_style="rowOdd";
                else $row_style="rowEven";

                if(!$_SESSION['read_only']){
                        $edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'&table='.$table.'"><img src="images/edit.png" border="0"></a>';
                        $delete_link='<a href="'.$page_name.'?action=delete&table='.$table.'&id='.$resultset[$i]['id'].'"onclick="return confirmDelete()"><img src="images/delete.png" border="0"></a>';

}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['id']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['username']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['domain']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['grp']?></td>
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
  <td colspan="<?=$colspan?>" class="aclTitle">
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
<?php 
} else {

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
        $res=$config->results_per_page;
        $page=$_SESSION[$current_page];
        $page_no=ceil($data_no/$res);
        if ($page>$page_no) {
                $page=$page_no;
                $_SESSION[$current_page]=$page;
        }
        $start_limit=($page-1)*$res;
        //$sql_command.=" limit ".$start_limit.", ".$res;
        if ($start_limit==0) $sql_command.=" limit ".$res;
        else $sql_command.=" limit ".$res." OFFSET " . $start_limit;
        $resultset = $link->queryAll($sql_command);
        if(PEAR::isError($resultset)) {
                die('Failed to issue query, error message : ' . $resultset->getMessage());
        }
        //require("lib/".$page_id.".main.js");
        $index_row=0;
        $i=0;
        while (count($resultset)>$i)
        {
                $index_row++;
                if ($index_row%2==1) $row_style="rowOdd";
                else $row_style="rowEven";

                if(!$_SESSION['read_only']){

                        $edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'&table='.$table.'"><img src="images/edit.gif" border="0"></a>';
                        $delete_link='<a href="'.$page_name.'?action=delete&table='.$table.'&id='.$resultset[$i]['id'].'"onclick="return confirmDelete()"><img src="images/trash.gif" border="0"></a>';

?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['id']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['username']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['domain']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['grp']?></td>
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
}
?>
 <tr>
  <td colspan="<?=$colspan?>" class="aclTitle">
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
<?php
}
?>
  </td>
 </tr>
</table>

<br>

