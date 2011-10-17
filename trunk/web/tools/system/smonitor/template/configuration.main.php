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

?>

<form action="<?=$page_name?>" method="post" name="form">
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr>
  <td class="Title" colspan="2">&nbsp;</td>
 </tr>
 <tr>
  <td class="rowOdd">Sampling Time:</td>
  <td class="rowOdd"><input type="text" name="sampling_time" value="<?=$sampling_time?>" class="Input" <?php if (!$_read_only) echo("onClick=\"alert('Warning: changing Sampling Time will erase all statistics logs !')\"") ?>></td>
 </tr>
 <tr>
  <td class="rowOdd">Chart Size:</td>
  <td class="rowOdd"><input type="text" name="chart_size" value="<?=$chart_size?>" class="Input"></td>
 </tr>
 <tr>
  <td class="rowOdd">Chart History:</td>
  <td class="rowOdd">
   <input type="radio" name="chart_history" value="auto" <?php if ($chart_history=="auto") echo("checked") ?> onClick="document.form.chart_history_value.disabled=true">Auto Mode - 3 Days<br>
   <input type="radio" name="chart_history" value="manual" <?php if ($chart_history!="auto") echo("checked") ?> onClick="document.form.chart_history_value.disabled=false">Manual
   <select name="chart_history_value" class="Input" <?php if ($chart_history=="auto") echo("disabled") ?>>
    <?php
     for($i=1;$i<32;$i++)
     {
      if ($chart_history==$i) $xtra="selected";
       else $xtra="";
      echo('<option value="'.$i.'" '.$xtra.'>'.$i.' Day(s)</option>');
     }
    ?>
   </select>
  </td>
 </tr>
 <tr>
  <td class="rowOdd" colspan="2" align="center">
  <?php
   if ($_read_only) echo('<i>n/a</i>');
    else echo('<input type="submit" name="set" value="Apply Configuration" class="Button">');
  ?>
  </td>
 </tr>
 <tr>
  <td class="Title" colspan="2"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
