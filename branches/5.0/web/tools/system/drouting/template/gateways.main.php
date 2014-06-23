<!--
 *
 * $Id$
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

<form action="<?=$page_name?>?action=search" method="post">
<?php
 $sql_search="";

 $search_gwid=$_SESSION['gateways_search_gwid'];
 if ($search_gwid!="") {
     $sql_search.=" and gwid like '%" . $search_gwid . "%' ";
 } else
    $sql_search .=" and gwid like '%' ";


 $search_type=$_SESSION['gateways_search_type'];
 if ($search_type!="") $sql_search.=" and type='".$search_type."'";
	 $search_address=$_SESSION['gateways_search_address'];

 $search_address=$_SESSION['gateways_search_address'];
 if ($search_address!="") {
	 $sql_search.=" and address like '%" . $search_address . "%' ";
 } else 
	$sql_search .=" and address like '%' "; 
 
 $search_pri_prefix=$_SESSION['gateways_search_pri_prefix'];
 
 if ($search_pri_prefix!="") 
	$sql_search.=" and pri_prefix='".$search_pri_prefix."'";

$search_probe_mode=$_SESSION['gateways_search_probe_mode'];

 if ($search_probe_mode!="")
     $sql_search.=" and probe_mode='".$search_probe_mode."'";

 $search_description=$_SESSION['gateways_search_description'];

 if ($search_description!="") 
	$sql_search.=" and description like '%".$search_description."%'";

 $search_attrs=$_SESSION['gateways_search_attrs'];

 if ($search_attrs!="")
        $sql_search.=" and attrs like '%".$search_attrs."%'";

?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="searchTitle">Search Gateways by</td>
 </tr>
 <tr>
  <td class="searchRecord">GWID :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_gwid" value="<?=$search_gwid?>" maxlength="128" class="searchInput"></td>
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
  <td class="searchRecord" width="200"><input type="text" name="search_pri_prefix" value="<?=$search_pri_prefix?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord">Probe Mode :</td>
  <td class="searchRecord" width="200">
  	<select id="probe_mode" name="probe_mode" class="dataSelect">
	 <option value="0" selected>0 - Never</option>
	 <option value="1">1 - When disabled</option>
	 <option value="2">2 - Always</option>
	</select>
  </td>
</tr>
 <tr>
  <td class="searchRecord">Attributes :</td>
  <td class="searchRecord" width="200"><input type="text" name="search_attrs" value="<?=$search_attrs?>" maxlength="128" class="searchInput"></td>
 </tr>
 <tr>

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
 <?php if (!$_read_only) echo('<input type="submit" name="add_new" value="Add New" class="formButton">'); ?>
</form>

<table width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">ID</td>
  <td class="dataTitle">GWID</td>
  <td class="dataTitle">Type</td>
  <td class="dataTitle">Address</td>
  <td class="dataTitle">Strip</td>
  <td class="dataTitle">PRI Prefix</td>
  <td class="dataTitle">Probe Mode</td>
  <td class="dataTitle">Attributes</td>
  <td class="dataTitle">Description</td>
  <td class="dataTitle">Status</td>
  <td class="dataTitle">Details</td>
  <td class="dataTitle">Edit</td>
  <td class="dataTitle">Delete</td>
 </tr>
<?php
//get status for all the gws
$gw_statuses = Array ();

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
$command="dr_gw_status";

for ($i=0;$i<count($mi_connectors);$i++){
	$comm_type=params($mi_connectors[$i]);
	$message=mi_command($command, $errors, $status);
}


$message = explode("\n",trim($message));
for ($i=0;$i<count($message);$i++){
	preg_match('/^(?:ID:: )?([^ ]+)/',$message[$i],$matchGWID);
	preg_match('/(?:Enabled=)?([^ ]+)$/',$message[$i],$matchStatus);

	$gw_statuses[$matchGWID[1]]= $matchStatus [1];
}
//end get status

 if ($sql_search=="") $sql_command="from ".$table." where (1=1) order by id asc";
  else $sql_command="from ".$table." where (1=1) ".$sql_search." order by id asc";

  $sql_command_count = "select count(*) ".$sql_command;
  $sql_command = "select * ".$sql_command;
  $result = $link->queryOne($sql_command_count);
  if(PEAR::isError($result)) {
 	 die('Failed to issue query, error message : ' . $result->getMessage());
  }
  $data_no=$result;
 if ($data_no==0) 
 	echo('<tr><td colspan="13" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
   $gw_status = $gw_statuses[$resultset[$i]['gwid']];

   if ($gw_status=="yes") 
   		$status='<a href="'.$page_name.'?action=disablegw&gwid='.$resultset[$i]['gwid'].'"><img name="status'.$i.'" src="images/active.gif" alt="Enabled - Click to disable" onclick="return confirmDisable(\''.$resultset[$i]['gwid'].'\');"></a>';
   else 
   		$status='<a href="'.$page_name.'?action=enablegw&gwid='.$resultset[$i]['gwid'].'"><img name="status'.$i.'" src="images/inactive.gif" alt="Disabled - Click to enable" onclick="return confirmEnable(\''.$resultset[$i]['gwid'].'\')"></a>';


   if ($resultset[$i]['pri_prefix']!="") $pri_prefix=$resultset[$i]['pri_prefix'];
    else $pri_prefix="&nbsp;";

   if ($resultset[$i]['attrs']!="") $attrs=$resultset[$i]['attrs'];
    else $attrs="&nbsp;";

   switch ($resultset[$i]['probe_mode']) {
   	case "0" : $probe_mode = "Never"; break;
	case "1" : $probe_mode = "When disabled"; break;
	case "2" : $probe_mode = "Always"; break;
   }
   $details_link='<a href="'.$page_name.'?action=details&gwid='.$resultset[$i]['gwid'].'"><img src="images/details.gif" border="0"></a>';
   $edit_link='<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'"><img src="images/edit.gif" border="0"></a>';
   $delete_link='<a href="'.$page_name.'?action=delete&gwid='.$resultset[$i]['gwid'].'" onclick="return confirmDelete(\''.$resultset[$i]['gwid'].'\')"><img src="images/trash.gif" border="0"></a>';
   if ($_read_only) $edit_link=$delete_link='<i>n/a</i>';
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['id']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['gwid']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['type']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['address']?></td>
  <td class="<?=$row_style?>"><?=$resultset[$i]['strip']?></td>
  <td class="<?=$row_style?>"><?=$pri_prefix?> </td>
  <td class="<?=$row_style?>"><?=$probe_mode?> </td>
  <td class="<?=$row_style?>"><?=$attrs?> </td>
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
  <td colspan="13" class="dataTitle">
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