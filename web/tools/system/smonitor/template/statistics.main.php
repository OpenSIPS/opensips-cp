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



<div id="custom_stat" class="dialog" style="display:none"></div>
<div onclick="closeStatOverlay();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=add_statistic" method="post">
 <?php csrfguard_generate();
 if (!$_SESSION['read_only']) echo('<input type="submit" name="add_new" value="Add New Stat" class="formButton add-new-btn">') ?>
</form>


<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Statistic name</th>
  <th class="listTitle">Details</th>
  <?php
  if(!$_SESSION['read_only']){

  	echo('
	  	<th class="listTitle">Edit</th>
  		<th class="listTitle">Delete</th>');
  }
  ?>
 </tr>
<?php
$sql_command="select count(*) from ocp_extra_stats";
$stm = $link->prepare( $sql_command );
if ($stm===FALSE) {
	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
}
if ($stm->execute( $sql_vals ) === false) {
	die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
}
$data_no = $stm->fetchColumn(0);

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
	$resultset = get_custom_statistics();
	$index_row=0;
	$i=0;
	while (count($resultset)>$i)
	{	
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";
		if(!$_SESSION['read_only']){
			$input = json_decode($resultset[$i]['input']);
			$input_display = "<br>";
			foreach ($input as $key => $value) {
				$input_display .= $key." : ".$value."<br>";
			}
			$details_link = '<a href="javascript:;" onclick="openStatOverlay(\''.$resultset[$i]['class']::get_description().'\',\''.$resultset[$i]['class'].'\',\''.$resultset[$i]['tool'].'\',\''.base64_encode($input_display).'\')"><img src="../../../images/share/details.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&stat_id='.$resultset[$i]['id'].'"><img src="../../../images/share/delete.png" border="0"></a>';
			$edit_link = '<a href="'.$page_name.'?action=edit_statistic&class='.$resultset[$i]['class'].'&stat_id='.$resultset[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
		}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?php print $resultset[$i]['name']?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $details_link?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $edit_link?></td>
  <td class="<?=$row_style?>">&nbsp;<?php print $delete_link?></td>
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