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

require_once("../../../../config/tools/system/cdrviewer/db.inc.php");
require_once("../../../../config/session.inc.php");
require_once("../../../common/cfg_comm.php");
require_once("lib/functions.inc.php");
session_load();
include("lib/db_connect.php");
$table=get_settings_value("cdr_table");

$sql = "SELECT * FROM ".$table." WHERE ".get_settings_value('cdr_id_field_name')."=?";
$stm = $link->prepare($sql);
if ($stm === false) {
        die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
}
$stm->execute( array($_GET['cdr_id']) );
$row = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
?>
	
<html>

<head>
 <title>CDR Details: #<?=$_GET['cdr_id']?></title>
 <link href="../../../style_tools.css" type="text/css" rel="StyleSheet">
</head>

<body bgcolor="#e9ecef">
<center>
<table width="480" cellpadding="5" cellspacing="5" border="0" align="center">
<?php
$k=0;
foreach($row as $key=>$value) {
 if ( $k%2 == 0 ) $row_style="rowOdd";
 else $row_style="rowEven";
	
?>
 <tr><td class="<?php print $row_style?>"><b><?php print "$key:"?> </b><?php print "$value"?></td></tr>
<?php
$k++;
}
?>	
</table>
</center>
</body>

</html>
