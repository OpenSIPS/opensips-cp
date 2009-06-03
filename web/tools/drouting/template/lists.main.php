<!--
 * $Id$
 -->

<form action="<?=$page_name?>?action=search" method="post">
<?php
 $sql_search="";
 $search_gwlist=$_SESSION['rules_search_gwlist'];
 if ($search_gwlist!="") {
                          $id=$search_gwlist;
                          $id=str_replace("*",".*",$id);
                          $id=str_replace("%",".*",$id);
			if ( $config->db_driver == "mysql" )
                          $sql_search.=" and gwlist regexp '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'";
			else if ( $config->db_driver == "pgsql" )
                          $sql_search.=" and gwlist ~* '(^".$id."$)|(^".$id."[,;|])|([,;|]".$id."[,;|])|([,;|]".$id."$)'";
                         }

 $search_description=$_SESSION['rules_search_description'];
 if ($search_description!="") $sql_search.=" and description like '%".$search_description."%'";
?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search GW LIST by</td>
 </tr>
 <tr>
  <td class="searchRecord"> GW List: </td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwlist" value="<?=$_SESSION['rules_search_gwlist']?>" maxlength="255" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description: </td>
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
  <td class="dataTitle">GW List</td>  
  <td class="dataTitle">Description</td>
  <td class="dataTitle">Edit</td>
  <td class="dataTitle">Delete</td>
 </tr>
<?php
 if ($sql_search=="") $sql_command="select * from ".$table." where (1=1) order by id asc ";
  else $sql_command="select * from ".$table." where (1=1) ".$sql_search." order by id asc ";
 $resultset=$link->queryAll($sql_command);
 if(PEAR::isError($resultset)) {
         die('Failed to issue query, error message : ' . $resultset->getMessage());
 }
 $data_no=count($resultset);
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
  if ($start_limit==0) $sql_command.=" limit ".$res_no;
  else $sql_command.=" limit ".$res_no." OFFSET " . $start_limit;
  $resultset=$link->queryAll($sql_command);
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
   if ($resultset[$i]['gwlist']=="") $gwlist='<center><img src="images/inactive.gif" alt="No GW List"></center>';
    else $gwlist=parse_gwlist($resultset[$i]['gwlist']);
    if ($resultset[$i]['description']!="") $description=$resultset[$i]['description'];
     //    else $description="&nbsp;";
   $edit_link='<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="images/edit.gif" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'" onclick="return confirmDelete(\''.$resultset[$i]['id'].'\')" ><img src="images/trash.gif" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['id']?></td>	
  <td class="<?=$row_style?>"><?=$gwlist?></td>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style?>" align="center" rowspan="1"><?=$edit_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="1"><?=$delete_link?></td>
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
