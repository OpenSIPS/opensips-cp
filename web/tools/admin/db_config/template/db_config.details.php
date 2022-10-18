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

require(__DIR__."/../../../../../config/db.inc.php");
require(__DIR__."/../../../../../config/tools/admin/db_config/settings.inc.php");
if ($id == 0) {
	$resultset = array();
	$resultset[0]["db_host"] = $config->db_host;
	$resultset[0]["db_port"] = $config->db_port;
	$resultset[0]["db_user"] = $config->db_user;
	$resultset[0]["db_name"] = $config->db_name;
	$resultset[0]["db_pass"] = $config->db_pass;
} else {
	$sql = "select * from ".$table." where id=?";
	$stm = $link->prepare($sql);
	if ($stm->execute(array($id)) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
}
?>
	<table width="400" border="0">
		<tr>
			<td class="mainTitle">
					Configuration details
			</td>
		</tr>

		<tr>
			<td>

				<table class="ttable" width="100%" cellspacing="2" cellpadding="2" border="0">
				<?php

				$i = 0;
				foreach ($resultset[0] as $key => $value) {
					if ($key != "id") {
						$row_style = ($i%2 == 1)?"rowOdd":"rowEven";
						$i++;
						?>
						<tr>
						<td class="<?=$row_style?>">
							<b><?=$key?></b>
						</td>
						<td class="<?=$row_style?>">
								<?php
									echo ($value);
								?>
						</td>
					</tr>
				<?php 
					}
				} 
				?>
				</table>

			</td>
		</tr>

		<tr>
			<td align="center">
				<? print_back_input(); ?>
			</td>
		</tr>
	

	</table>

