<?php
/*
 * Copyright (C) 2019 OpenSIPS Project
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

$sql_search = "";
$sql_values = array();

if ( isset($_SESSION['sip_trunk_registrar']) )
    $search_registrar = $_SESSION['sip_trunk_registrar'];
else
    $search_registrar = "";
if ( isset($_SESSION['sip_trunk_proxy']) )
    $search_proxy = $_SESSION['sip_trunk_proxy'];
else
    $search_proxy = "";
if ( isset($_SESSION['sip_trunk_aor']) )
    $search_aor = $_SESSION['sip_trunk_aor'];
else
    $search_aor = "";

if( $search_registrar != "") {
    $sql_search .= " and registrar=?";
    array_push( $sql_values, $search_registrar);
}
if( $search_proxy != "") {
    $sql_search .= " and proxy like ?";
    array_push( $sql_values, "%".$search_proxy."%");
}
if( $search_aor != "") {
    $sql_search .= " and aor like ?";
    array_push( $sql_values, "%".$search_aor."%");
}

if( !$_SESSION['read_only'] ) {
    $colspan = 14;
}else{
    $colspan = 12;
}
?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<form action="<?=$page_name?>?action=sip_trunk_search" method="post">

    <table width="50%" cellspacing="2" cellpadding="2" border="0">
	<tr>
	    <td class="searchRecord">Registrar</td>
	    <td class="searchRecord" width="200"><input type="text" name="sip_trunk_registrar"
							value="<?=$search_registrar?>" class="searchInput"></td>
	</tr>
	<tr>
	    <td class="searchRecord">Proxy</td>
	    <td class="searchRecord" width="200"><input type="text" name="sip_trunk_proxy"
							value="<?=$search_proxy?>" maxlength="16" class="searchInput"></td>
	</tr>
	<tr>
	    <td class="searchRecord">Address of Registrant</td>
	    <td class="searchRecord" width="200"><input type="text" name="sip_trunk_aor"
							value="<?=$search_aor?>" maxlength="128" class="searchInput"></td>
	</tr>
	<tr height="10">
	    <td colspan="2" class="searchRecord border-bottom-devider" align="center">
		<input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
		<input type="submit" name="show_all" value="Show All" class="searchButton"></td>
	</tr>
    </table>
</form>


<?php if ( !$_SESSION['read_only'] ) { ?>
    <form action="<?=$page_name?>?action=add&clone=0" method="post">
	<input type="submit" name="add_new" value="Add SIP Trunk" class="formButton"> &nbsp;&nbsp;&nbsp;
	<!--input type="submit" name="refresh" value="Refresh from Server" class="searchButton"--> &nbsp;&nbsp;&nbsp;
	<input onclick="apply_changes()" name="reload" class="formButton" value="Reload on Server" type="button"/>
    </form>
<?php } ?>

<table class="ttable" width="95%" cellspacing="2" cellpadding="2" border="0">
    <tr align="center">
	<th class="listTitle">Registrar</th>
	<th class="listTitle">Proxy</th>
	<th class="listTitle">Address of Registrant</th>
	<th class="listTitle">3rd Party Registrant</th>
	<th class="listTitle">Username</th>
	<th class="listTitle">Password</th>
	<th class="listTitle">Binding URI</th>
	<th class="listTitle">Binding Params</th>
	<th class="listTitle">Expiry</th>
	<th class="listTitle">Forced Socket</th>
	<th class="listTitle">Cluster Share-Tag</th>
	<?php
	if ( !$_SESSION['read_only'] ) {
	    echo('<th class="listTitle">Edit</th>
		<th class="listTitle">Delete</th>
		<th class="listTitle">Clone</th>');
	}
	?>
    </tr>

    <?php
    if ( $sql_search == "" ) {
	$sql_command = "select * from " . $table . " where (1=1) order by registrar, proxy, aor asc";
	$sql_count = "select count(*) from " . $table . " where (1=1)";
    }
    else {
	$sql_command = "select * from ".$table." where (1=1) " . $sql_search . " order by registrar, proxy, aor asc";
	$sql_count = "select count(*) from " . $table . " where (1=1) " . $sql_search;
    }

    $stm = $link->prepare($sql_count);
    if ( $stm === FALSE ) {
	die('Failed to issue query [' . $sql_count . '], error message : ' . $link->errorInfo()[2]);
    }
    $stm->execute( $sql_values );
    $data_no = $stm->fetchColumn(0);

    if ( $data_no == 0 )
	echo('<tr><td colspan="' . $colspan . '" class="rowEven" align="center"><br>' .
	     $no_result . '<br><br></td></tr>');
    else {
	$sip_trunk_state = array();
	$sip_trunk_res = array();
	$sip_trunk_auto = array();

	/*
	   // get in memory status for the entries we want to list
	   $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	   $message = mi_command('sip_trunk_list', $mi_connectors[0], $errors, $status);

	   $sip_trunk_state = array();
	   $sip_trunk_res = array();
	   $sip_trunk_auto = array();

	   $message = json_decode($message,true);
	   $message = $message['Destination'];
	   for ( $i=0; $i<count($message); $i++ ) {
	   $id		= $message[$i]['attributes']['id'];

	   $resource="";
	   $res = $message[$i]['children']['Resources']['children']['Resource'];
	   for ( $j=0; $j<count($res); $j++ ) {
	   $resource .= "<tr>";
	   $resource .= "<td>".$res[$j]['value']."=".$res[$j]['attributes']['load']."/".$res[$j]['attributes']['max']."</td>";
	   $resource .= "</tr>";
	   }
	   $sip_trunk_res[$id] = "<table class=\"pagingtable\" width=\"100%!important;\" cellspacing=\"2\"
	   cellpadding=\"2\" border=\"0\">".$resource."</table>";
	   //$sip_trunk_res[$id] = "<table style=\"width:100%!important;\">".$resource."</table>";
	   $sip_trunk_state[$id] = ($message[$i]['attributes']['enabled']=="yes")?"enabled":"disabled";
	   $sip_trunk_auto[$id] = $message[$i]['attributes']['auto-reenable'];
	   }
	 */

	$res_no = $config->results_per_page;
	$page = $_SESSION[$current_page];
	$page_no = ceil( $data_no/$res_no );
	if ( $page > $page_no ) {
	    $page = $page_no;
	    $_SESSION[$current_page] = $page;
	}

	$start_limit = ( $page-1 ) * $res_no;
	if ( $start_limit == 0 )
	    $sql_command .= " limit " . $res_no;
	else
	    $sql_command .= " limit " . $res_no . " OFFSET " . $start_limit;

	// pepare SQL statement
	$stm = $link->prepare($sql_command);
	if ( $stm === FALSE ) {
	    die('Failed to issue query [' . $sql_command . '], error message : ' . $link->errorInfo()[2]);
	}

	// execute the SQL statement and fetch results
	$stm->execute( $sql_values );
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);

	// display the resulting rows in the table
	$index_row = 0;
	for ( $i=0; count($result)>$i; $i++ )
	{
	    $index_row++;
	    $id = $result[$i]['id'];

	    if ( $index_row%2 == 1 )
		$row_style="rowOdd";
	    else
		$row_style="rowEven";

	    /* if the resources were not fetched via MI, used
	       the DB values */
	    //if ( $sip_trunk_res[$id] == NULL || $sip_trunk_res[$id] == "" )
	    //	$sip_trunk_res[$id] = $result[$i]['resources'];

	    if( !$_SESSION['read_only'] ) {
		$edit_link = '<a href="'.$page_name.'?action=edit&clone=0&id='.$result[$i]['id'].'"><img src="../../../images/share/edit.png" border="0"></a>';
		$delete_link='<a href="'.$page_name.'?action=delete&clone=0&id='.$result[$i]['id'].'" onclick="return confirmDelete()"><img src="../../../images/share/delete.png" border="0"></a>';
		$clone_link='<a href="'.$page_name.'?action=clone&clone=1&id='.$result[$i]['id'].'"><img src="../../../images/share/clone.gif" border="0"></a>';
	    }
    ?>
    <tr>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['registrar']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['proxy']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['aor']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['third_party_registrant']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['username']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['password']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['binding_uri']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['binding_params']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['expiry']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['forced_socket']?></td>
	<td class="<?=$row_style?>">&nbsp;<?=$result[$i]['cluster_shtag']?></td>
	<?php
	if ( !$_SESSION['read_only'] ) {
	    echo('<td class="'.$row_style.'Img" align="center">'.$edit_link.'</td>
	      <td class="'.$row_style.'Img" align="center">'.$delete_link.'</td>
	      <td class="'.$row_style.'Img" align="center">'.$clone_link.'</td>');
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
			if ( $data_no == 0 )
			    echo('<font class="pageActive">0</font>&nbsp;');
			else {
			    $max_pages = $config->results_page_range;
			    // start page
			    if ( $page % $max_pages == 0 )
				$start_page = $page - $max_pages + 1;
			    else
				$start_page = $page - ($page % $max_pages) + 1;
			    // end page
			    $end_page = $start_page + $max_pages - 1;
			    if ( $end_page > $page_no )
				$end_page = $page_no;
			    // back block
			    if ( $start_page != 1 )
				echo('&nbsp;<a href="'.$page_name.'?page='.($start_page-$max_pages).'" class="menuItem"><b>&lt;&lt;</b></a>&nbsp;');
			    // current pages
			    for ( $i = $start_page; $i <= $end_page; $i++ )
				if ( $i == $page )
				    echo('<font class="pageActive">'.$i.'</font>&nbsp;');
			    else
				echo('<a href="'.$page_name.'?page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
			    // next block
			    if ($end_page != $page_no)
				echo('&nbsp;<a href="'.$page_name.'?page='.($start_page+$max_pages).'" class="menuItem"><b>&gt;&gt;</b></a>&nbsp;');
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
