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
<?php
 if($_SESSION['read-only']) {
	$colspan=9;
 } else {
	$colspan=7;
 }
 $sql_search="";
 $search_groupid=$_SESSION['rules_search_groupid'];
 if ($search_groupid!="") {
                           $id=$search_groupid;
                           $id=str_replace("*",".*",$id);
                           $id=str_replace("%",".*",$id);
			   if ($config->db_driver == "mysql" )
	                           $sql_search.=" and groupid regexp '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'";
			   else if ($config->db_driver == "pgsql" )
	                           $sql_search.=" and groupid ~* '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'";
                          }
 $search_prefix=$_SESSION['rules_search_prefix'];
 if ($search_prefix!="") {
                          $pos=strpos($search_prefix,"*");
                          if ($pos===false) $sql_search.=" and prefix='".$search_prefix."'";
                           else $sql_search.=" and prefix like '".str_replace("*","%",$search_prefix)."'";
                          }
 $search_priority=$_SESSION['rules_search_priority'];
 if ($search_priority!="") $sql_search.=" and priority='".$search_priority."'";
 $search_routeid=$_SESSION['rules_search_routeid'];
 if ($search_routeid!="") $sql_search.=" and routeid='".$search_routeid."'";
 $search_gwlist=$_SESSION['rules_search_gwlist'];
 if ($search_gwlist!="") {
                          $id=$search_gwlist;
                          $id=str_replace("*",".*",$id);
                          $id=str_replace("%",".*",$id);
                          $sql_search.=" and gwlist regexp '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'";
                         }
 $search_description=$_SESSION['rules_search_description'];
 if ($search_description!="") $sql_search.=" and description like '%".$search_description."%'";
?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search Rules by</td>
 </tr>
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
  <td class="searchRecord">Route ID </td>
  <td class="searchRecord" width="200"><input type="text" name="search_routeid" value="<?=$_SESSION['rules_search_routeid']?>" maxlength="11" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Gateway List </td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwlist" value="<?=$_SESSION['rules_search_gwlist']?>" maxlength="255" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Attributes </td>
  <td class="searchRecord" width="200"><input type="text" name="search_attrs" value="<?=$_SESSION['rules_search_attrs']?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description </td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$_SESSION['rules_search_description']?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
   <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
   <input type="submit" name="show_all" value="Show All" class="searchButton">
  </td>
 </tr>
  <td colspan="2" class="searchRecord" align="center">
   <?php if (!$_read_only) echo('<input type="submit" name="delete" value="Delete Matching" class="searchButton" onClick="return confirmDeleteSearch()">&nbsp;&nbsp;&nbsp;') ?>
  </td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="dataTitle">ID</th>
  <th class="dataTitle">Group ID</th>
  <th class="dataTitle">Prefix</th>
  <th class="dataTitle">Priority</th>
  <th class="dataTitle">Route ID</th>
  <th class="dataTitle">GW List</th>  
  <th class="dataTitle">Attributes</th>
  <th class="dataTitle">Description</th>
  <th class="dataTitle">Details</th>
  <th class="dataTitle">Edit</th>
  <th class="dataTitle">Delete</th>
 </tr>
<?php
 if ($sql_search=="") {
	$sql_command="select * from ".$table." where (1=1) order by ruleid asc";
	$sql_count="select count(*) from ".$table." where (1=1)";
 }
 else {
	$sql_command="select * from ".$table." where (1=1) ".$sql_search." order by ruleid asc";
	$sql_count="select count(*) from ".$table." where (1=1) ".$sql_search;
 }
 $data_no = $link->queryOne($sql_count);
 
 if ($data_no==0) echo('<tr><td colspan="11" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
   if ($resultset[$i]['routeid']!="") $routeid=$resultset[$i]['routeid'];
    else $routeid="&nbsp;";
   if (strlen($resultset[$i]['description'])>18) $description=substr($resultset[$i]['description'],0,15)."...";
    else if ($resultset[$i]['description']!="") $description=$resultset[$i]['description'];
         else $description="&nbsp;";
   $details_link='<a href="'.$page_name.'?action=details&id='.$resultset[$i]['ruleid'].'"><img src="../../../images/share/details.gif" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['ruleid'].'"><img src="../../../images/share/edit.gif" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['ruleid'].'" onclick="return confirmDelete(\''.$resultset[$i]['ruleid'].'\')" ><img src="../../../images/share/trash.gif" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>" rowspan="2" align="center"><?=$resultset[$i]['ruleid']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['groupid']?></td>
  <td class="<?=$row_style?>"><?=$prefix?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['priority']?></td>
  <td class="<?=$row_style?>"><?=$routeid?></td>
  <td class="<?=$row_style?>"><?=$gwlist?></td>
  <td class="<?=$row_style?>"><?=$attrs?></td>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style?>" align="center" rowspan="2"><?=$details_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="2"><?=$edit_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="2"><?=$delete_link?></td>
 </tr>
 <tr>
  <td class="<?=$row_style?>" colspan="<?php print $colspan;?>"><?=parse_timerec_main($resultset[$i]['timerec'])?></td>
 </tr>
<?php
  }
 }
?>
 <tr>
  <th colspan="11" class="dataTitle">
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

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>
