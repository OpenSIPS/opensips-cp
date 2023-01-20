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

<div id="widget_overlay" class="dialog" style="display:none"></div>
<div onclick="closeOverlay();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Widget Name</th>
  <th class="listTitle">Widget Description</th>
  <?php
  if(!$_SESSION['read_only']){
  	echo('<th class="listTitle">Import</th>');
  }
  ?>
 </tr>
<?php
$data_no = get_imports_no();

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
	
    $resultset = get_imports();
    
	require("lib/".$page_id.".main.js");
	$index_row=0;
	$i=0;
	while (sizeof($resultset)>$i)
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";
			$details_link = '<a href="javascript:;" onclick="show_widget(\''.$resultset[$i]['dir'].'\')"><img src="../../../images/share/details.png" border="0"></a>';
            //$details_link = '<a href="'.$page_name.'?action=import_details&widget_dir='.$resultset[$i]['dir'].'"><img src="../../../images/share/details.png" border="0"></a>';
    	if(!$_SESSION['read_only']){	
			$import_link = '<a href="'.$page_name.'?action=import_widget_true&panel_id='.$panel_id.'&widget_dir='.$resultset[$i]['dir'].'"><img src="../../../images/share/edit.png" border="0"></a>';
        }
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?php print $resultset[$i]['info']?></td>
	<td class=<?=$row_styleImg."Img"?> align="center"><?=$details_link?></td>
<?php
   if(!$_SESSION['read_only']){
   	echo('
            <td class="'.$row_style.'Img" align="center">'.$import_link.'</td>');
   }
?>
  </tr>  
<?php

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
