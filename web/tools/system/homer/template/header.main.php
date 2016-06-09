<?php
/*
* Copyright (C) 2016 OpenSIPS Project
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

<html>

<head>
 <link href="style/style.css" type="text/css" rel="StyleSheet">
</head>

<body bgcolor="#e9ecef">
<center>
<table width="705" cellpadding="2" cellspacing="2" border="0">
	<tr  valign="center" height="20">
		<td style="color:#0969b5">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr valign="center">
					<td align="left">
						<form action="<?=$page_name?>?action=Hjump" method="post">
							<input type="submit" name="action" value="Open in new window" class="searchButton">
						</form>
					</td>
					<td align="right">
	          			<b><?php print "System / Homer / ".$_SESSION['permission'];?></b>
					</td>
				<tr>
			</table>
		</td>
	</tr>
</table>
</center>
</body>

</html>
<?php
$_SESSION['user_active_tool']="homer";
$_SESSION['user_active_page']=$page_name;
?>
