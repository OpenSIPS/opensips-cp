<!--
 *
 * $Id$
 * Copyright (C) 2008-2010 Voice Sistem SRL
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

<?php
 if ($resultset[0]['gwlist']=="") $gwlist='<img src="images/inactive.gif" alt="No GW List">';
  else if ( preg_match('/[#][0-9]+/',$resultset[0]['gwlist'])) $gwlist=parse_list($resultset[0]['gwlist']); 
  else $gwlist=parse_gwlist($resultset[0]['gwlist']);
?>
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for Rule #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Rule ID:</b> <?=$resultset[0]['ruleid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> 
  <?php 
   if ($config->group_id_method=="static") get_groups($resultset[0]['groupid']);
   if ($config->group_id_method=="dynamic") echo($resultset[0]['groupid']);
  ?>
  </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Prefix:</b> <?=$resultset[0]['prefix']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Time Recurrence:</b> <?=parse_timerec($resultset[0]['timerec'],1)?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Priority:</b> <?=$resultset[0]['priority']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Route ID:</b> <?=$resultset[0]['routeid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$gwlist?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Attributes:</b> <?=$resultset[0]['attrs']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
 <tr height="10">
  <td class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?=$back_link?>
