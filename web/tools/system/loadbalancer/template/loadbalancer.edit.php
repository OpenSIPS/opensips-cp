<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
<?php
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

 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
	$id=$_GET['id'];
	
	$sql = "select * from ".$table." where id='".$id."'";
	$row = $link->queryAll($sql);
	if(PEAR::isError($row)) {
        	 die('Failed to issue query, error message : ' . $row->getMessage());
	}
	$index_row=0;
if ($row[0]['probe_mode']==0) $probe = "No probing";
else if ($row[0]['probe_mode']==1) $probe = "Probing only when the destination is in disabled mode";
else $probe = "Probing all the time";
?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="loadbalancerTitle">Edit Load Balancer Definition</td>
 </tr>
<?php
?>

 <tr>
  <td class="dataRecord"><b>Group ID:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="group_id" value="<?=$row[0]['group_id']?>" maxlength="128" class="dataInput"></td>
  </tr>

 <tr>
  <td class="dataRecord"><b>Destination URI:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="dst_uri" value="<?=$row[0]['dst_uri']?>" maxlength="128" class="dataInput"></td>
 </tr>
 
 <tr>
  <td class="dataRecord"><b>Resources:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="resources" value="<?=$row[0]['resources']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Probe Mode:</b></td>
  <td class="dataRecord" width="275"><?=get_types("probe_mode",$probe)?></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Description:</b></td>
  <td class="dataRecord" width="275"><input type="text" name="description" value="<?=$row[0]['description']?>" maxlength="128" class="dataInput"></td>
 </tr>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="save" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="../../../images/share/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>

