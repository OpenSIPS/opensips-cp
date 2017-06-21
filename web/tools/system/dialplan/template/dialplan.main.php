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


require("lib/".$page_id.".main.js");

$sql_search="";
$search_dpid=$_SESSION['dialplan_id'];
if($search_dpid!="") $sql_search.="and dpid='".$search_dpid."'";

if(!$_SESSION['read_only']){
	$colspan = 11;
}else{
	$colspan = 8;
}

?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=dp_act" method="post">
<table width="350" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="dialplanTitle"></td>
 </tr>
  <tr>
  <td class="searchRecord">Dialplan ID :</td>
  <td class="searchRecord" width="200"><input type="text" name="dialplan_id" 
  value="<?=$search_dpid?>" maxlength="16" class="searchInput"></td>
 </tr>
  <tr height="10">
  <td colspan="2" class="searchRecord" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>

 <tr height="10">
  <td colspan="2" class="dialplanTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>

</table>
</form>

<form action="<?=$page_name?>?action=add&clone=0" method="post">
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New Rule" class="formButton">') ?>
</form>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="dialplanTitle">Dialplan ID</th>
  <th class="dialplanTitle">Rule Priority</th>
  <th class="dialplanTitle">Matching Operator</th>
  <th class="dialplanTitle">Matching Regular Expression</th>
  <th class="dialplanTitle">Matching Flags</th>
  <th class="dialplanTitle">Substitution Regular Expression</th>
  <th class="dialplanTitle">Replacement Expression</th>
  <th class="dialplanTitle">Atrributes</th>
  <?
  if(!$_SESSION['read_only']){

  	echo('<th class="dialplanTitle">Edit</th>
  		<th class="dialplanTitle">Delete</th>
    	<th class="dialplanTitle">Clone</th>');
  }
  ?>
 </tr>
<?php
if ($sql_search=="") $sql_command="select * from ".$table." where (1=1) order by dpid, pr, match_op, match_exp asc";
else $sql_command="select * from ".$table." where (1=1) ".$sql_search." order by dpid, pr, match_op, match_exp asc";
$row = $link->queryAll($sql_command);
 if(PEAR::isError($row)) {
         die('Failed to issue query, error message : ' . $row->getMessage());
 }

$data_no=count($row);
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
	else $sql_command.=" limit ". $res_no . " OFFSET " . $start_limit;
	$row = $link->queryAll($sql_command);
	if(PEAR::isError($row)) {
        	ie('Failed to issue query, error message : ' . $row->getMessage());
	 }
	require("lib/".$page_id.".main.js");
	$index_row=0;
	for ($i=0;count($row)>$i;$i++)

	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if (strlen($row[$i]['attrs'])>23) $attrs=substr($row[$i]['attrs'],0,20)."...";
		else if ($row[$i]['attrs']!="") $attrs=$row[$i]['attrs'];
		else $attrs="&nbsp;";

		if(!$_SESSION['read_only']){

			$edit_link = '<a href="'.$page_name.'?action=edit&clone=0&id='.$row[$i]['id'].'"><img src="../../../images/share/edit.gif" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&clone=0&id='.$row[$i]['id'].'" onclick="return confirmDelete()"><img src="../../../images/share/trash.gif" border="0"></a>';
			$clone_link='<a href="'.$page_name.'?action=clone&clone=1&id='.$row[$i]['id'].'"><img src="../../../images/share/clone.gif" border="0"></a>';
		}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['dpid']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['pr']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['match_op']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['match_exp']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['match_flags']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['subst_exp']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$row[$i]['repl_exp']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$attrs?></td>
   <? 
   if(!$_SESSION['read_only']){
   	echo('<td class="'.$row_style.'" align="center">'.$edit_link.'</td>
			  <td class="'.$row_style.'" align="center">'.$delete_link.'</td>
			  <td class="'.$row_style.'" align="center">'.$clone_link.'</td>');
   }
	?>  
  </tr>  
<?php
	}
}
?>
 <tr>
  <th colspan="<?=$colspan?>" class="dialplanTitle">
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


