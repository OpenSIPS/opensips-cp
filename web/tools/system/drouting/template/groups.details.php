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

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="mainTitle">Detailed view for '<?=$_GET['id']?>'</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Username:</b> <?=$resultset[0]['username']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Domain:</b> <?=$resultset[0]['domain']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Group ID:</b> <?=$resultset[0]['groupid']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
</table>
<br>
<?php print_back_button(); ?>
