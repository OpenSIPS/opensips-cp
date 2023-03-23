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

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>
<form action="<?=$page_name?>?action=search" method="post">
<?php csrfguard_generate();

 $sql_search="";
 $sql_vals=array();

 $search_gwlist=$_SESSION['carriers_search_gwlist'];
 if ($search_gwlist!="") {
                          $id=$search_gwlist;
                          $id=str_replace("*",".*",$id);
                          $id=str_replace("%",".*",$id);
			if ( $config->db_driver == "mysql" ) {
                          $sql_search.=" and gwlist regexp ?";
			  array_push( $sql_vals, "'(^".$id."(=[^,]+)?$)|(^".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?$)'");
			} else if ( $config->db_driver == "pgsql" ) {
                          $sql_search.=" and gwlist ~* ?";
			  array_push( $sql_vals, "'(^".$id."(=[^,]+)?$)|(^".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?$)'");
			}
 }

 $search_description=$_SESSION['carriers_search_description'];
 if ($search_description!="") {
	 $sql_search.=" and description like ?";
	 array_push( $sql_vals, "%".$search_description."%");
 }
?>
<table width="35%" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord"> GW List </td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwlist" value="<?=$_SESSION['carriers_search_gwlist']?>" maxlength="<?=(isset($config->gwlist_size)?$config->gwlist_size:255)?>" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description </td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$_SESSION['carriers_search_description']?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord border-bottom-devider" align="center">
   <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
   <input type="submit" name="show_all" value="Show All" class="searchButton"> &nbsp;&nbsp;&nbsp;
   <?php if (!$_read_only) echo('<input type="submit" name="delete" value="Delete Matching" class="formButton" onClick="return confirmDeleteSearch()">') ?>
  </td>
 </tr>
</table>
</form>

<?php if (!$_SESSION['read_only']) { ?>
<form action="<?=$page_name?>?action=add" method="post">
<?php csrfguard_generate(); ?>
  <input type="submit" name="add_new" value="Add Carrier" class="formButton"> &nbsp;&nbsp;&nbsp;
  <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
</form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Carrier ID</th>
  <th class="listTitle">GW List</th>  
  <th class="listTitle">List Sort</th>
  <th class="listTitle">Use only first</th>
  <th class="listTitle">Description</th>
<?php
$carrier_attrs_colspan = 0;
$carrier_attributes_mode = get_settings_value("carrier_attributes_mode");
$carrier_attributes = get_settings_value("carrier_attributes");
$memory_status = get_settings_value("memory_status");
if ($carrier_attributes_mode != "none") {
	if ($carrier_attributes_mode == "input") {
		echo('<th class="listTitle"><'.(isset($carrier_attributes["display_name"])?$carrier_attributes["display_name"]:"Attributes").'></th>');
		$carrier_attrs_colspan = 1;
	} else {
		foreach ($carrier_attributes as $key => $value) {
			echo('<th class="listTitle">'.(isset($value["display_main"])?$value["display_main"]:$value["display"]).'</th>');
		}
		$carrier_attrs_colspan = count($carrier_attributes);
	}
}
if ($memory_status == "0")
	$carrier_attrs_colspan--;
?>
  <th class="listTitle">DB State</th>
<?php if ($memory_status != "0") { ?>
  <th class="listTitle">Memory State</th>
<?php } ?>
  <th class="listTitle">Details</th>
  <th class="listTitle">Edit</th>
  <th class="listTitle">Delete</th>
 </tr>

<?php
if ($memory_status != "0") {
//get status for all the gws
$carrier_statuses = Array ();

$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

$params = NULL;
if (get_settings_value("routing_partition") && get_settings_value("routing_partition") != "")
	$params['partition_name'] = get_settings_value("routing_partition");

$message=mi_command( "dr_carrier_status", $params, $mi_connectors[0], $errors);

if (!is_null($message)) {
	$message = $message['Carriers'];
	for ($j=0; $j<count($message); $j++)
		$carrier_statuses[$message[$j]['ID']]= trim($message[$j]['Enabled']);
}
}
//end get status

 if ($sql_search=="") {
 	$sql_command="from ".$table." where (1=1) order by id asc ";
 	$sql_command_count = "select count(*) from ".$table." where (1=1)";
 }
 else { 
 	$sql_command="from ".$table." where (1=1) ".$sql_search." order by id asc ";
 	$sql_command_count = "select count(*) from ".$table." where (1=1) ".$sql_search;
 }
 $sql_command = "select * ".$sql_command;

 $stm = $link->prepare($sql_command_count);
 if ($stm===FALSE) {
 	die('Failed to issue query ['.$sql_command_count.'], error message : ' . $link->errorInfo()[2]);
 }
 require("lib/".$page_id.".main.js");
 $stm->execute( $sql_vals );
 $data_no = $stm->fetchColumn(0);
 $colspan = 11 + $carrier_attrs_colspan;

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
  if ($start_limit==0) $sql_command.=" limit ".$res_no;
  else $sql_command.=" limit ".$res_no." OFFSET " . $start_limit;
  $stm = $link->prepare($sql_command);
  if ($stm===FALSE) {
 	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
  }
  $stm->execute( $sql_vals );
  $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
  if ($carrier_attributes_mode != "none")
	  require_once("lib/common.functions.inc.php");
  $index_row=0;
  for ($i=0;count($resultset)>$i;$i++)
  {
   $index_row++;
   if ($index_row%2==1) $row_style="rowOdd";
    else $row_style="rowEven";
   if ($resultset[$i]['gwlist']=="") $gwlist='<center><img src="../../../images/share/inactive.png" alt="No GW List"></center>';
    else $gwlist=parse_gwlist($resultset[$i]['gwlist']);
	//handle flags
	if (is_numeric($resultset[$i]['flags'])) {
		$useonlyfirst = (fmt_binary($resultset[$i]['flags'],4,4)) ? "Yes" : "No" ;
	}
	else{
		$usefirstonly = "error";
		$enabled = "error";
	}
	
    if ($resultset[$i]['description']!="") 
		$description=$resultset[$i]['description'];
    else 
		$description="&nbsp;";

if ($memory_status != "0") {
	//handle status
	$carrier_status = $carrier_statuses[$resultset[$i]['carrierid']];

	   if ($carrier_status=="yes")
	           $status='<a href="'.$page_name.'?action=disablecar&carrierid='.$resultset[$i]['carrierid'].'"><img name="status'.$i.'" src="../../../images/share/active.png" alt="Enabled - Click to disable" onclick="return confirmDisable(\''.$resultset[$i]['carrierid'].'\');"></a>';
      else
	          $status='<a href="'.$page_name.'?action=enablecar&carrierid='.$resultset[$i]['carrierid'].'"><img name="status'.$i.'" src="../../../images/share/inactive.png" alt="Disabled - Click to enable" onclick="return confirmEnable(\''.$resultset[$i]['carrierid'].'\')"></a>';
}

   switch ($resultset[$i]['state']) {
   	case "0" : $state = "Active"; break;
	case "1" : $state = "Inactive"; break;
   }
	//edit and delete links					 
   $details_link='<a href="'.$page_name.'?action=details&carrierid='.$resultset[$i]['carrierid'].'"><img src="../../../images/share/details.png" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&carrierid='.$resultset[$i]['carrierid'].'"><img src="../../../images/share/edit.png" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&carrierid='.$resultset[$i]['carrierid'].'" onclick="return confirmDelete(\''.$resultset[$i]['carrierid'].'\')" ><img src="../../../images/share/delete.png" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['carrierid']?></td>	
  <td class="<?=$row_style?>"><?=$gwlist?></td>
  <td class="<?=$row_style?>"><?=dr_get_name_of_sort_alg($resultset[$i]['sort_alg'])?></td>
  <td class="<?=$row_style?>" align="center"><?=$useonlyfirst?></td>
  <td class="<?=$row_style?>"><?=$description?></td>
<?php
if ($carrier_attributes_mode == "input") {
	if ($resultset[$i]['attrs']!="") $attrs=$resultset[$i]['attrs'];
	else $attrs="&nbsp;";
	echo('<td class="'.$row_style.'">'.$attrs.'</td>');
} else if ($carrier_attributes_mode == "params") {
	$attr_map = dr_get_attrs_map($resultset[$i]['attrs']);
	foreach ($carrier_attributes as $key => $value) {
		$val = dr_get_attrs_val($attr_map, $key, $value);
		if ($value["type"] == "checkbox" && $val == true)
			$val = "<img src='../../../images/share/active.png'>";
		else if (isset($value["value_wrapper_func"])) {
			eval("\$func = ".$value['value_wrapper_func'].';');
			$val = $func($attr_map[$key], $val);
		}
		echo("<td class=\"".$row_style."\">".$val."</td>");
	}
}
?>
  <td class="<?=$row_style?>" align="center"><?=$state?></td>
<?php if ($memory_status != "0") { ?>
  <td class="<?=$row_style?>" align="center"><?=$status?></td>
<?php } ?>
  <td class="<?=$row_style."Img"?>" align="center" rowspan="1"><?=$details_link?></td>
  <td class="<?=$row_style."Img"?>" align="center" rowspan="1"><?=$edit_link?></td>
  <td class="<?=$row_style."Img"?>" align="center" rowspan="1"><?=$delete_link?></td>
 </tr>
<?php
  }
 }
?>
 <tr>
  <th colspan="11">
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
  </td>
 </tr>
</table>
