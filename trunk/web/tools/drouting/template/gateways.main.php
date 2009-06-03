<!--
 * $Id$
-->

<form action="<?=$page_name?>?action=search" method="post">
<?php
 $sql_search="";
 $search_type=$_SESSION['gateways_search_type'];
 if ($search_type!="") $sql_search.=" and type='".$search_type."'";
 $search_address=$_SESSION['gateways_search_address'];
 if ($search_address!="") {
                           $pos=strpos($search_address,"*");
                           if ($pos===false) $sql_search.=" and address='".$search_address."'";
                            else $sql_search.=" and address like '".str_replace("*","%",$search_address)."'";
                          }
 $search_pri_prefix=$_SESSION['gateways_search_pri_prefix'];
 if ($search_pri_prefix!="") $sql_search.=" and pri_prefix='".$search_pri_prefix."'";
 $search_description=$_SESSION['gateways_search_description'];
 if ($search_description!="") $sql_search.=" and description like '%".$search_description."%'";
?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search Gateways by</td>
 </tr>
 <tr>
  <td class="searchRecord">Type :</td>
  <td class="searchRecord" width="200"><?=get_types("search_type", $search_type)?></td>
 </tr>
 <tr>
  <td class="searchRecord">Address :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_address" value="<?=$search_address?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">PRI Prefix :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_pri_prefix" value="<?=$search_pri_prefix?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$search_description?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord" align="center"><input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" name="show_all" value="Show All" class="searchButton"></td>
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
  <td class="dataTitle">Type</td>
  <td class="dataTitle">Address</td>
  <td class="dataTitle">Strip</td>
  <td class="dataTitle">PRI Prefix</td>
  <td class="dataTitle">Description</td>
  <td class="dataTitle">Status</td>
  <td class="dataTitle">Details</td>
  <td class="dataTitle">Edit</td>
  <td class="dataTitle">Delete</td>
 </tr>
<?php
 if ($sql_search=="") $sql_command="select * from ".$table." where (1=1) order by gwid asc";
  else $sql_command="select * from ".$table." where (1=1) ".$sql_search." order by gwid asc";
  $resultset = $link->queryAll($sql_command);
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
   if (strlen($resultset[$i]['description'])>23) $description=substr($resultset[$i]['description'],0,20)."...";
    else if ($resultset[$i]['description']!="") $description=$resultset[$i]['description'];
         else $description="&nbsp;";
   $data_rows=get_status($resultset[$i]['gwid']);
   if ($data_rows==0) $status='<img src="images/inactive.gif" alt="inactive">';
    else $status='<img src="images/active.gif" alt="active"> / '.$data_rows;
   if ($resultset[$i]['pri_prefix']!="") $pri_prefix=$resultset[$i]['pri_prefix'];
    else $pri_prefix="&nbsp;";
   $details_link='<a href="'.$page_name.'?action=details&id='.$resultset[$i]['gwid'].'"><img src="images/details.gif" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['gwid'].'"><img src="images/edit.gif" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['gwid'].'" onclick="return confirmDelete(\''.$resultset[$i]['gwid'].'\')"><img src="images/trash.gif" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['gwid']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['type']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['address']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['strip']?></td>
  <td class="<?=$row_style?>"><?=$pri_prefix?> </td>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style?>" align="center"><?=$status?></td>
  <td class="<?=$row_style?>" align="center"><?=$details_link?></td>
  <td class="<?=$row_style?>" align="center"><?=$edit_link?></td>
  <td class="<?=$row_style?>" align="center"><?=$delete_link?></td>
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
