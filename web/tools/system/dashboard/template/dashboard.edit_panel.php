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

if(!$_SESSION['read_only']){
	$colspan = 5;
}else{
	$colspan = 3;
}
?>
<form action="<?=$page_name?>?action=add_blank_panel" method="post">
<?php csrfguard_generate(); ?>
 <?php if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add Blank Panel" class="formButton add-new-btn">') ?>
</form>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Panel name</th>
  <?php
  if(!$_SESSION['read_only']){

  	echo('<th class="listTitle">Rename</th>
        <th class="listTitle">Clone</th>
		<th class="listTitle">Delete</th>
		<th class="listTitle">Move</th>');
  }
  ?>
 </tr>
<?php
$sql_command="select count(*) from ".$table."";
$stm = $link->prepare( $sql_command );
if ($stm===FALSE) {
	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
}
$stm->execute( $sql_vals );
$data_no = $stm->fetchColumn(0);

if ($data_no==0) echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
	$sql_command="select `name`, id, `order` from ".$table."  order by `order` asc limit ".$res_no;
	if ($start_limit!=0)
		$sql_command.=" OFFSET " . $start_limit;
	$stm = $link->prepare( $sql_command );
	if ($stm===FALSE)
	       die('Failed to issue query ['.$sql_command.'], error message : ' . print_r($link->errorInfo(), true));
	$stm->execute();
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	require("lib/".$page_id.".main.js");
	$index_row=0;
	$i=0;
	$move_up_id = -1;
	$move_down_id = -1;
	while (count($resultset)>$i)
	{	
		if ($i == count($resultset) - 1) $move_down_id = -1;
		else $move_down_id = $resultset[$i + 1]['order'];  
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if(!$_SESSION['read_only']){
			$edit_link = '<a href="'.$page_name.'?action=change_panel_name&panel_id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
			$clone_link = '<a href="'.$page_name.'?action=clone_panel&panel_id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&panel_id='.$resultset[$i]['id'].'"onclick="return confirmDelete(\'panel\')"><img src="../../../images/share/delete.png" border="0"></a>';
			$move_link='<table style="width:40px; table-layout:fixed; border-spacing: 0px; "><tr><td style="border-left-width: 0px; border-bottom-width: 0px; border-top-width: 0px;">';
			if ($move_up_id != -1)
				$move_link .= '<a style="position:relative; right:27px;" href="'.$page_name.'?action=move_panels&order_id1='.$resultset[$i]['order'].'&order_id2='.$move_up_id.'"><img src="../../../images/share/up.png" border="0"></a>';
			$move_link .= '</td><td style="border-left-width: 0px; border-bottom-width: 0px; border-top-width: 0px;">';
			if ($move_down_id != -1)
				$move_link .= '<a style="position:relative; right:15px;" href="'.$page_name.'?action=move_panels&order_id1='.$resultset[$i]['order'].'&order_id2='.$move_down_id.'"><img src="../../../images/share/down.png" border="0"></a>';
			$move_link .= '</td></tr></table>';
		}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?php print $resultset[$i]['name']?></td>
<?php
   if(!$_SESSION['read_only']){
   	echo('<td class="'.$row_style.'Img" align="center">'.$edit_link.'</td>
            <td class="'.$row_style.'Img" align="center">'.$clone_link.'</td>
			<td class="'.$row_style.'Img" align="center">'.$delete_link.'</td>
			<td class="'.$row_style.'Img" align="center">'.$move_link.'</td>');
   }
?>
  </tr>  
<?php

	$move_up_id = $resultset[$i]['order'];
	$i++;
	}
}
?>
 <tr>
  <th colspan="<?=$colspan?>">
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
       	else echo('<a href="'.$page_name.'?action=edit_panel&page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
       	// next block
       	if ($end_page!=$page_no) echo('&nbsp;<a href="'.$page_name.'?action=edit_panel&page='.($start_page+$max_pages).'" class="menuItem"><b>&gt;&gt;</b></a>&nbsp;');
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
