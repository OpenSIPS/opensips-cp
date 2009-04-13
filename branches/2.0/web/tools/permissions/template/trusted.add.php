<form action="<?=$page_name?>?action=add_verify&clone=<?=$_GET['clone']?>&id=<?=$_GET['id']?>" method="post">
<?
/*
* $Id:$
* Copyright (C) 2008 Voice Sistem SRL
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

if($add_verify =="1"){
	db_connect();
	$id=$_GET['id'];

	$result=mysql_query("select * from ".$table." where id='".$id."'") or die(mysql_error());
	$row=mysql_fetch_array($result);
	db_close();
	$src_ip = $row['src_ip'];
	$proto = $row['proto'];
	$from_pattern =$row['from_pattern'];
	$tag =$row['tag'];
}

?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dispatcherTitle">New Trusted</td>
 </tr>
<?php
?>
 <tr>
  <td class="dataRecord"><b>Source IP:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="src_ip" 
  value="<?=$src_ip?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Protocol:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="proto" 
  value="<?=$proto?>" maxlength="128" class="dataInput"></td>
 </tr>
 
<tr>
  <td class="dataRecord"><b>From pattern:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="from_pattern" 
  value="<?=$from_pattern?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Tag:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="tag" 
  value="<?=$tag?>" maxlength="128" class="dataInput"></td>
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
