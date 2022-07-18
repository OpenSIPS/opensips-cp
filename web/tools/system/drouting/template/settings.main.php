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
?>

<div id="dialog" class="dialog" style="display:none"></div>
<div onclick="closeDialog();" id="overlay" style="display:none"></div>
<div id="content" style="display:none"></div>
<form action="<?=$page_name?>?action=gw_types" method="post">
<?php csrfguard_generate();
 $filename="../../../../config/tools/system/drouting/gw_types.txt";
 $handle=fopen($filename,"r");
 $content=fread($handle,filesize($filename));
 fclose($handle);
?>
<table width="300" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="mainTitle">Gateway Types File</td>
 </tr>
<?php
 if ($gw_error!="") {
                     echo(' <tr>');
                     echo('  <td class="searchRecord" align="center"><div class="formError">'.$gw_error.'</div></td>');
                     echo(' </tr>');
                    }
?>
 <tr>
  <td class="searchRecord" align="center">
   <textarea name="data" rows="5" class="searchInput"><?=$content?></textarea><br>
  </td>
 </tr>
 <?php
  if (!$_read_only) echo('<tr><td class="searchRecord" align="center"><input type="submit" name="save" value="Save Changes" class="searchButton"></td></tr>');
 ?>
</table>
</form>
<br>

<?php
if (get_settings_value("group_id_method")=="static")
{ 
?>
<form action="<?=$page_name?>?action=groups" method="post">
<?php csrfguard_generate();
 $filename="../../../../config/tools/system/drouting/group_ids.txt";
 $handle=fopen($filename,"r");
 $content=fread($handle,filesize($filename));
 fclose($handle);
?>
<table width="300" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="mainTitle">Group IDs File</td>
 </tr>
<?php
 if ($groups_error!="") {
                         echo(' <tr>');
                         echo('  <td class="searchRecord" align="center"><div class="formError">'.$groups_error.'</div></td>');
                         echo(' </tr>');
                        }
?>
 <tr>
  <td class="searchRecord" align="center">
   <textarea name="data" rows="5" class="searchInput"><?=$content?></textarea><br>
  </td>
 </tr>
 <?php
  if (!$_read_only) echo('<tr><td class="searchRecord" align="center"><input type="submit" name="save" value="Save Changes" class="searchButton"></td></tr>');
 ?>
</table>
</form>
<?php
}
?>
