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
 
<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for Gateway #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway ID:</b> <?=$resultset[0]['gwid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Type:</b> <?=get_type($resultset[0]['type'])?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Address:</b> <?=$resultset[0]['address']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Strip:</b> <?=$resultset[0]['strip']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>PRI Prefix:</b> <?=$resultset[0]['pri_prefix']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
 <tr height="10">
  <td class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?php
 if (strpos($_SERVER['HTTP_REFERER'],"rules.php")!==false)
  echo('<a href="rules.php" class="backLink">Go Back</a>&nbsp;|&nbsp;');
?>
<?=$back_link?>
