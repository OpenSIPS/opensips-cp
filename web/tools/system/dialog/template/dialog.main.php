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

<div align="right">
	<form name="refreshform" action="<?=$page_name?>?action=refresh" method="post">
		<input type="submit" name="refresh" value="Refresh Dialog List" class="ButtonLink">
	</form>
</div>
<br>

<?php

include "dialog_table.inc.php";

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

// get status from the first one only
$comm = "dlg_list ".$start_limit." ".$config->results_per_page;
$message=mi_command($comm , $mi_connectors[0], $errors , $status);

$message = json_decode($message,true);
$data_no = $message['dlg_counter'][0]['value'];
$message = $message['dlg_counter'];


echo '<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">';
echo '<tr align="center">';
echo '<th class="listTitle">Call ID</th>';
echo '<th class="listTitle">From URI</th>';
echo '<th class="listTitle">To URI</th>';
echo '<th class="listTitle">Start Time</th>';
echo '<th class="listTitle">Timeout Time</th>';
echo '<th class="listTitle">State</th>';
if(!$_SESSION['read_only'])
	echo('<th class="listTitle">Stop Call</th>');
echo '</tr>';

if ($data_no==0) {
	echo('<tr><td colspan="7" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
}
else {
	// here goes the paging stuff
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/$config->results_per_page);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	
	$start_limit=($page-1)*$config->results_per_page;

	display_dialog_table($message);
}

?>


<tr>
<th colspan="7">
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
    </th>
 </tr>
</table>

<br>

