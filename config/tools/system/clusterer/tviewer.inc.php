<?php
/*
 * Copyright (C) 2016 OpenSIPS Project
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

$module_id = "clusterer";
$custom_config[$module_id] = array();
$custom_config[$module_id]['custom_name'] = "Clusterer";

/*
 * Renders the "state" column of the Cluster Nodes tab as a clickable, colour
 * coded Active/Inactive icon. Clicking it fires the "change_state" action which
 * is handled server side by custom_actions/nodes_hooks.php. Read-only users get
 * a non-clickable status icon.
 */
if (!function_exists("clusterer_state_wrapper")) {
	function clusterer_state_wrapper($key, $text, $row) {
		$active = ($text == "1");
		$state  = $active ? "Active" : "Inactive";
		$img    = ($active ? "active" : "inactive").".png";
		$imgtag = '<img src="../../../images/share/'.$img.'" alt="'.$state.'" border="0">';

		if ($_SESSION['read_only'])
			return $imgtag;

		$other = $active ? "Inactive" : "Active";
		return '<a href="tviewer.php?action=change_state&submenu_item_id=0&id='.$row['id'].'&state='.$text.'"'
			. ' onclick="return confirm(\'Switch node to '.$other.'?\')">'.$imgtag.'</a>';
	}
}

######################
# submenu / tabs     #
######################

$custom_config[$module_id]['submenu_items'] = array(
	0 => "Cluster Nodes"
);

##############################################
############ TAB 0 - CLUSTER NODES ###########
##############################################

$custom_config[$module_id][0]['custom_table'] = get_settings_value("table_clusterer");
$custom_config[$module_id][0]['custom_table_primary_key'] = "id";
$custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
$custom_config[$module_id][0]['per_page'] = get_settings_value("per_page");
$custom_config[$module_id][0]['page_range'] = get_settings_value("page_range");

$custom_config[$module_id][0]['custom_table_column_defs'] = array (
		"id" 		=> 	array (
				"header" 			=> "ID",
				"type"				=> "text",
				"key"				=> "PRI",
				"show_in_add_form" 	=> false,
				"show_in_edit_form"	=> false,
				"searchable" 		=> false,
				"visible"			=> false
		),
		"cluster_id" 	=> 	array (
				"header" 			=> "Cluster ID",
				"type"				=> "text",
				"key"				=> "MUL",
				"tip"				=> "The numerical ID of the cluster for adding the new node",
				"validation_regex" 	=> "^[0-9]+$",
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> true,
				"is_optional"		=> "n"
		),
		"node_id" 	=> 	array (
				"header" 			=> "Node ID",
				"type"				=> "text",
				"key"				=> "MUL",
				"tip"				=> "The numerical ID of the server/node inside the cluster (note that this ID must be unique across all the clusters the node belongs to)",
				"validation_regex" 	=> "^[0-9]+$",
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> false,
				"is_optional"		=> "n"
		),
		"url" 	=> 	array (
				"header" 			=> "BIN URL",
				"type"				=> "text",
				"key"				=> NULL,
				"tip"				=> "The Binary INterface URL for reaching the node (like bin:ip:port)",
				"validation_regex" 	=> "^bin:[^:]+(:[0-9]+)$",
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> true,
				"is_optional"		=> "n"
		),
		"no_ping_retries" 	=> 	array (
				"header" 			=> "Max retries",
				"type"				=> "text",
				"key"				=> NULL,
				"tip"				=> "Maximum number of probes/retries before marking other nodes as unreachable",
				"validation_regex" 	=> "^[0-9]+$",
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> false,
				"is_optional"		=> "n"
		),
		"sip_addr" 	=> 	array (
				"header" 			=> "SIP address",
				"type"				=> "text",
				"key"				=> NULL,
				"tip"				=> "An IP address where this node is receiving the SIP traffic (for certain scenarios, like Federated User Location)",
				"validation_regex" 	=> "^([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})$",
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> false,
				"is_optional"		=> "y"
		),
		"flags" 	=> 	array (
				"header" 			=> "Flags",
				"type"				=> "text",
				"key"				=> NULL,
				"tip"				=> "Comma separated list of text flags required by modules using the clusterer engine. The only one supported right now is 'seed'.",
				"validation_regex" 	=> "^seed$",
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> false,
				"is_optional"		=> "y"
		),
		"description" 	=> 	array (
				"header" 			=> "Description",
				"type"				=> "text",
				"key"				=> NULL,
				"tip"				=> "Description in DB, not used by OpenSIPS",
				"validation_regex" 	=> NULL,
				"show_in_add_form" 	=> true,
				"show_in_edit_form"	=> true,
				"searchable" 		=> false,
				"is_optional"		=> "y"
		),
		"state" 	=> 	array (
				"header" 			=> "State",
				"type"				=> "text",
				"key"				=> NULL,
				"show_in_add_form" 	=> false,
				"show_in_edit_form"	=> false,
				"searchable" 		=> false,
				"visible"			=> true,
				"value_wrapper_func" => "clusterer_state_wrapper"
		)
);

// need to reload 0 or 1
$custom_config[$module_id][0]['reload'] = 1;
// MI command ran by the "Reload on Server" button
$custom_config[$module_id][0]['custom_mi_command'] = "clusterer:reload";
// the system ID to send the reload MI command to
$talk_to_this_assoc_id = get_settings_value("talk_to_this_assoc_id");

// custom search + one-click state toggle handling (see nodes_hooks.php)
$custom_config[$module_id][0]['custom_search'] = array (
		"enabled" => true,
		"action_script" => "../../../tools/system/clusterer/custom_actions/nodes_hooks.php"
);

$custom_config[$module_id][0]['custom_action_columns'] = array (
		"0" 	=> 	array(
					"header" 			=> "Edit",
					"show_header" 		=> false,
					"type"				=> "link",
					"action" 			=> "edit",
					"icon"				=> "../../../images/share/edit.png",
					"action_script" 	=> "custom_actions/edit.php"
				),
		"1" 	=> 	array(
					"header" 			=> "Delete",
					"show_header" 		=> false,
					"type"				=> "link",
					"action" 			=> "delete",
					"icon"				=> "../../../images/share/delete.png",
					"action_script" 	=> "custom_actions/delete.php",
					"events"			=> "onclick=\"return confirmDelete()\""
				)
);

$custom_config[$module_id][0]['custom_action_buttons'] = array (
		"0"		=>	array(
					"text" 				=> "Add",
					"action" 			=> "add",
					"style"				=> "formButton",
					"action_script"		=> "custom_actions/add.php"
				)
);

##############################################
########### TAB 1 - CLUSTER BRIDGES ##########
##############################################

if (get_settings_value("bridge_enabled")) {

	$custom_config[$module_id]['submenu_items'][1] = "Cluster Bridges";

	$custom_config[$module_id][1]['custom_table'] = get_settings_value("table_bridge");
	$custom_config[$module_id][1]['custom_table_primary_key'] = "id";
	$custom_config[$module_id][1]['custom_table_order_by'] = $custom_config[$module_id][1]['custom_table_primary_key'];
	$custom_config[$module_id][1]['per_page'] = get_settings_value("per_page");
	$custom_config[$module_id][1]['page_range'] = get_settings_value("page_range");

	$custom_config[$module_id][1]['custom_table_column_defs'] = array (
			"id" 		=> 	array (
					"header" 			=> "ID",
					"type"				=> "text",
					"key"				=> "PRI",
					"show_in_add_form" 	=> false,
					"show_in_edit_form"	=> false,
					"searchable" 		=> false,
					"visible"			=> false
			),
			"cluster_a" 	=> 	array (
					"header" 			=> "Source cluster",
					"type"				=> "text",
					"key"				=> "MUL",
					"tip"				=> "The ID of the source cluster of this replication link.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n"
			),
			"cluster_b" 	=> 	array (
					"header" 			=> "Destination cluster",
					"type"				=> "text",
					"key"				=> "MUL",
					"tip"				=> "The ID of the destination cluster of this replication link.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n"
			),
			"send_shtag" 	=> 	array (
					"header" 			=> "Send sharing tag",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "The sharing tag designating the node responsible for sending over this bridge.",
					"validation_regex" 	=> "^.{1,32}$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"is_optional"		=> "n"
			),
			"dst_node_csv" 	=> 	array (
					"header" 			=> "Destination nodes (CSV)",
					"type"				=> "textarea",
					"key"				=> NULL,
					"tip"				=> "Comma separated list of destination BIN sockets to try, in failover mode.",
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"is_optional"		=> "y"
			)
	);

	$custom_config[$module_id][1]['reload'] = 1;
	$custom_config[$module_id][1]['custom_mi_command'] = "clusterer:reload";

	$custom_config[$module_id][1]['custom_search'] = array (
			"enabled" => true,
			"action_script" => "custom_actions/search.php"
	);

	$custom_config[$module_id][1]['custom_action_columns'] = array (
			"0" 	=> 	array(
						"header" 			=> "Edit",
						"show_header" 		=> false,
						"type"				=> "link",
						"action" 			=> "edit",
						"icon"				=> "../../../images/share/edit.png",
						"action_script" 	=> "custom_actions/edit.php"
					),
			"1" 	=> 	array(
						"header" 			=> "Delete",
						"show_header" 		=> false,
						"type"				=> "link",
						"action" 			=> "delete",
						"icon"				=> "../../../images/share/delete.png",
						"action_script" 	=> "custom_actions/delete.php",
						"events"			=> "onclick=\"return confirmDelete()\""
					)
	);

	$custom_config[$module_id][1]['custom_action_buttons'] = array (
			"0"		=>	array(
						"text" 				=> "Add",
						"action" 			=> "add",
						"style"				=> "formButton",
						"action_script"		=> "custom_actions/add.php"
					)
	);
}
?>
