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
if ( file_exists("../siptrace") && $config_modules["system"]["modules"]["siptrce"]["enabled"]==true )
	$tracer = "siptrace";
else
	$tracer = "";
?>


<form action="<?=$page_name?>?action=search" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
    <td align="right"  style="color:#0969b5">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print "System / CDRViewer / ".$_SESSION['permission'];?></b>
    </td>
</tr>
</table>
<hr width="100%" color="#000000">
<?php
$search_regexp=$_SESSION['cdrviewer_search_val'];
$cdr_field = $_SESSION['cdrviewer_search_cdr_field'];


if (($cdr_field!="") && ($search_regexp!="")) $sql_search.=" and ".$cdr_field.' like "%'.$search_regexp.'%"' ;


$search_start=$_SESSION['cdrviewer_search_start'];
$search_end=$_SESSION['cdrviewer_search_end'];

$cdr_table = $config->cdr_table;
$sql  = "select count(*) from ".$cdr_table. " where (1=1) ";

if (($search_start!="")) {

	$sql.=" and unix_timestamp('".$search_start ."')  <= unix_timestamp(time)";

}

if ($search_end!="") {

	$sql.=" and unix_timestamp(time) <= unix_timestamp('" . $search_end ."')";

}



if 	((($sql_search!=""))) {

	$sql .=$sql_search ;

}


?>

<table width="470" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search CDRs by: </td>
 </tr>

 

 <tr>

 <td class="searchRecord"><input type="checkbox" name="set_text_regex" value="set" onChange="changeState_cdr_field()" <?php if($search_regexp!="") echo('checked') ?>>CDR field :</td>
  <td class="searchRecord" > 
 <select name="cdr_field" id="select_cdr_field" <?  if ($search_regexp=="") echo 'disabled="true"' ; ?> >
 <? if (!isset($cdr_field)) {

 } ?>
 
 <? for ($i =0 ; $i < count($show_field) ; $i++) { 

 	if ($cdr_field == key($show_field[$i]) ) { 		?>

 	 <option value=<?echo key($show_field[$i]) ?>  selected > <?echo $show_field[$i][key($show_field[$i])]?></option>

 	 <? } else { ?>
 
 <option value=<?echo key($show_field[$i]) ?> > <?echo $show_field[$i][key($show_field[$i])]?></option>
           
 <? } ?>  
 <? } ?>

 </select>


  <input type="text" name="search_regexp" id="search_regexp" value="<?=$search_regexp?>" maxlength="128" class="searchInput" <?  if ($search_regexp=="")  echo 'disabled="true"' ; ?> ></td>

  </tr>

 
 
 <td class="searchRecord"><input type="checkbox" name="set_start" value="set" onChange="changeState('start')" <?php if($search_start!="") echo('checked') ?>>Start Date :</td>

 <td class="searchRecord"><?=print_start_date_time($search_start)?></td>
 
 </tr>
 <tr>
  <td class="searchRecord"><input type="checkbox" name="set_end" value="set" onChange="changeState('end')" <?php if($search_end!="") echo('checked') ?>>End Date :
  </td>
  <td class="searchRecord"><?=print_end_date_time($search_end)?></td>
 </tr>


 <tr height="10">
  <td colspan="3" class="searchRecord" align="center"><input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" name="show_all" value="Show All" class="searchButton">&nbsp;&nbsp;&nbsp;<input type="submit" name="export" value="Export" class="searchButton" onclick="return validate_cdr_export()"></td>
 </tr>
 <tr height="10">
   <td colspan="2" class="searchTitle"></td>
 </tr>

 
 </table>
</form><br>

<?
$data_no=$link->queryOne($sql);
if(PEAR::isError($data_no)) {
	die('Failed to issue query, error message : ' . $data_no->getMessage());
}

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

	$sql = "select * " ;

	$sql.=" from ".$cdr_table . " where (1=1) ";

	if (($search_start!="") && ($search_end!="")) {

		$sql.=" and unix_timestamp('".$search_start ."')  <= unix_timestamp(time) and  ";
		$sql.="unix_timestamp(time) <= unix_timestamp('" . $search_end ."')"   ;

		$sql .=$sql_search ;


	}

	if (($sql_search!="")) {

		$sql .=$sql_search ;

	}


	$sql .= " order by time desc " ;
	$sql.=" LIMIT ".$config->results_per_page." OFFSET ".$start_limit;

	$result=$link->queryAll($sql);
	if(PEAR::isError($result)) {
		die('Failed to issue query, error message : ' . $result->getMessage());
	}

	?>



	<center>
	<table class="ttable" width="95%" cellspacing="1" cellpadding="1" border="0" align="right">
     <tr align="center">

 	  <? for ($i = 0 ; $i < count($show_field)  ; $i++) {  ?>
     
 	  		
 	  	<th class="Title" align="center"><?echo $show_field[$i][key($show_field[$i])]?></th>


 	  <? } ?>
     	   
 	  	<th class="Title" align="center">Details</th>
    </tr>
	
	<?


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
	   $details_cdr='<a href="details.php?cdr_id='.($this_cdr_id).'" class="menuItem"> <img src="../../../images/share/details.gif" border="0" onClick="window.open(\'details.php?cdr_id='.($this_cdr_id).'\',\'info\',\'scrollbars=1,width=550,height=300\');return false;"></td></a>&nbsp';	  
	   ?>
	   <td class="rowOdd" align="center"><?print $details_cdr?></td>

	   </tr>

<?      		
$k++ ;
	}
}
$link->disconnect();

?>
<tr>
  <th colspan="<?print(count($show_field)+1)?>" class="Title">
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

