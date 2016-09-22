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

	$id=$_GET['id'];
	
	$sql = "select * from ".$table." where id='".$id."'";
	$index_row=0;
	$resultset = $link->queryAll($sql);
	$link->disconnect();
?>
<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>

<?php
require("lib/".$page_id.".main.js");
?>

<form name="cl_edit" action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" onsubmit="return validateFormCLAdd('cl_edit','cle_cid','cle_sid','cle_url')" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="clustererTitle">Edit CLuster Node Definition</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Cluster ID:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="cle_cid" 
  value="<?=$resultset[0]['cluster_id']?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Server ID:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="cle_sid" 
  value="<?=$resultset[0]['machine_id']?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>URL:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="cle_url" 
  value="<?=$resultset[0]['url']?>"maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Description:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="cle_description" 
  value="<?=$resultset[0]['description']?>"maxlength="128" class="dataInput"></td>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

