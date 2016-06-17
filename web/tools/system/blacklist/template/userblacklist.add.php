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
		<form action="<?=$page_name?>?action=add_verify" method="post">
			<table width="50%" cellspacing="2" cellpadding="2" border="0">
				<tr align="center">
					<td colspan="2" height="10" class="blacklistTitle">Add a user-specific entry</td>
				</tr>

				<tr height="10">
					<td class="searchRecord" align="right">Prefix / number</td>
					<td class="searchRecord" align="left"><input name="prefix" class="searchInput" type="text" maxlength="64" ></td>
				</tr>

				<tr height="10">
					<td class="searchRecord" align="right">Username</td>
					<td class="searchRecord" align="left"><input name="username" class="searchInput" type="text" maxlength="64" ></td>
				</tr>

				<tr height="10">
					<td class="searchRecord" align="right">Domain</td>
					<td class="searchRecord" align="left"><input name="domain" class="searchInput" value="<?=$entry['domain']?>" type="text" maxlength="64" /></td>
				</tr>

				<tr height="10">
					<td colspan="2" class="searchRecord" align="center"><input name="whitelisted" id="whitelisted" type="checkbox"> <label for="whitelisted">Whitelisted</label></td>
				</tr>

				<?php
				if(!$_SESSION['read_only']){
					?>
					<tr height="10">
						<td colspan="2" align="center" class="blacklistTitle" ><input type="submit" class="searchButton" onclick="return confirmAdd()"></td>
					</tr>
					<?php
				}
				?>
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
