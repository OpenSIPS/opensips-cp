<!--
 *
 * $Id$
 * Copyright (C) 2016 PARADIS Corentin
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

</td>
</tr>
</table>
</center>
</body>

</html>
<?php
require_once("../../../../config/session.inc.php");
require_once("../../../../config/tools/system/blacklist/local.inc.php");
require_once("lib/functions.inc.php");
require_once("../../../../config/tools/system/blacklist/menu.inc.php");
$page_name = basename($_SERVER['PHP_SELF']);
$page_id = substr($page_name, 0, strlen($page_name) - 4);
$back_link = '<a href="'.$page_name.'" class="backLink">Go Main</a>';
$no_result = "No Data Found.";
?>

<html>

<head>
	<link href="style/style.css" type="text/css" rel="StyleSheet">
	<!--META HTTP-EQUIV=REFRESH CONTENT=5-->
</head>

<body bgcolor="#e9ecef">
	<center>
		<form action="<?=$page_name?>?action=dp_act" method="post">
			<?php
			$sql_search="SELECT * FROM userblacklist WHERE '1'='1'";
			$search_prefix = $_SESSION['lst_u_prefix'];
			$search_whitelist = $_SESSION['lst_u_whitelist'];
			$search_domain = $_SESSION['lst_u_domain'];
			$search_username = $_SESSION['lst_u_username'];
			if($search_prefix !="") $sql_search.=" AND prefix LIKE '" . $search_prefix . "%'";
			else $sql_search.=" AND prefix like '%'";

			if($search_whitelist != "") $sql_search.=" AND whitelist = '" . $search_whitelist . "'";

			if($search_domain !="") $sql_search.=" AND domain LIKE '%" . $search_domain . "%'";
			else $sql_search.=" AND domain like '%'";

			if($search_username !="") $sql_search.=" AND username LIKE '%" . $search_username . "%'";
			else $sql_search.=" AND username like '%'";

			$sql_search.=" ORDER BY prefix ASC";

			?>
			<table name="ttable" cellspacing="2" cellpadding="2" border="0">
				<tr align="center">
					<td colspan="2" height="10" class="blacklistTitle"></td>
				</tr>
				<tr>
					<td class="searchRecord" align="left"><label for="prefix">Prefix</label></td>
					<td class="searchRecord" width="200"><input type="text" name="lst_prefix" value="<?=$search_prefix?>" id="prefix" maxlength="64" class="searchInput"></td>
				</tr>
				<tr>
					<td class="searchRecord" align="left"><label for="username">Username</label></td>
					<td class="searchRecord" width="200"><input type="text" name="lst_username" value="<?=$search_username?>" id="username" maxlength="64" class="searchInput"></td>
				</tr>
				<tr>
					<td align="right" class="searchRecord"><input type="radio" name="lst_whitelist" id="blacklisted" value="0" <?php if ($search_whitelist=="0") echo "checked=\"true\"";?> ></td>
					<td class="searchRecord" align="left"><label for="blacklisted">Blacklisted</label></td>
				</tr>
				<tr>	
					<td align="right" class="searchRecord"><input type="radio" name="lst_whitelist" id="whitelisted" value="1" <?php if ($search_whitelist=="1") echo "checked=\"true\"";?> ></td>
					<td class="searchRecord" align="left"><label for="whitelisted">Whitelisted</label></td>
				</tr>
				<tr>	
					<td class="searchRecord" align="left"><label for="domain">Domain</label></td>
					<td class="searchRecord" width="200"><input type="text" name="lst_domain" value="<?=$search_domain?>" id="domain" maxlength="255" class="searchInput"></td>
				</tr>
				<tr height="10">
					<td colspan="2" class="searchRecord" align="center">
						<input type="submit" name="search" value="Search" class="searchButton">&nbsp;&nbsp;&nbsp;
						<input type="submit" name="show_all" value="Show All" class="searchButton">
					</td>
				</tr>

				<tr height="10">
					<td colspan="2" class="blacklistTitle"><img src="images/spacer.gif" width="5" height="5"></td>
				</tr>

			</table>
		</form>

		<?php
		if (!$_SESSION['read_only']) {
			?>
			<form action="<?=$page_name?>?action=add" method="post">
				<input type="submit" name="add" value="Add a new entry" class="formButton">
			</form>
			<?php
		}
		?>

		<table class="ttable" width="50%" cellspacing="2" cellpadding="2" border="0">
			<tr class="center">
				<th class="blacklistTitle">Prefix</th>
				<th class="blacklistTitle">Username</th>
				<th class="blacklistTitle">Domain</th>
				<th class="blacklistTitle">Blacklisted ?</th>
				<th class="blacklistTitle">Edit</th>
				<th class="blacklistTitle">Delete</th>
			</tr>
			<?php
			$index_row = 0;
			$resultset = $link->query($sql_search);
			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}
			$data_no = $resultset->numRows();
			$resultset->free();
			$sql_search .= " LIMIT " . $config->results_per_page . " OFFSET " . (($page - 1) * $config->results_per_page);
			$resultset = $link->query($sql_search);

			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}

			if ( $data_no == 0 ) echo('<tr><td colspan="6" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
			else {
				$page=$_SESSION[$current_page];
				$page_no=ceil($data_no/$config->results_per_page);
				if ($page>$page_no) {
					$page=$page_no;
					$_SESSION[$current_page]=$page;
				}

				while($row = $resultset->fetchRow()){
					$index_row++;
					if ($index_row%2==1) $row_style="rowOdd";
					else $row_style="rowEven";

					$edit_link='<a href="'.$page_name.'?action=edit&id='.$row['id'].'"><img src="images/edit.gif" border="0"></a>';
					$delete_link='<a href="'.$page_name.'?action=delete&id='.$row['id'].'" onclick="return confirmDelete()" ><img src="images/trash.gif" border="0"></a>';
					?>
					<tr>
						<td class="searchRecord <?=$row_style?>"><?=$row['prefix']?></td>
						<td class="searchRecord <?=$row_style?>"><?=$row['username']?></td>
						<td class="searchRecord <?=$row_style?>"><?=$row['domain']?></td>
						<td class="searchRecord <?=$row_style?>"><?=$row['whitelist'] ? "No" : "Yes"?></td>
						<td class="searchRecord <?=$row_style?>" align="center"><?=$edit_link?></td>
						<td class="searchRecord <?=$row_style?>" align="center"><?=$delete_link?></td>
					</tr>
					<?php
				}
			}
			$resultset->free();

			?>
			<tr>
				<th colspan="6" class="blacklistTitle">
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
									for($i=$start_page;$i<=$end_page;$i++){
										if ($i==$page) echo('<font class="pageActive">'.$i.'</font>&nbsp;');
										else echo('<a href="'.$page_name.'?page='.$i.'" class="pageList">'.$i.'</a>&nbsp;');
									}
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
			<br>
		</table>
	</form>
	<br/>
	<?php
	if(isset($error) && !empty($error))
		echo "<font color='red'><b>" . $error . "</b></font>";
	if(isset($log) && !empty($log))
		echo "<font color='green'><b>" . $log . "</b></font>";
	?>
	<br>
