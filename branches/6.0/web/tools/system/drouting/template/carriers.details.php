<!--
 *
 * $Id: lists.details.php 287 2011-10-17 09:41:35Z untiptun $
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

<table width="400" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td class="dataTitle">Detailed view for List #<?=$_GET['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>List ID:</b> <?=$resultset[0]['id']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b> <?=$resultset[0]['gwlist']?></td>
 </tr>

 <tr>
   <td class="dataRecord"><b>Use weights:</b> <?=$resultset[0]['useweights']?></td> 
 </tr>
 <tr>
    <td class="dataRecord"><b>Use only first:</b> <?=$resultset[0]['useonlyfirst']?></td>
 </tr>

 <tr>
     <td class="dataRecord"><b>Disabled:</b> <?=$resultset[0]['enabled']?></td>
 </tr>
 <tr>
  	<td class="dataRecord"><b>State</b> 
		<?php 
			switch ($resultset[0]['state']){
				case "1" : echo "1 - Active"; break;
				case "0" : echo "0 - Inactive"; break;
			}
		?>
	</td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Description:</b> <?=$resultset[0]['description']?></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Attributes:</b> <?=$resultset[0]['attrs']?></td>
 </tr>
 <tr>
   <td class="dataRecord"><b>Status:</b> <?=$resultset[0]['status']?></td>
  </tr>
 <tr height="10">
  <td class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
<br>
<?=$back_link?>
