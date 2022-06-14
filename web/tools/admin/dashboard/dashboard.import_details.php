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
	$widget_dir = $_GET['widget_dir'];
	require_once(__DIR__."/".$widget_dir."/def.php");
 ?>
	<table width="400" border="0">
		<tr>
			<td class="mainTitle">
					View widget
			</td>
		</tr>

		<tr>
			<td>

				<table class="ttable" width="100%" cellspacing="2" cellpadding="2" border="0"> <tr>
				<?php
                echo ('<td>'.$widget_def['info'].'</td>');
                ?>
				<td  rowspan=2>
                <img src="<?php echo $widget_dir ?>/example.jpg"></td>
                </tr><tr><?php  
                echo ('<td>'.$widget_def['description'].'</td>'); ?></tr></table>

			</td>
		</tr>

		<tr>
			<td align="center">
				<? print_back_input(); ?>
			</td>
		</tr>
	

	</table>

