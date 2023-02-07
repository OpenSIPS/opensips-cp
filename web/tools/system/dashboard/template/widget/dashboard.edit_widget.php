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

require("lib/".$page_id.".main.js");

if(!$_SESSION['read_only']){
	$colspan = 5;
}else{
	$colspan = 3;
}
echo '<h1>Edit widget</h1>'
?>
<form action="<?=$page_name?>?action=edit_widget_verify&panel_id=<?=$panel_id?>&widget_type=<?=$widget_content['widget_type']?>&widget_id=<?=$widget_id?>" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0" id="widget_table">
 <tr align="center">
  <td colspan="2" height="10" class="mainTitle">Edit Widget</td>
 </tr>
 <?php
  $widget_content['widget_type']::new_form($widget_content);
?>
 <tr id="buttons_row">
  <td colspan="2">
    <table cellspacing=20>
      <tr>
        <td class="dataRecord" align="right" width="50%"><input type="submit" name="editwidget" value="Edit" class="formButton"></td>
        <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
 </tr>
</table>
<script> form_init_status(); </script>
</form>
<form action="<?=$page_name?>?action=delete_widget&panel_id=<?=$panel_id?>&widget_id=<?=$widget_id?>" method="post">
<?php csrfguard_generate(); ?>
<input type="submit" name="delete" value="Delete" class="formButton" onclick="return confirmDelete('widget')">
</form>
