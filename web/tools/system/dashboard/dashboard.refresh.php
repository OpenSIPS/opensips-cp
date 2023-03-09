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
session_start();
require("../../../common/cfg_comm.php");
get_priv("dashboard");
require("../../../../config/db.inc.php");
require("../../../../config/tools/system/dashboard/db.inc.php");
require("../../../../config/tools/system/dashboard/settings.inc.php");
include("lib/db_connect.php");
session_load_from_tool("dashboard");
$table=get_settings_value_from_tool("custom_table", "dashboard");
$widget_id = $_GET['id'];
/* split to get the panel id */
$panel_id = explode("_", $widget_id)[1];

$sql = 'SELECT content FROM '.$table.' WHERE id = ? ';
$stm = $link->prepare($sql);
if ($stm->execute(array($panel_id)) == false) {
	error_log("could not execute $sql: ".print_r($link->errorInfo(), true));
	return;
}

$resultset = $stm->fetchAll(PDO::FETCH_ASSOC)[0];
$widgets = json_decode($resultset["content"], true);
if (!isset($widgets[$widget_id])) {
	error_log("unknown widget $widget_id");
	return;
}
load_widgets();
$widget = json_decode($widgets[$widget_id], true);
$widget_type = $widget['widget_type'];
if (!class_exists($widget['widget_type']))
	return;
$new_widget = new $widget['widget_type']($widget);
$new_widget->set_id($widget['widget_id']);

$data = $new_widget->get_data();
if ($data === false) {
  echo($new_widget->get_status()."\n");
  $new_widget->echo_content();
} else {
  $response = array("status"=>$new_widget->get_status(), "data"=>$data);
  echo(json_encode($response));
}
// vim:set sw=2 ts=2 et ft=php fdm=marker:
?>
