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

require_once("../../../../config/tools/system/siptrace/db.inc.php");
require_once("../../../../config/tools/system/siptrace/local.inc.php");
require_once("lib/functions.inc.php");
include("lib/db_connect.php");
$table=$config->table_trace;
$sql = "SELECT * FROM ".$table." WHERE id='".$_GET['traceid']."'";
$row = $link->queryAll($sql);
if(PEAR::isError($row)) {
	die('Failed to issue query, error message : ' . $row->getMessage());
}
$message=htmlspecialchars(trim($row[0]['msg']));
// from highlight
$message=str_replace("From:","<span style='background-color:".$config->from_bgcolor."'><font color='".$config->from_color."'>From:",$message);
$message=substr_replace($message,"</font></span>\n",strpos($message,"\n",strpos($message,"From:")),1);
// to highlight
$message=str_replace("To:","<span style='background-color:".$config->to_bgcolor."'><font color='".$config->to_color."'>To:",$message);
$message=substr_replace($message,"</font></span>\n",strpos($message,"\n",strpos($message,"To:")),1);
// call-id highlight
$message=str_replace("Call-ID:","<span style='background-color:".$config->callid_bgcolor."'><font color='".$config->callid_color."'>Call-ID:",$message);
$message=substr_replace($message,"</font></span>\n",strpos($message,"\n",strpos($message,"Call-ID:")),1);
// cseq highlight
$message=str_replace("CSeq:","<span style='background-color:".$config->cseq_bgcolor."'><font color='".$config->cseq_color."'>CSeq:",$message);
$message=substr_replace($message,"</font></span>\n",strpos($message,"\n",strpos($message,"CSeq:")),1);
// regexp highlight
$regexp=trim($_GET['regexp']);
if ($regexp!="")
$message=preg_replace('/'.$regexp.'/i', "<span style='background-color:".$config->regexp_bgcolor."'><font color='".$config->regexp_color."'><b>$0</b></font></span>",$message);
$message=str_replace("\n","<br>",$message);
?>

<html>

<head>
 <title>SIP Trace Details: #<?=$_GET['traceid']?></title>
 <link href="style/style.css" type="text/css" rel="StyleSheet">
</head>

<body bgcolor="#e9ecef">
<center>
<table width="480" cellpadding="5" cellspacing="5" border="1">
 <tr>
  <td><?=$message?></td>
 </tr>
</table>
</body>

</html>
