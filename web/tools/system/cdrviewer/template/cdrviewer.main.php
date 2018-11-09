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

require("../../../../config/modules.inc.php");
if ( file_exists("../homer") && $config_modules["system"]["modules"]["homer"]["enabled"]==true )
	$tracer = "homer";
else
if ( file_exists("../siptrace") && $config_modules["system"]["modules"]["siptrace"]["enabled"]==true )
	$tracer = "siptrace";
else
	$tracer = "";

$search_start=$_SESSION['cdrviewer_search_start'];
$search_end=$_SESSION['cdrviewer_search_end'];
$search_regexp=$_SESSION['cdrviewer_search_val'];
$cdr_field = $_SESSION['cdrviewer_search_cdr_field'];

$cdr_table = $config->cdr_table;

$sql_search  = " from ".$cdr_table. " where (1=1) ";
$sql_vals = array();

if (($search_start!="")) {
	$sql_search.=" and unix_timestamp( ? )  <= unix_timestamp(time)";
	array_push( $sql_vals, $search_start);
}

if ($search_end!="") {
	$sql_search.=" and unix_timestamp(time) <= unix_timestamp( ? )";
	array_push( $sql_vals, $search_end);
}

if (($cdr_field!="") && ($search_regexp!="")) {
	$sql_search.=' and '.$cdr_field.' like ?' ;
	array_push( $sql_vals, "%".$search_regexp."%");
}

?>

<form action="<?=$page_name?>?action=search" method="post">
<table width="350" class="search-area" cellspacing="2" cellpadding="2" border="0">

 <tr>

 <td class="searchRecord" style="width:100px!important">
  <label><input type="checkbox" name="set_text_regex" value="set" onChange="changeState_cdr_field()" <?php if($search_regexp!="") echo('checked') ?>>CDR field</label></td>
  <td class="searchRecord" > 
 <select name="cdr_field" class="dataSelect" style="width:100px!important" id="select_cdr_field" <?php  if ($search_regexp=="") echo 'disabled="true"' ; ?> >
 <?php if (!isset($cdr_field)) {

 } ?>
 
 <?php for ($i =0 ; $i < count($show_field) ; $i++) { 

 	if ($cdr_field == key($show_field[$i]) ) { 		?>

 	 <option value=<?php echo key($show_field[$i]) ?>  selected > <?php echo $show_field[$i][key($show_field[$i])]?></option>

 	 <?php } else { ?>
 
 <option value=<?php echo key($show_field[$i]) ?> > <?php echo $show_field[$i][key($show_field[$i])]?></option>
           
 <?php } ?>  
 <?php } ?>

 </select>


  <input type="text" name="search_regexp" style="width:310px!important" id="search_regexp" value="<?=$search_regexp?>" maxlength="128" class="searchInput" <?php  if ($search_regexp=="")  echo 'disabled="true"' ; ?> ></td>

  </tr>

 
 
 <td class="searchRecord"><label><input type="checkbox" name="set_start" value="set" onChange="changeState('start')" <?php if($search_start!="") echo('checked') ?>>Start Date</label?</td>

 <td class="searchRecord"><?=print_start_date_time($search_start)?></td>
 
 </tr>
 <tr>
  <td class="searchRecord"><label><input type="checkbox" name="set_end" value="set" onChange="changeState('end')" <?php if($search_end!="") echo('checked') ?>>End Date</label>
  </td>
  <td class="searchRecord"><?=print_end_date_time($search_end)?></td>
 </tr>


 <tr height="10">
  <td colspan="3" class="searchRecord border-bottom-devider" align="center">
	<input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
	<input type="submit" name="show_all" value="Show All" class="searchButton">&nbsp;&nbsp;&nbsp;
	<input type="submit" name="export" value="Export" class="searchButton" onclick="return validate_cdr_export()">
 </td>
 </tr>

 </table>
</form>

<?php
$stm = $link->prepare("select count(*) ".$sql_search);
if ($stm === false) {
	die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
}
$stm->execute( $sql_vals );
$data_no = $stm->fetchColumn(0);

if ($data_no==0) {

	echo('<tr><td colspan="5" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');

	unset($_SESSION['cdrviewer_search_start']);
	unset($_SESSION['cdrviewer_search_end']);
	unset($_SESSION['cdrviewer_search_val']);
	unset($_SESSION['cdrviewer_search_cdr_field']);

}
else
{
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/$config->results_per_page);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	$start_limit=($page-1)*$config->results_per_page;

	$sql = "select * ".$sql_search." order by time desc " ;
	if ($start_limit==0)
		$sql.=" LIMIT ".$config->results_per_page;
	else
		$sql.=" LIMIT ".$config->results_per_page." OFFSET ".$start_limit;

	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( $sql_vals );
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);

	?>



	<center>
	<table class="ttable" width="95%" cellspacing="1" cellpadding="1" border="0" align="right">
     <tr align="center">

 	  <?php for ($i = 0 ; $i < count($show_field)  ; $i++) {  ?>
     
 	  		
 	  	<th class="listTitle" align="center"><?php echo $show_field[$i][key($show_field[$i])]?></th>


 	  <?php } ?>
     	   
 	  	<th class="listTitle" align="center">Details</th>
    </tr>
	
	<?php


	$k = 0 ;
	for($j=0;count($result)>$j;$j++)
	{

		if ( $k%2 == 0 ) $row_style="rowOdd";
		else $row_style="rowEven";
		
		echo '<tr align="center">';

		for ($i = 0 ; $i < count($show_field)  ; $i++) {
			if ($sip_call_id_field_name==key($show_field[$i])) {
				// link the "callid" field to the tracer module
				if ($tracer=="") {
					echo '<td class="'.$row_style.'">'.$result[$j][key($show_field[$i])].'</td>';
				} else {
					echo '<td class="'.$row_style.'">'.'&nbsp;<a href="trace.php?tracer='.$tracer.'&callid='.($result[$j][key($show_field[$i])]).'" class="menuItem" onClick="select_dot()" > <b>'.($result[$j][key($show_field[$i])]).'</b></a>&nbsp;'.'</td>';
				}
			} else {
				echo '<td class="'.$row_style.'">'.$result[$j][key($show_field[$i])].'</td>';
			}
		}
	   
	   $this_cdr_id = $result[$j][$cdr_id_field_name];
	   $details_cdr='<a href="details.php?cdr_id='.($this_cdr_id).'" class="menuItem"> <img src="../../../images/share/details.png" border="0" onClick="window.open(\'details.php?cdr_id='.($this_cdr_id).'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></td></a>&nbsp';	  
	   ?>
	   <td class="<?=$row_style?>Img" align="center"><?php print $details_cdr?></td>

	   </tr>

<?php      		
$k++ ;
	}
}

?>
<tr>
  <th colspan="<?php print(count($show_field)+1)?>">
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

