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


$search_ausername=$_SESSION['grp_username'];
$search_adomain=$_SESSION['grp_domain'];
$search_agrp=$_SESSION['grp_grp'];


$sql_search="";
$sql_vals=array();

if ($search_ausername !="") {
	$sql_search.=" and username like ?";
	array_push( $sql_vals, "%".$search_ausername."%");
}
if (($search_adomain != 'ANY') && ($search_adomain != "")) {
	$sql_search.=" and domain=?";
	array_push( $sql_vals, $search_adomain);
}
if (($search_agrp !='ANY') && ($search_agrp !="")) {
	$sql_search.=" and grp=?"; 
	array_push( $sql_vals, $search_agrp);
}

if ($sql_search!="")
	$sql_search = " where ".substr($sql_search,4);

if(!$_SESSION['read_only']){
        $colspan = 7;
}else{
        $colspan = 5;
}

?>

<form action="<?=$page_name?>?action=dp_act" method="post">
<?php csrfguard_generate(); ?>
<table class="search-area" width="350" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td class="searchRecord" align="left">Username</td>
	<td class="searchRecord" width="200"><input type="text" name="username"
	value="<?=$search_ausername?>" maxlength="16" class="searchInput"></td>
</tr>
<tr>
	<td class="searchRecord" align="left">Domain</td>
	<td class="searchRecord" width="200"> <?php print_domains("domain",$search_adomain, TRUE)?></td>
</tr>
<tr>
	<td class="searchRecord" align="left">Group</td>
	<td class="searchRecord" width="200"> <?php print_groups("group",$search_agrp,TRUE)?></td>
</tr>

<tr height="10">
	<td colspan="2" class="searchRecord border-bottom-devider" align="center">
		<input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
		<input type="submit" name="show_all" value="Show All" class="searchButton">&nbsp;&nbsp;&nbsp;
<?php
if(!$_SESSION['read_only']){
	echo('<input type="submit" class="formButton" name="delete" value="Delete" onclick="return confirmDeleteALL()">');
}
?>
	</td>
</tr>



</table>
</form>

<br>
<form action="<?=$page_name?>?action=add" method="post">
 <?php csrfguard_generate();
 if (!$_SESSION['read_only']) echo('<input type="submit" name="add" value="Add New Group" class="formButton add-new-btn">') ?>
</form>
<br>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
<tr align="center">
<th class="listTitle">Username</th>
<th class="listTitle">Domain</th>
<th class="listTitle">Group</th>
<?php
if(!$_SESSION['read_only']){

echo('<th class="listTitle">Edit</th>
	<th class="listTitle">Delete</th>');
}
?>
</tr>

<?php
        require("../../../../config/db.inc.php");
        session_load();

	$sql_command="from ".$table.$sql_search;
	$stm = $link->prepare("select count(*) ".$sql_command);
	if ($stm===FALSE) {
		die('Failed to issue query [select count(*) '.$sql_command.'], error message : ' . $link->errorInfo()[2]);
	}
	$stm->execute( $sql_vals );
	$data_no = $stm->fetchColumn(0);

	if ($data_no==0)
		echo('<tr><td colspan="'.$colspan.'" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>'); 
	else { 

        $res = get_settings_value("results_per_page");
        $page=$_SESSION[$current_page];
        $page_no=ceil($data_no/$res);
        if ($page>$page_no) {
                $page=$page_no;
                $_SESSION[$current_page]=$page;
        }
        $start_limit=($page-1)*$res;
        if ($start_limit==0) $sql_command.=" order by id asc limit ".$res;
        else $sql_command.=" order by id asc limit ".$res." OFFSET " . $start_limit;
	$stm = $link->prepare("select * ".$sql_command);
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

                if(!$_SESSION['read_only']){
                        $edit_link = '<a href="'.$page_name.'?action=edit&id='.$resultset[$i]['id'].'&table='.$table.'"><img src="../../../images/share/edit.png" border="0"></a>';
                        $delete_link='<a href="'.$page_name.'?action=delete&table='.$table.'&id='.$resultset[$i]['id'].'"onclick="return confirmDeleteGRP()"><img src="../../../images/share/delete.png" border="0"></a>';

		}
?>
 <tr>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['username']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['domain']?></td>
  <td class="<?=$row_style?>">&nbsp;<?=$resultset[$i]['grp']?></td>
   <?php
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
  <th colspan="<?=$colspan?>">
    <table class="pagingTable">
     <tr>
      <th align="left">Page:
       <?php
       if ($data_no==0) echo('<font class="ageActive">0</font>&nbsp;');
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

