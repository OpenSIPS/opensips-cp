<!--
 * $Id: rules.main.php,v 1.1.1.1 2006-08-30 10:43:09 bogdan Exp $
 -->

<form action="<?=$page_name?>?action=search" method="post">
<?php
 $sql_search="";
 $search_groupid=$_SESSION['rules_search_groupid'];
 if ($search_groupid!="") {
                           $id=$search_groupid;
                           $id=str_replace("*",".*",$id);
                           $id=str_replace("%",".*",$id);
                           $sql_search.=" and groupid regexp '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'";
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
  <td class="searchRecord">Group ID :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_groupid" value="<?=$_SESSION['rules_search_groupid']?>" maxlength="64" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Prefix :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_prefix" value="<?=$_SESSION['rules_search_prefix']?>" maxlength="64" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Priority :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_priority" value="<?=$_SESSION['rules_search_priority']?>" maxlength="11" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Route ID :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_routeid" value="<?=$_SESSION['rules_search_routeid']?>" maxlength="11" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Gateway List :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwlist" value="<?=$_SESSION['rules_search_gwlist']?>" maxlength="255" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$_SESSION['rules_search_description']?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
   <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
   <?php if (!$_read_only) echo('<input type="submit" name="delete" value="Delete Matching" class="searchButton" onClick="return confirmDeleteSearch()">&nbsp;&nbsp;&nbsp;') ?>
   <input type="submit" name="show_all" value="Show All" class="searchButton">
  </td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>

<table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">ID</td>
  <td class="dataTitle">Group ID</td>
  <td class="dataTitle">Prefix</td>
  <td class="dataTitle">Priority</td>
  <td class="dataTitle">Route ID</td>
  <td class="dataTitle">GW List</td>  
  <td class="dataTitle">Description</td>
  <td class="dataTitle">Details</td>
  <td class="dataTitle">Edit</td>
  <td class="dataTitle">Delete</td>
 </tr>
<?php
 db_connect();
 if ($sql_search=="") $sql_command="select * from ".$table." where 1 order by ruleid asc";
  else $sql_command="select * from ".$table." where 1 ".$sql_search." order by ruleid asc";
 $result=mysql_query($sql_command) or die(mysql_error());
 $data_no=mysql_num_rows($result);
 if ($data_no==0) echo('<tr><td colspan="10" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
  $sql_command.=" limit ".$start_limit.", ".$res_no;
  $result=mysql_query($sql_command) or die(mysql_error());
  require("lib/".$page_id.".main.js");
  $index_row=0;
  while ($row=mysql_fetch_array($result))
  {
   $index_row++;
   if ($index_row%2==1) $row_style="rowOdd";
    else $row_style="rowEven";
   if ($row['prefix']!="") $prefix=$row['prefix'];
    else $prefix="&nbsp;";
   if ($row['gwlist']=="") $gwlist='<center><img src="images/inactive.gif" alt="No GW List"></center>';
    else $gwlist=parse_gwlist($row['gwlist']);
   if (strlen($row['description'])>18) $description=substr($row['description'],0,15)."...";
    else if ($row['description']!="") $description=$row['description'];
         else $description="&nbsp;";
   $details_link='<a href="'.$page_name.'?action=details&id='.$row['ruleid'].'"><img src="images/details.gif" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&id='.$row['ruleid'].'"><img src="images/edit.gif" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&id='.$row['ruleid'].'" onclick="return confirmDelete(\''.$row['ruleid'].'\')" ><img src="images/trash.gif" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>" rowspan="2" align="center"><?=$row['ruleid']?></td>
  <td class="<?=$row_style?>"><?=$row['groupid']?></td>
  <td class="<?=$row_style?>"><?=$prefix?></td>
  <td class="<?=$row_style?>"><?=$row['priority']?></td>
  <td class="<?=$row_style?>"><?=$row['routeid']?></td>
  <td class="<?=$row_style?>"><?=$gwlist?></td>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style?>" align="center" rowspan="2"><?=$details_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="2"><?=$edit_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="2"><?=$delete_link?></td>
 </tr>
 <tr>
  <td class="<?=$row_style?>" colspan="6"><?=parse_timerec_main($row['timerec'])?></td>
 </tr>
<?php
  }
 }
?>
 <tr>
  <td colspan="10" class="dataTitle">
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

<form action="<?=$page_name?>?action=add" method="post">
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="formButton">') ?>
</form>