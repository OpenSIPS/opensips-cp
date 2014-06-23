<!--
 *
 * $Id: lists.main.php 287 2011-10-17 09:41:35Z untiptun $
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
 *
 -->

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>
<form action="<?=$page_name?>?action=search" method="post">
<?php

 $sql_search="";
 $search_gwlist=$_SESSION['carriers_search_gwlist'];
 if ($search_gwlist!="") {
                          $id=$search_gwlist;
                          $id=str_replace("*",".*",$id);
                          $id=str_replace("%",".*",$id);
			if ( $config->db_driver == "mysql" )
                          $sql_search.=" and gwlist regexp '(^".$id."(=[^,]+)?$)|(^".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?$)'";
			else if ( $config->db_driver == "pgsql" )
                          $sql_search.=" and gwlist ~* '(^".$id."(=[^,]+)?$)|(^".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?,)|(,".$id."(=[^,]+)?$)'";
                         }

 $search_description=$_SESSION['carriers_search_description'];
 if ($search_description!="") $sql_search.=" and description like '%".$search_description."%'";
?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search carriers by</td>
 </tr>
 <tr>
  <td class="searchRecord"> GW List: </td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwlist" value="<?=$_SESSION['carriers_search_gwlist']?>" maxlength="255" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Description: </td>
  <td class="searchRecord" width="200"><input type="text" name="search_description" value="<?=$_SESSION['carriers_search_description']?>" maxlength="128" class="searchInput"></td>
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
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="formButton">');?>
</form>
<?php 
?>
<table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">ID</td>
  <td class="dataTitle">GW ID</td>
  <td class="dataTitle">GW List</td>  
  <td class="dataTitle">Use weights</td>
  <td class="dataTitle">Use only first</td>
  <td class="dataTitle">Description</td>
  <td class="dataTitle">Attributes</td>
  <td class="dataTitle">DB State</td>
  <td class="dataTitle">Memory State</td>
  <td class="dataTitle">Details</td>
  <td class="dataTitle">Edit</td>
  <td class="dataTitle">Delete</td>
 </tr>
<?php
	//get status for all the gws
	$carrier_statuses = Array ();

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	$command="dr_carrier_status";

	for ($i=0;$i<count($mi_connectors);$i++){
    	$comm_type=params($mi_connectors[$i]);
	    $message=mi_command($command, $errors, $status);
	}


	if ($comm_type != "json"){
		$message = explode("\n",trim($message));
		for ($i=0;$i<count($message);$i++){
    		preg_match('/^(?:ID:: )?([^ ]+)/',trim($message[$i]),$matchCarID);
	    	preg_match('/(?:Enabled=)?([^ ]+)$/',trim($message[$i]),$matchStatus);

    		$carrier_statuses[$matchCarID[1]]= $matchStatus [1];
		}
	}
	else {
		$message = json_decode($message,true);
		$message = $message['ID'];
		for ($j=0; $j<count($message); $j++){
			$carrier_statuses[$message[$j]['value']]= trim($message[$j]['attributes']['Enabled']);
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

 $result=$link->queryOne($sql_command_count);
 if(PEAR::isError($result)) {
         die('Failed to issue query count , error message : ' . $resultset->getMessage());
 }
 $data_no=$result;
 if ($data_no==0) echo('<tr><td colspan="12" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
          die('Failed to issue query select, error message : ' . $resultset->getMessage());
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
	//handle flags
	if (is_numeric($resultset[$i]['flags'])) {
		$useweights   = (fmt_binary($resultset[$i]['flags'],4,4)) ? "Yes" : "No" ;
		$useonlyfirst = (fmt_binary($resultset[$i]['flags'],4,3)) ? "Yes" : "No" ;
	}
	else{
		$useweights = "error";
		$usefirstonly = "error";
		$enabled = "error";
	}
	
    if ($resultset[$i]['description']!="") 
		$description=$resultset[$i]['description'];
    else 
		$description="&nbsp;";

    if ($resultset[$i]['attrs']!="") 
		$attrs=$resultset[$i]['attrs'];
    else 
		$attrs="&nbsp;";

	//handle status
	$carrier_status = $carrier_statuses[$resultset[$i]['carrierid']];

	   if ($carrier_status=="yes")
	           $status='<a href="'.$page_name.'?action=disablecar&carrierid='.$resultset[$i]['carrierid'].'"><img name="status'.$i.'" src="images/active.gif" alt="Enabled - Click to disable" onclick="return confirmDisable(\''.$resultset[$i]['carrierid'].'\');"></a>';
      else
	          $status='<a href="'.$page_name.'?action=enablecar&carrierid='.$resultset[$i]['carrierid'].'"><img name="status'.$i.'" src="images/inactive.gif" alt="Disabled - Click to enable" onclick="return confirmEnable(\''.$resultset[$i]['carrierid'].'\')"></a>';

   switch ($resultset[$i]['state']) {
   	case "0" : $state = "Active"; break;
	case "1" : $state = "Inactive"; break;
   }
	//edit and delete links					 
   $details_link='<a href="'.$page_name.'?action=details&carrierid='.$resultset[$i]['carrierid'].'"><img src="images/details.gif" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&carrierid='.$resultset[$i]['carrierid'].'"><img src="images/edit.gif" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&carrierid='.$resultset[$i]['carrierid'].'" onclick="return confirmDelete(\''.$resultset[$i]['carrierid'].'\')" ><img src="images/trash.gif" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['id']?></td>	
  <td class="<?=$row_style?>"><?=$resultset[$i]['carrierid']?></td>	
  <td class="<?=$row_style?>"><?=$gwlist?></td>
  <td class="<?=$row_style?>" align="center"><?=$useweights?></td>
  <td class="<?=$row_style?>" align="center"><?=$useonlyfirst?></td>
  <td class="<?=$row_style?>"><?=$description?></td>
  <td class="<?=$row_style?>"><?=$attrs?></td>
  <td class="<?=$row_style?>" align="center"><?=$state?></td>
  <td class="<?=$row_style?>" align="center"><?=$status?></td>
  <td class="<?=$row_style?>" align="center" rowspan="1"><?=$details_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="1"><?=$edit_link?></td>
  <td class="<?=$row_style?>" align="center" rowspan="1"><?=$delete_link?></td>
 </tr>
<?php
  }
 }
?>
 <tr>
  <td colspan="12" class="dataTitle">
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
