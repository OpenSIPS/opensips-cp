<form action="<?=$page_name?>?action=add_verify&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
<?
/*
* $Id$
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

$clone=$_GET['clone'];

if($clone =="1"){
	$id=$_GET['id'];

	$sql = "select * from ".$table." where id='".$id."'";
	$row = $link->queryAll($sql);
	if(PEAR::isError($row)) {
        	 die('Failed to issue query, error message : ' . $row->getMessage());
	}
	$rtpproxy_sock = $row[0]['rtpproxy_sock'];
	$set_id = $row[0]['set_id'];
}

?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="nathelperTitle">New RTPproxy</td>
 </tr>
<?php
?>
 <tr>
  <td class="dataRecord"><b>RTPproxy Sock:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="rtpproxy_sock" 
  value="<?=$rtpproxy_sock?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>SETID:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="set_id" 
  value="<?=$set_id?>" maxlength="128" class="dataInput"></td>
 </tr>
 
 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="add" value="Add" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>
