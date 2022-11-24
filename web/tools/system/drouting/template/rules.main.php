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
<form action="<?=$page_name?>?action=search" method="post">
<?php csrfguard_generate();
 $rules_attributes_mode = get_settings_value("rules_attributes_mode");
 if($_SESSION['read-only']) {
	$colspan=9;
 } else {
	$colspan=7;
 }
 if ($rules_attributes_mode != "none") {
        $rules_attributes = get_settings_value("rules_attributes");
	$row_colspan = 11;
 } else {
	$row_colspan = 10;
 }
 $sql_search="";
 $sql_vals=array();
 $search_groupid=$_SESSION['rules_search_groupid'];
 if ($search_groupid!="") {
                           $id=$search_groupid;
                           $id=str_replace("*",".*",$id);
                           $id=str_replace("%",".*",$id);
			   if ($config->db_driver == "mysql" ) {
	                           $sql_search.=" and groupid regexp ?";
				   array_push( $sql_vals, "(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)");
			   } else if ($config->db_driver == "pgsql" ) {
				   $sql_search.=" and groupid ~* ?";
				   array_push( $sql_vals, "(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)");
			   }
 }
 $search_prefix=$_SESSION['rules_search_prefix'];
 if ($search_prefix!="") {
                          $pos=strpos($search_prefix,"*");
			  if ($pos===false) {
				  $sql_search.=" and prefix=?";
				  array_push( $sql_vals, $search_prefix);
                          } else {
				  $sql_search.=" and prefix like ?";
				  array_push( $sql_vals, str_replace("*","%",$search_prefix));
			  }
 }
 $search_priority=$_SESSION['rules_search_priority'];
 if ($search_priority!="") {
	 $sql_search.=" and priority=?";
	 array_push( $sql_vals, $search_priority);
 }
 $search_routeid=$_SESSION['rules_search_routeid'];
 if ($search_routeid!="") {
	 $sql_search.=" and routeid=?";
	 array_push( $sql_vals, $search_routeid);
 }
 $search_gwlist=$_SESSION['rules_search_gwlist'];
 if ($search_gwlist!="") {
                          $id=$search_gwlist;
                          $id=str_replace("*",".*",$id);
                          $id=str_replace("%",".*",$id);
                          $sql_search.=" and gwlist regexp ?";
			  array_push( $sql_vals, "'(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'");
                         }
 $search_attributes=$_SESSION['rules_search_attrs'];
 if ($search_attrs!="") {
         $sql_search.=" and attrs like ?";
         array_push( $sql_vals, "%".$search_attrs."%");
 }
 $search_description=$_SESSION['rules_search_description'];
 if ($search_description!="") {
	 $sql_search.=" and description like ?";
	 array_push( $sql_vals, "%".$search_description."%");
 }
?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord">Group ID </td>
  <td class="searchRecord" width="200"><input type="text" name="search_groupid" value="<?=$_SESSION['rules_search_groupid']?>" maxlength="64" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Prefix </td>
  <td class="searchRecord" width="200"><input type="text" name="search_prefix" value="<?=$_SESSION['rules_search_prefix']?>" maxlength="64" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Priority </td>
  <td class="searchRecord" width="200"><input type="text" name="search_priority" value="<?=$_SESSION['rules_search_priority']?>" maxlength="11" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Gateway List </td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwlist" value="<?=$_SESSION['rules_search_gwlist']?>" maxlength="<?=(isset($config->gwlist_size)?$config->gwlist_size:255)?>" class="searchInput"></td>
 </tr>
<?php if ($rules_attributes_mode != "none") { ?>
 <tr>
  <td class="searchRecord"><?=(isset($rules_attributes["display_name"])?$rules_attributes["display_name"]:"Attributes")?> </td>
  <td class="searchRecord" width="200"><input type="text" name="search_attrs" value="<?=$_SESSION['rules_search_attrs']?>" maxlength="128" class="searchInput"></td>
 </tr>
<?php } ?>
 <tr>
  <td class="searchRecord">Description </td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$_SESSION['rules_search_description']?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord border-bottom-devider" align="center">
   <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
   <input type="submit" name="show_all" value="Show All" class="searchButton"> &nbsp;&nbsp;&nbsp;
   <?php if (!$_read_only) echo('<input type="submit" name="delete" value="Delete Matching" class="formButton" onClick="return confirmDeleteSearch()">') ?>
  </td>
 </tr>
</table>
</form>
<?php
$rules_attributes_mode = get_settings_value("rules_attributes_mode");
if ($rules_attributes_mode != "none")
	$rules_attributes = get_settings_value("rules_attributes");
?>

<?php if (!$_SESSION['read_only']) { ?>
<form action="<?=$page_name?>?action=add" method="post">
<?php csrfguard_generate(); ?>
  <input type="submit" name="add_new" value="Add Rule" class="formButton"> &nbsp;&nbsp;&nbsp;
  <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
</form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">ID</th>
  <th class="listTitle">Group ID</th>
  <th class="listTitle">Prefix</th>
  <th class="listTitle">Priority</th>
  <th class="listTitle">GW List</th>  
  <th class="listTitle">List Sort</th>  
<?php if ($rules_attributes_mode != "none") { ?>
  <th class="listTitle"><?=(isset($rules_attributes["display_name"])?$rules_attributes["display_name"]:"Attributes")?></th>
<?php } ?>
  <th class="listTitle">Description</th>
  <th class="listTitle">Details</th>
  <th class="listTitle">Edit</th>
  <th class="listTitle">Delete</th>
 </tr>
<?php
 if ($sql_search=="") {
	$sql_command="select * from ".$table." order by ruleid asc";
	$sql_count="select count(*) from ".$table;
 }
 else {
	$sql_command="select * from ".$table." where (1=1) ".$sql_search." order by ruleid asc";
	$sql_count="select count(*) from ".$table." where (1=1) ".$sql_search;
 }
 $stm= $link->prepare($sql_count);
 if ($stm===FALSE) {
	die('Failed to issue query ['.$sql_count.'], error message : ' . $link->errorInfo()[2]);
 }
 require("lib/".$page_id.".main.js");
 $stm->execute( $sql_vals );
 $data_no = $stm->fetchColumn(0);

 if ($data_no==0) echo('<tr><td colspan="'.$row_colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
  $stm= $link->prepare($sql_command);
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
   if ($resultset[$i]['prefix']!="") $prefix=$resultset[$i]['prefix'];
    else $prefix="&nbsp;";
   if ($resultset[$i]['gwlist']=="") $gwlist='<center><img src="../../../images/share/inactive.png" alt="No GW List"></center>';
   else if ( preg_match('/[#][0-9]+/',$resultset[$i]['gwlist'])) $gwlist=parse_list($resultset[$i]['gwlist']);
    else $gwlist=parse_gwlist($resultset[$i]['gwlist']);
   if ($resultset[$i]['attrs']!="") $attrs=$resultset[$i]['attrs'];
    else $attrs="&nbsp;";
   if (strlen($resultset[$i]['description'])>18) $description=substr($resultset[$i]['description'],0,15)."...";
    else if ($resultset[$i]['description']!="") $description=$resultset[$i]['description'];
         else $description="&nbsp;";
   $details_link='<a href="'.$page_name.'?action=details&id='.$resultset[$i]['ruleid'].'"><img src="../../../images/share/details.png" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['ruleid'].'"><img src="../../../images/share/edit.png" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['ruleid'].'" onclick="return confirmDelete(\''.$resultset[$i]['ruleid'].'\')" ><img src="../../../images/share/delete.png" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>" rowspan="2" align="center"><?=$resultset[$i]['ruleid']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['groupid']?></td>
  <td class="<?=$row_style?>"><?=$prefix?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['priority']?></td>
  <td class="<?=$row_style?>"><?=$gwlist?></td>
  <td class="<?=$row_style?>"><?=dr_get_name_of_sort_alg($resultset[$i]['sort_alg'])?></td>
<?php if ($rules_attributes_mode != "none") { ?>
  <td class="<?=$row_style?>"><?=$attrs?></td>
<?php } ?>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style."Img"?>" align="center" rowspan="2"><?=$details_link?></td>
  <td class="<?=$row_style."Img"?>" align="center" rowspan="2"><?=$edit_link?></td>
  <td class="<?=$row_style."Img"?>" align="center" rowspan="2"><?=$delete_link?></td>
 </tr>
 <tr>
  <td class="<?=$row_style?>" colspan="<?=$colspan?>"><?=parse_timerec_main($resultset[$i]['timerec'])?></td>
 </tr>
<?php
  }
 }
?>
 <tr>
 <th colspan="<?=$row_colspan?>">
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
