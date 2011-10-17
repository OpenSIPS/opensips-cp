<form action="<?=$page_name?>?action=add_verify" method="post">
<?
/*
* $Id: tviewer.add.php 133 2009-10-29 18:05:56Z iulia_bublea $
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


?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="tviewerTitle">New Record</td>
 </tr>
<?php
 foreach ($config->custom_table_columns as $key => $value){ 
?>
 <tr>
  <td class="dataRecord"><b><?php echo $key; ?></b></td>
  <td class="dataRecord" width="275"><input type="text" name="<?php echo $value; ?>" 
  value="" maxlength="128" class="dataInput"></td>
  </tr>
<?php } ?>

 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="add" value="Add" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<?=$back_link?>
