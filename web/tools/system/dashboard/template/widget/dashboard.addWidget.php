<?php
/*
 * * Copyright (C) 2011 OpenSIPS Project
 * *
 * * This file is part of opensips-cp, a free Web Control Panel Application for
 * * OpenSIPS SIP server.
 * *
 * * opensips-cp is free software; you can redistribute it and/or modify
 * * it under the terms of the GNU General Public License as published by
 * * the Free Software Foundation; either version 2 of the License, or
 * * (at your option) any later version.
 * *
 * * opensips-cp is distributed in the hope that it will be useful,
 * * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * * GNU General Public License for more details.
 * *
 * * You should have received a copy of the GNU General Public License
 * * along with this program; if not, write to the Free Software
 * * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * */

require_once("../../../common/forms.php");
if (!isset($_POST['type_val']) || !$_POST['type_val']) $widget_type = $widgets[0];
else $widget_type = $_POST['type_val'];

?>

<form action="<?=$page_name?>?action=add_widget&panel_id=<?=$panel_id?>" name="type_select" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0" name="type_select_table"> 
<tr align="center">
  <td colspan="2" height="10" class="mainTitle">Add New Widget</td>
 </tr>
 <?php
 $vals = array();
 $keys = array();
 foreach ( $widgets as $val ) {
	$vals[] = $val;
	$keys[] = $val::get_name();
  }
  echo ('<input type="hidden" name="type_val" class="formInput" method="post" value="">');
 form_generate_select("Widget type", "The type of widget you want to add",
 "type_list", 100, $widget_type, $vals, $keys);
 if (!is_null($widget_type::get_description()))
 	print_widget_description($widget_type::get_description());
?>
<script>
	type_select.type_list.onchange = function() {
		type_select.type_val.value=type_select.type_list.value;type_select.submit()
	}
</script>
</table>
</form>
<form action="<?=$page_name?>?action=add_widget_verify&panel_id=<?=$panel_id?>&widget_type=<?=$widget_type?>" name="add_widget_form" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0" id="widget_table">
 <?php
  $widget_type::new_form();
?>
 <tr id="buttons_row">
  <td colspan="2">
    <table cellspacing=20>
      <tr>
        <td class="dataRecord" align="right" width="50%"><input type="submit" name="addwidget" value="Add" class="formButton"></td>
        <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
 </tr>
</table>
<script> form_init_status(); </script>
</form>
