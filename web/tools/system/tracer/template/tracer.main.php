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


$sql_search="";
$sql_vals=array();
$search_groupid=$_SESSION['lb_groupid'];
$search_dsturi=$_SESSION['lb_dsturi'];
$search_resources=$_SESSION['lb_resources'];

if(!$_SESSION['read_only']){
	$colspan = 10;
}else{
	$colspan = 8;
}
?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>


<form action="<?=$page_name?>?action=search" method="post">
<?php csrfguard_generate(); ?>
<table width="50%" cellspacing="2" cellpadding="2" border="0">
  <tr>
  <td class="searchRecord">Group ID</td>
  <td class="searchRecord" width="200"><input type="text" name="lb_groupid" 
  value="<?=$search_groupid?>" class="searchInput"></td>
 </tr>
  <tr>
  <td class="searchRecord">SIP URI</td>
  <td class="searchRecord" width="200"><input type="text" name="lb_dsturi" 
  value="<?=$search_dsturi?>" maxlength="16" class="searchInput"></td>
 </tr>
  <tr>
  <td class="searchRecord">Resources</td>
  <td class="searchRecord" width="200"><input type="text" name="lb_resources" 
  value="<?=$search_resources?>" maxlength="128" class="searchInput"></td>
 </tr>
  <tr height="10">
  <td colspan="2" class="searchRecord border-bottom-devider" align="center">
  <input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
  <input type="submit" name="show_all" value="Show All" class="searchButton"></td>
 </tr>
</table>
</form>


<?php if (!$_SESSION['read_only']) { ?>
<form action="<?=$page_name?>?action=add" method="post">
<?php csrfguard_generate(); ?>
  <input type="submit" name="add_new" value="Add Destination" class="formButton"> &nbsp;&nbsp;&nbsp;
  <input type="submit" name="refresh" value="Refresh from Server" class="searchButton"> &nbsp;&nbsp;&nbsp;
  <input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
</form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <th class="listTitle">ID</th>
  <th class="listTitle">Group ID</th>
  <th class="listTitle">SIP URI</th>
  <th class="listTitle">Resources</th>
  <th class="listTitle">Probe Mode</th>
  <th class="listTitle">Auto Re-enable</th>
  <th class="listTitle">Status</th>
  <th class="listTitle">Attributes</th>
  <th class="listTitle">Description</th>
  <?php
  if(!$_SESSION['read_only']){

  	echo('<th class="listTitle">Edit</th>
  		<th class="listTitle">Delete</th>');
  }
  ?>
 </tr>

<?php
if($search_groupid!="") { 
	$sql_search.=" and group_id=?";
	array_push( $sql_vals, $search_groupid);
}
if ( $search_dsturi!="" ) {
	$sql_search.=" and dst_uri like ?";
	array_push( $sql_vals, "%".$search_dsturi."%");
}
if ( $search_resources!="" ) {
	$sql_search.=" and resources like ?";
	array_push( $sql_vals, "%".$search_resources."%");
}

$sql_command="select count(*) from ".$table." where (1=1) ".$sql_search;
$stm = $link->prepare($sql_command);
if ($stm===FALSE) {
	die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
}
$stm->execute( $sql_vals );
$data_no = $stm->fetchColumn(0);

if ($data_no==0)
	echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
else {
	// get in memory status for the entries we want to list
	$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
	$message = mi_command('lb_list', NULL, $mi_connectors[0], $errors);

	$lb_state = array();
	$lb_res = array();
	$lb_auro = array();

	if (!is_null($message)) {
		$message = $message['Destinations'];
		for ($i=0; $i<count($message);$i++) {
			$id 		= $message[$i]['id'];

			$resource="";
			$res = $message[$i]['Resources'];
			for ($j=0;$j<count($res);$j++) {
				$resource .= "<tr>";
				$resource .= "<td>".$res[$j]['name']."=".$res[$j]['load']."/".$res[$j]['max']."</td>";
				$resource .= "</tr>";
			}
			$lb_res[$id] = "<table style=\"width:100%!important;\">".$resource."</table>";
			$lb_state[$id] = ($message[$i]['enabled']=="yes")?"enabled":"disabled";
			$lb_auto[$id] = $message[$i]['auto-reenable'];
		}
	}

	$res_no=get_settings_value("results_per_page");
	$page=$_SESSION[$current_page];
	$page_no=ceil($data_no/$res_no);
	if ($page>$page_no) {
		$page=$page_no;
		$_SESSION[$current_page]=$page;
	}
	$start_limit=($page-1)*$res_no;

	$sql_command = "select * from ".$table." where (1=1) ".$sql_search." order by id asc";
	if ($start_limit==0) $sql_command.=" limit ".$res_no;
	else $sql_command.=" limit ". $res_no . " OFFSET " . $start_limit;

	$stm = $link->prepare($sql_command);
	if ($stm===FALSE) {
		die('Failed to issue query ['.$sql_command.'], error message : ' . $link->errorInfo()[2]);
	}
	$stm->execute( $sql_vals );
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);

	// display the resulting rows in the table
	$index_row=0;
	for ($i=0;count($result)>$i;$i++)
	{
		$index_row++;
		$id = $result[$i]['id'];

		if ($index_row%2==1) $row_style="rowOdd";
		else $row_style="rowEven";

		/* if the resources were not fetched via MI, used
		   the DB values */
		if ($lb_res[$id]==NULL || $lb_res[$id]=="")
			$lb_res[$id] = $result[$i]['resources'];
		?>
		<tr>
			<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['id']?></td>
			<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['group_id']?></td>
			<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['dst_uri']?></td>
			<td class="<?=$row_style?>"><?=$lb_res[$id]?></td>
			<td class="<?=$row_style?>">&nbsp;<?=$lb_probing_modes[$result[$i]['probe_mode']]?></td>
			<td class="<?=$row_style?>">&nbsp;<?=$lb_auto[$id]?></td>
			<td class="<?=$row_style?>">&nbsp;
			<?php 
                        if ($lb_state[$id]==NULL) {
				echo "-";
			} else if ($_SESSION['read_only']) {
			?>
				<img name="toggle" src="../../../images/share/<?=($lb_state[$id]=="enabled"?"active":"inactive")?>.png" alt="<?=$lb_state[$id]?>" border="0">
			<?php
			} else {
			?>	
				<a href="<?=$page_name?>?action=toggle&state=<?=$lb_state[$id]?>&id=<?=$result[$i]['id']?>"><img name="toggle" src="../../../images/share/<?=($lb_state[$id]=="enabled"?"active":"inactive")?>.png" alt="<?=$lb_state[$id]?>" onclick="return confirmStateChange('<?=$lb_state[$id]?>')" border="0"></a>
			<?php
			}
			?>
			</td>
			<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['attrs']?></td>
			<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['description']?></td>
			<?php 
			if(!$_SESSION['read_only']){
				echo('<td class="'.$row_style.'" align="center"><a href="'.$page_name.'?action=edit&id='.$result[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a></td>');
				echo('<td class="'.$row_style.'" align="center"><a href="'.$page_name.'?action=delete&id='.$result[$i]['id'].'"onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a></td>');
   			}
			?>  
		</tr>  
<?php
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


