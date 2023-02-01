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
?>
<form action="<?=$page_name?>?action=add_verify" method="post">
<?php csrfguard_generate(); ?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" height="10" class="mainTitle">Add New Configuration</td>
 </tr>
  <?php 
form_generate_input_text("Configuration name", "Name of the configuration",
	"config_name", "n", "", 64, null);
form_generate_input_text("DB host", "Database host", "db_host", "n",
	"", 64, null);
form_generate_input_text("DB port", "Database port", "db_port", "y",
	"", 64, '^([0-9]\+)$');
form_generate_input_text("DB user", "Database user", "db_user", "n",
	"", 64, null);
form_generate_input_text("DB password", "Database password", "db_pass",
	"y", "", 64, null);
form_generate_input_text("DB name", "Database name", "db_name",
	"n", "", 64, null);
?>

 <tr>
  <td colspan="2">
    <table cellspacing=20>
      <tr>
        <td class="dataRecord" align="right" width="50%"><input type="submit" name="addadmin" value="Add" class="formButton"></td>
        <td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
      </tr>
    </table>
 </tr>
</table>
<script> form_init_status(); </script>
</form>
