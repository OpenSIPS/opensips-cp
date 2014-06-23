<!--
 *
 * $Id: lists.edit.php 287 2011-10-17 09:41:35Z untiptun $
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

<form action="<?=$page_name?>?action=modify&carrierid=<?=$_GET['carrierid']?>" method="post">
<table width="465" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dataTitle">Edit Carrier #<?=$_GET['carrierid']?></td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>
<tr>
  <td rowspan="2" class="dataRecord" ><b>Gateway List</b></td>
   <td class="dataRecord">
            <input type="text"   name="gwlist" id="gwlist" value="<?=$resultset[0]['gwlist']?>" maxlength="255" readonly class="dataInput">
            <input type="button" name="clear_gwlist" value="Clear" class="formButton" style="width:50px;" onclick="clearObject('gwlist')"><br>
   </td>
  </tr>
  <tr>
        <td class="dataRecord">
            <input type="text"   name="weight" id="weight" value="" maxlength="5" class="dataInput" style="width:38;"> <?=print_gwlist()?>
            <input type="button" name="add_gwlist" value="Add" class="formButton" style="margin-left:2px;width:50px;" onclick="addElementToObject('gwlist','weight')">
        </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Use weights</b></td>
    <td class="dataRecord">
		<select id="useweights" name="useweights" class="dataSelect" style="width: 275px;">
			<option value="0" <?php if ($resultset[0]['useweights']==0) echo "selected";?>>0 - No</option>
     		<option value="1" <?php if ($resultset[0]['useweights']==1) echo "selected";?>>1 - Yes</option>
    	</select>	
	</td>
  </tr>
 <tr>
  <td class="dataRecord"><b>Use only first</b></td>
    <td class="dataRecord">
        <select id="useweights" name="useonlyfirst" class="dataSelect" style="width: 275px;">
            <option value="0" <?php if ($resultset[0]['useonlyfirst']==0) echo "selected";?>>0 - No</option>
            <option value="1" <?php if ($resultset[0]['useonlyfirst']==1) echo "selected";?>>1 - Yes</option>
        </select>   
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
		</select>
	</td>
 </tr>
<tr>
  <td class="dataRecord"><b>Attributes</b></td>
  <td class="dataRecord"><input type="text" name="attrs" value="<?=$resultset[0]['attrs']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description</b></td>
  <td class="dataRecord"><input type="text" name="description" value="<?=$resultset[0]['description']?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="edit" value="Save" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<br>
<?=$back_link?>
