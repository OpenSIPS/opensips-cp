<!--
 *
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
 *
-->

<form action="<?=$page_name?>?action=modify&id=<?=$_GET['id']?>" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dataTitle">Edit Gateway #<?=$_GET['id']?></td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>
 <tr>
   <td class="dataRecord"><b>GWID</b></td>
   <td class="dataRecord" width="275"><input type="text" name="gwid" value="<?=$resultset[0]['gwid']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Type</b></td>
  <td class="dataRecord" width="275"><?=get_types("type",$resultset[0]['type'],275)?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Address</b></td>
  <td class="dataRecord" width="275"><input type="text" name="address" value="<?=$resultset[0]['address']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Strip</b></td>
  <td class="dataRecord"><input type="text" name="strip" value="<?=$resultset[0]['strip']?>" maxlength="11" class="dataInput"></td>
 </tr>
  <tr>
  <td class="dataRecord"><b>PRI Prefix</b></td>
  <td class="dataRecord"><input type="text" name="pri_prefix" value="<?=$resultset[0]['pri_prefix']?>" maxlength="16" class="dataInput"></td>
 </tr>
<tr>
  <td class="searchRecord"><b>Probe Mode</b></td>
  <td class="searchRecord" width="200">
    <select id="probe_mode" name="probe_mode" class="dataSelect" style="width: 275px;">
     <option value="0" <?php if ($resultset[0]['probe_mode']==0) echo "selected";?>>0 - Never</option>
     <option value="1" <?php if ($resultset[0]['probe_mode']==1) echo "selected";?>>1 - When disabled</option>
     <option value="2" <?php if ($resultset[0]['probe_mode']==2) echo "selected";?>>2 - Always</option>
    </select>
  </td>
 </tr>
<tr>
	<td class="dataRecord">
		<b>Socket</b>
	</td>
	<td class="dataRecord" width="275">
		<input type="text" name="socket" value="<?=$resultset[0]['socket']?>" maxlength="128" class="dataInput">
	</td>
</tr>

<tr>
	<td class="dataRecord">
		<b>DB State</b>
	</td>
	<td class="dataRecord" width="200">
		<select id="state" name="state" class="dataSelect" style="width: 275px;">
			<option value="0" <? if (isset($resultset[0]['state']) && $resultset[0]['state'] == 0) echo "selected"; ?>>0 - Active</option>
			<option value="1" <? if (isset($resultset[0]['state']) && $resultset[0]['state'] == 1) echo "selected"; ?>>1 - Inactive</option>
			<option value="2" <? if (isset($resultset[0]['state']) && $resultset[0]['state'] == 2) echo "selected"; ?>>2 - Probing</option>
		</select>
	</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Attributes</b></td>
  <td class="dataRecord"><input type="text" name="attrs" value="<? echo htmlspecialchars($resultset[0]['attrs']);?>" maxlength="16" class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Description</b></td>
  <td class="dataRecord"><input type="text" name="description" value="<? echo htmlspecialchars($resultset[0]['description']);?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="edit" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>
