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
				<th class="blacklistTitle">Descripton</th>
				<th class="blacklistTitle">Blacklisted ?</th>
				<th class="blacklistTitle">Edit</th>
				<th class="blacklistTitle">Delete</th>
			</tr>
			<?php
			$index_row = 0;
			$sql = "SELECT * FROM globalblacklist";
			$resultset = $link->query($sql);
			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}
			$data_no = $resultset->numRows();
			$resultset->free();
			$sql .= " LIMIT " . $config->results_per_page . " OFFSET " . (($page - 1) * $config->results_per_page);
			$resultset = $link->query($sql);

			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}

			if ( $data_no == 0 ) echo('<tr><td colspan="5" class="rowEven" align="center"><br>'.$no_result.'<br><br></td></tr>');
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
						<td class="<?=$row_style?>"><?=$row['prefix']?></td>
						<td class="<?=$row_style?>"><?=$row['description']?></td>
						<td class="<?=$row_style?>"><?=$row['whitelist'] ? "No" : "Yes"?></td>
						<td class="<?=$row_style?>" align="center"><?=$edit_link?></td>
						<td class="<?=$row_style?>" align="center"><?=$delete_link?></td>
					</tr>
					<?php
				}
			}
			$resultset->free();

			?>
			<tr>
				<th colspan="5" class="blacklistTitle">
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
			<br>
		</table>
	</form>
	<?php
	if(isset($error) && !empty($error))
		echo "<font color='red'><b>" . $error . "</b></font>";
	if(isset($log) && !empty($log))
		echo "<font color='green'><b>" . $log . "</b></font>";
	?>
	<br>
