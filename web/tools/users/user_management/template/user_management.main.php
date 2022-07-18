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

<?php
if ($keepoverlay){
	echo "<script language='JavaScript'>";
	echo "show_contacts('".$_POST['username']."','".$_POST['domain']."')";
	echo "</script>";
}

$sql_search="";
$sql_vals=array();

$search_uname = $_SESSION['lst_uname'];
$search_domain = $_SESSION['lst_domain'];

## check what other tools are available
require("../../../../config/modules.inc.php");
if ( file_exists("../group_management") &&
$config_modules["users"]["modules"]["group_management"]["enabled"]==true
)
	$has_acl = true;
else
	$has_acl = false;

if ( file_exists("../alias_management") &&
$config_modules["users"]["modules"]["alias_management"]["enabled"]==true
)
	$has_alias = true;
else
	$has_alias = false;


if ($search_uname !="") {
	if (strpos($search_uname,"%")===false)
		$sql_search.=" AND s.username = ?";
	else
		$sql_search.=" AND s.username like ?";
	array_push( $sql_vals, $search_uname);
}
if (($search_domain!="ANY") && ($search_domain!="")) {
	$sql_search.=" AND s.domain = ?";
	array_push( $sql_vals, $search_domain);
}
foreach (get_settings_value("subs_extra") as $key => $value) {
	if (!isset($value["searchable"]) || !$value["searchable"])
		continue;
	if (isset($_SESSION['extra_'.$key]) && $_SESSION['extra_'.$key] != "") {
		$sql_search.=" AND s.".$key." like ?";
		array_push( $sql_vals, '%'.$_SESSION['extra_'.$key].'%');
	}
}

if(!$_SESSION['read_only']){
	$colspan = 5;
}else{
	$colspan = 3;
}

if ($has_acl){
	$colspan++;
}

if ($has_alias){
	$colspan++;
}

$subs_extra_actions = get_settings_value("subs_extra_actions");
if (isset($subs_extra_actions))
	$colspan += count($subs_extra_actions);

if (!isset($users))
	$users = '';
if ( $users == "online_usr" ) {
	$checkedAll="";
	$checkedOnline="checked";
	$checkedOffline="";
} else if ( $users == "offline_usr" ) {
	$checkedAll="";
	$checkedOnline="";
	$checkedOffline="checked";
} else {
	$users = "all_usr";
	$checkedAll='checked';
	$checkedOnline="";
	$checkedOffline="";
}
  ?>
<form action="<?=$page_name?>?action=dp_act" method="post">
<?php csrfguard_generate(); ?>
<table  class="search-area" width="350" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="searchRecord" align="left">Username</td>
  <td class="searchRecord" width="200"><input type="text" name="lst_uname" 
  value="<?=$search_uname?>" maxlength="16" class="searchInput"></td>
 </tr>
 <tr>
  <td class="searchRecord" align="left">Domain</td>
  <td class="searchRecord" width="200"><?php print_domains("lst_domain",$search_domain,TRUE);?></td>
 </tr>
<?php
foreach (get_settings_value("subs_extra") as $key => $value) {
	if (!isset($value["searchable"]) || !$value["searchable"])
		continue;
?>
 <tr>
  <td class="searchRecord" align="left"><?=$value["header"]?></td>
  <td class="searchRecord" width="200"><input type="text" name="extra_<?=$key?>"
  value="<?=isset($_SESSION['extra_'.$key])?$_SESSION['extra_'.$key]:''?>" maxlength="16" class="searchInput"></td>
 </tr>
<?php
}
?>
 <tr>
  <td align="right" class="searchRecord"><input type="radio" name="users" 
  value="all_usr" <?php if ($users=="all_usr") echo "checked=\"true\"";?> ></td>
  <td class="searchRecord" align="left">All Users</td>
 </tr>
 <tr>	
  <td align="right" class="searchRecord"><input type="radio" name="users" 
  value="online_usr" $checkedOnline  <?php if ($users=="online_usr") echo "checked=\"true\"";?> ></td>
  <td class="searchRecord" align="left">Online Users</td>
 </tr>
 <tr>	
  <td align="right" class="searchRecord"><input type="radio" name="users" 
  value="offline_usr" <?php if ($users=="offline_usr") echo "checked=\"true\"";?>  ></td>
  <td class="searchRecord" align="let">Offline Users</td>
 </tr>
 <tr height="10">
  <td colspan="2" class="searchRecord border-bottom-devider" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>

</table>
</form>
<br>
<form action="<?=$page_name?>?action=add" method="post">
 <?php csrfguard_generate();
 if (!$_SESSION['read_only']) echo('<input type="submit" name="add" value="Add New" class="formButton">') ?>
</form>
<br>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">Username</th>

  <?php
	$combo_cache = array();
	require_once("../../../common/forms.php");
	foreach ( get_settings_value("subs_extra") as $key => $value ) {
		if (isset($value["show_in_main_form"]) && !$value["show_in_main_form"])
			continue;
		echo ('<th class="listTitle">'.$value['header'].'</th>');
		$colspan++;
		if (isset($value["type"]) && $value["type"] == "combo")
			$combo_cache[ $key ] = get_combo_options($value);
	}
	
	echo ('<th class="listTitle">Contacts</th>');

	if ($has_alias) {
		echo('<th class="listTitle">Alias</th>');
	}
	
	if ($has_acl) {
		echo('<th class="listTitle">Group</th>');
	}
	
	if (isset($subs_extra_actions)) {
		foreach ($subs_extra_actions as $key => $value ) {
			echo('<th class="listTitle">'.$value['header'].'</th>');
		}
	}
	if(!$_SESSION['read_only']){
		echo('<th class="listTitle">Edit</th>
			<th class="listTitle">Delete</th>');
	}

	echo('</tr>');

if ($users=="all_usr" || $users=="") {
	if ($sql_search!="") $sql_search = "WHERE ".substr($sql_search,4);
	$sql_command="from ".$table." s ".$sql_search;
	$sql_order=" order by s.id asc";
} else if ($users=="online_usr") {
	$table_location = get_settings_value('table_location');
	$sql_command="from ".$table." s, $table_location l where s.username=l.username AND s.domain=l.domain ".$sql_search;
	$sql_order=" order by s.id asc";
} else if ($users=="offline_usr") {
	//if ($sql_search!="") $sql_search = substr($sql_search,4);
	$table_location = get_settings_value('table_location');
	$sql_command="from ".$table." s where s.username NOT IN (select s.username from $table s,$table_location l where s.username=l.username AND s.domain=l.domain )".$sql_search;
	$sql_order=" order by s.id asc";
}

$stm = $link->prepare("select count(*) ".$sql_command);
if ($stm===FALSE) {
	die('Failed to issue query [select count(*) '.$sql_command.'], error message : ' . $link->errorInfo()[2]);
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
	if ($start_limit==0) $sql_order.=" limit ".$res_no;
	else $sql_order.=" limit ".$res_no." OFFSET " . $start_limit;
	$stm = $link->prepare("select * ".$sql_command.$sql_order);
        if ($stm===FALSE)
                die('Failed to issue query [select * '.$sql_command.$sql_order.'], error message : ' . print_r($link->errorInfo(), true));
	$stm->execute( $sql_vals );
        $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	$index_row=0;
	$i=0;
	while (count($resultset)>$i)
	{
		$index_row++;
		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		if ($has_acl) {
			$group_link = '<a href="../group_management/group_management.php?action=dp_act&fromusrmgmt=1&username='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"><img src="../../../images/share/info.png" border="0"></a>';
			
		}
		if ($has_alias) {
			$alias_link = '<a href="../alias_management/alias_management.php?action=dp_act&fromusrmgmt=1&username='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"><img src="../../../images/share/alias.png" border="0"></a>';
		}

		if(!$_SESSION['read_only']){
			$edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'&table='.$table.'"><img src="../../../images/share/edit.png" border="0"></a>';
			$delete_link='<a href="'.$page_name.'?action=delete&id='.$resultset[$i]['id'].'&uname='.$resultset[$i]['username'].'&domain='.$resultset[$i]['domain'].'"onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a>';
		}
?>
 <tr>
  <td class="<?=$row_style?>"><?=$resultset[$i]['username'].'@'.$resultset[$i]['domain']?></td>

<?php
	foreach ( get_settings_value("subs_extra") as $key => $value ) {
		if (isset($value["show_in_main_form"]) && !$value["show_in_main_form"])
			continue;
		        echo "<td class='".$row_style."'>";
		if (!isset($value["type"]) || $value["type"] == "text" ) {
			$text = $resultset[$i][$key];
		} else {
			$text = isset($resultset[$i][$key]) ?
				$combo_cache[$key][ $resultset[$i][$key] ]['display'] : "";
		}
		if (isset($value['value_wrapper_func'])) {
			eval("\$func = ".$value['value_wrapper_func'].';');
			echo $func($key, $text, $resultset[$i]);
		} else {
			echo $text;
		}
		echo "</td>";
	}
?>

  <td class="<?=$row_style."Img"?>" align="center">
    <a href="javascript:;" onclick="show_contacts('<?=$resultset[$i]['username']?>','<?=$resultset[$i]['domain']?>')">
		<img src="../../../images/share/phone.png" border="0">
	</a>
  </td>

   <?php 

	if ($has_alias){
		echo('<td class="'.$row_style.'Img" align="center">'.$alias_link.'</td>');
	}

	if ($has_acl){
		echo('<td class="'.$row_style.'Img" align="center">'.$group_link.'</td>');
	}

	if (isset($subs_extra_actions)) {
		foreach ( $subs_extra_actions as $key => $value ) {
			if (isset($value['action_func']) && $value['action_func'] != NULL) {
				eval("\$func = ". $value['action_func'].';');
				$custom_extra_action = $func($resultset[$i]);
			} else if (isset($value['action_url']) && $value['action_url'] != NULL) {
				$custom_extra_action = $value['action_url'];
			} else {
				$custom_extra_action = "";
			}
			if (isset($value['icon_func']) && $value['icon_func'] != NULL) {
				eval("\$func = ". $value['icon_func'].';');
				$custom_extra_icon = $func($resultset[$i]);
			} else if (isset($value['icon']) && $value['icon'] != NULL) {
				$custom_extra_icon = '<img src="'.$value['icon'].'" border="0">';
			} else {
				$custom_extra_icon = "";
			}

			if ($custom_extra_action != "")
				$custom_extra_link ='<a href="'. $custom_extra_action . '">'.$custom_extra_icon.'</a>';
			else
				$custom_extra_link = $custom_extra_icon;
			echo('<td class="'.$row_style.'Img" align="center">'.$custom_extra_link.'</td>');
		}
	}

	if(!$_SESSION['read_only']){
		echo('<td class="'.$row_style.'Img" align="center">'.$edit_link.'</td>
			<td class="'.$row_style.'Img" align="center">'.$delete_link.'</td>');
	}
?>  
  </tr>  
<?php

	$i++;
	}
}
?>
 <tr>
  <th colspan="<?=$colspan?>" >
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
      <th align="right" >Total Records: <?=$data_no?>&nbsp;</th>
     </tr>
    </table>
  </th>
 </tr>
</table>



