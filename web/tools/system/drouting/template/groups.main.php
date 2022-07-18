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
 $sql_vals=array();

 $search_username=$_SESSION['groups_search_username'];
 if ($search_username!="") {
                            $pos=strpos($search_username,"*");
			    if ($pos===false) {
				    $sql_search.=" and username=?";
				    array_push( $sql_vals, $search_username);
			    } else {
				    $sql_search.=" and username like ?";
				    array_push( $sql_vals, str_replace("*","%",$search_username));
			    }
 }
 $search_domain=$_SESSION['groups_search_domain'];
 if ($search_domain!="") {
                          $pos=strpos($search_domain,"*");
			  if ($pos===false) {
				  $sql_search.=" and domain=?";
				  array_push( $sql_vals, $search_domain);
			  } else {
				  $sql_search.=" and domain like ?";
				  array_push( $sql_vals, str_replace("*","%",$search_domain));
                          }
 }
 $search_groupid=$_SESSION['groups_search_groupid'];
 if ($search_groupid!="") {
	 $sql_search.=" and groupid=?";
	 array_push( $sql_vals, $search_groupid);
 }
 $search_description=$_SESSION['groups_search_description'];
 if ($search_description!="") {
	 $sql_search.=" and description like ?";
	 array_push( $sql_vals, str_replace("*","%",$search_description));
 }
?>

<form action="<?=$page_name?>?action=search" method="post">
<?php csrfguard_generate(); ?>
<table width="35%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord">Username </td>
  <td class="searchRecord" width="200"><input type="text" name="search_username" value="<?=$search_username?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Domain </td>
  <td class="searchRecord" width="200"><input type="text" name="search_domain" value="<?=$search_domain?>" maxlength="64" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Group ID </td>
  <td class="searchRecord" width="200"><input type="text" name="search_groupid" value="<?=$search_groupid?>" maxlength="11" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description </td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$search_description?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord border-bottom-devider" align="center"><input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>
</table>
</form>

<?php if (!$_SESSION['read_only']) { ?>
<form action="<?=$page_name?>?action=add" method="post">
<?php csrfguard_generate(); ?>
  <input type="submit" name="add_new" value="Add Record" class="formButton"> &nbsp;&nbsp;&nbsp;
</form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Username</th>
  <th class="listTitle">Domain</th>
  <th class="listTitle">Group ID</th>
  <th class="listTitle">Description</th>
  <th class="listTitle">Details</th>
  <th class="listTitle">Edit</th>
  <th class="listTitle">Delete</th>
 </tr>
<?php
 $sql_command="select count(*) from ".$table." where (1=1) ".$sql_search;
 $stm = $link->prepare($sql_command);
 if ($stm===FALSE) {
 	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
 }
 require("lib/".$page_id.".main.js");
 $stm->execute( $sql_vals );
 $data_no = $stm->fetchColumn(0);
 if ($data_no==0) echo('<tr><td colspan="7" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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

  $sql_command="select * from ".$table." where (1=1) ".$sql_search." order by username, domain asc limit ".$res_no;
  if ($start_limit!=0) 
  	$sql_command.=" OFFSET " . $start_limit;
  $stm = $link->prepare($sql_command);
  if ($stm===FALSE) {
 	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
  }
  $stm->execute( $sql_vals );
  $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
  $index_row=0;
  for ($i=0;count($resultset)>$i;$i++)
  {
   $index_row++;
   if ($index_row%2==1) $row_style="rowOdd";
    else $row_style="rowEven";
   if (strlen($resultset[$i]['description'])>23) $description=substr($resultset[$i]['description'],0,20)."...";
    else if ($resultset[$i]['description']!="") $description=$resultset[$i]['description'];
         else $description="&nbsp;";
   $record_id=$resultset[$i]['username']."@".$resultset[$i]['domain'];
   $details_link='<a href="'.$page_name.'?action=details&id='.$record_id.'"><img src="../../../images/share/details.png" border="0"></a>';
   if(!$_SESSION['read_only']){
	   $edit_link='<a href="'.$page_name.'?action=edit&id='.$record_id.'"><img src="../../../images/share/edit.png" border="0"></a>';
	   $delete_link='<a href="'.$page_name.'?action=delete&id='.$record_id.'" onclick="return confirmDelete(\''.$record_id.'\')"><img src="../../../images/share/delete.png" border="0"></a>';
   }

   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
   
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['username']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['domain']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['groupid']?></td>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style."Img"?>" align="center"><?=$details_link?></td>
  <td class="<?=$row_style."Img"?>" align="center"><?=$edit_link?></td>
  <td class="<?=$row_style."Img"?>" align="center"><?=$delete_link?></td>
 </tr>  
<?php
  }
 }
?>
 <tr>
  <th colspan="7" >
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

