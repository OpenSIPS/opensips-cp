<?php
/*
 * Copyright (C) 2014 OpenSIPS Project
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

###############################################################################
# Attention : advanced options !!

 //this is a very important parameter
 $module_id = "tcp_mgm";
 $custom_config[$module_id] = array ();

 // a custom global name for the tool
 $custom_config[$module_id]['custom_name'] = "TCP Management";
 

//if you want submenu (horizontal) items add them here:

// $custom_config[$module_id]['submenu_items'] = array(
// 					"0"	=> "Submenu1",
//	 				"1"	=> "Submenu2"
//				);




/* config for each submenu item */

/*
Example table: table1
+---------------+------------------+------+-----+---------+----------------+
| Field         | Type             | Null | Key | Default | Extra          |
+---------------+------------------+------+-----+---------+----------------+
| id            | int(10) unsigned | NO   | PRI | NULL    | auto_increment | 
| name          | char(128)        | NO   | UNI |         |                | 
| address       | char(128)        | NO   |     |         |                | 
| age           | int(10) unsigned | NO   |     | 0       |                | 
| married       | int(10) unsigned | NO   |     | 0       |                | 
+---------------+------------------+------+-----+---------+----------------+

####################################################################################
#																				   #
# Uncomment bellow this line - follow the example and adapt it to your DB table    #
#																				   #
####################################################################################
*/

$custom_config[$module_id][0]['custom_table'] = "tcp_mgm";
$custom_config[$module_id][0]['custom_table_primary_key'] = "id";
$custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
$custom_config[$module_id][0]['per_page'] = 50;
$custom_config[$module_id][0]['page_range'] = 3;

/*
 Columns definition:
	- header - the header title used to label this column (mandatory)
	- type - how to display/handle the data - text / combo / textarea (mandatory)
	- key - the key restrictions for this column (they will be checked prior to DB op) - PRI/UNI (optional)
	- tip - tip/explanation to be attached to this column in the add/edit forms (optional)
	- validation_regex - a regular expression to check the inserted value; only for text type (optional)
	- is_optional - if a value must be provided or not for the column (mandatory)

	- show_in_add_form - if to be shown in the add form (optional)
	- show_in_edit_form - if to be shown in the edit form (optional)
	- searchable - if to be listed as field in the search form (optional)
	- disabled - if the disabled attribute should be set to this column (optional)
	- readonly - if the readonly attribute should be set to this column (optional)
	- visible - if the column should be displayed in the table (optional)
	- value_wrapper_func - php function like func($key, $text, $link) to return 
		a custom string to be displayed instead of the real value; parameters are:
		- $key - the name of the column
		- $text - the value of the column
		- $link - an assoc array with all the values of the DB row( i.e $text = $link[$key] )
		This function can be use to build links or images around the values to be displayed (optional).

	- default_value - optional default value to pre-populate the column in the add form
	- default_display - a default display text corresponding to the default value if the type is COMBO
	- combo_default_values - assoc array (value -> display) for definiting static COMBOs
		Example: array("1"=>"Yes","0"=>"No")
	- combo_table, combo_value_col, combo_display_col, combo_hook_col - set of attributes for
		defininig dynamic combos, with data from DB; table and value are mandatory in this
		configuration; display may be optionaly used; similar the hook - this has no impact
		over the display of the combo; it simply creates a "hook" attribute inside the
		"option" html tag, which may be used by JS function to enable/disable/remove 
		the value during runtime.
	
	- events - a string that optionaly may define JS events for the input/select field
		Example: "onChange=\"runJSfunction();\" "
 
	- textarea_display_size - optional value used when a textarea_label_column is not specified, and
		 indicates how many characters should be shown from the text area content. Default is 50.
*/

 
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
			"proto"	=> 	array (
					"header" 			=> "Protocol",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Restrict this profile to a specific OpenSIPS supported protocol",
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n",
					"combo_default_values" => array("any"=>"any","tcp"=>"SIP TCP","tls"=>"SIP TLS", "bin"=>"BIN", "bins"=>"BIN TLS", "hep_tcp"=>"HEP TCP", "msrp"=>"MSRP", "smpp"=>"SMPP","ws"=>"SIP WS", "wss"=>"SIP WSS")
			), 
			"remote_addr" 	=> 	array (
					"header" 			=> "Remote Address",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Remote network address in \"ip[/prefix_length]\" format, or the special value of \"any\", which will match any remote IPv4 or IPv6 address.",
					"validation_regex" 	=> "^((any)|([0-9.:a-fA-F]{7,}(\/[0-9]{1,2})?))$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n",
					"default_value" 	=> "any"
			),
			"remote_port" 	=> 	array (
					"header" 			=> "Remote port",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Remote network port. A value of 0 will match any remote port.",
					"validation_regex" 	=> "^[0-9]{1,5}$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n",
					"default_value" 	=> "0"
			),
			"local_addr" 	=> 	array (
					"header" 			=> "Local Address",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Local network address in \"ip[/prefix_length]\" format, or the special value of \"any\", which will match any of the OpenSIPS network listeners.",
					"validation_regex" 	=> "^((any)|([0-9.:a-fA-F]{7,}(\/[0-9]{1,2})?))$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n",
					"default_value" 	=> "any"
			),
			"local_port" 	=> 	array (
					"header" 			=> "Local port",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Local network port.  A value of 0 will match any OpenSIPS listening port.",
					"validation_regex" 	=> "^[0-9]{1,5}$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"is_optional"		=> "n",
					"default_value" 	=> "0"
			),
			"priority" 	=> 	array (
					"header" 			=> "Priority",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "By default, higher network prefix lengths will match first.  The priority field can be used to override this behavior, with lower priority rules being attempted first.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "0"
			),
			"attrs" 	=> 	array (
					"header" 			=> "Attributes",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "A URI params like string with various TCP-connection related flags or properties pertaining to specific OpenSIPS modules or areas of code.",
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "y",
					"default_value" 	=> ""
			),
			"connect_timeout" 	=> 	array (
					"header" 			=> "Connect Timeout",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Time in milliseconds before an ongoing blocking TCP connect attempt is aborted.  Default: 100 ms.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "100"
			),
			"con_lifetime" 	=> 	array (
					"header" 			=> "Connection Lifetime",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Time in seconds with no READ or WRITE events on a TCP connection before it is destroyed.  Default: 120 s.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "120"
			),
			"msg_read_timeout" 	=> 	array (
					"header" 			=> "Message Read Timeout",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "The maximum number of seconds that a complete SIP message is expected to arrive via TCP. Default: 4 s.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "4"
			),
			"send_threshold" 	=> 	array (
					"header" 			=> "Send Threshold",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "The maximum number of microseconds that sending a TCP request can take.  Send latencies above this threshold will trigger a logging warning.  A value of 0 disables the check.  Default: 0 us.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "0"
			),
			"no_new_conn" 	=> 	array (
					"header" 			=> "Do not Connect",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Set this to YES in order to instruct OpenSIPS to never open connections to the \"remote\" side.  This may be useful when there is a NAT firewall in-between, so only remote->local connections are possible.  Default: NO. ",
					"combo_default_values" => array("0"=>"No","1"=>"Yes"),
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
			),
			"alias_mode" 	=> 	array (
					"header" 			=> "TCP alias mode",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Controls TCP connection reusage for requests in the opposite direction to the original one.  NEVER (never reuse), VIA REQUESTED (only reuse if RFC 5923 Via \";alias\" is present), ALWAYS (always reuse).  Default: NEVER.",
					"combo_default_values" => array("0"=>"Never","1"=>"Via requested", "2"=>"Always"),
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
			),
			"parallel_read" 	=> 	array (
					"header" 			=> "Parallel reading mode",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Set to RE-BALANCE to re-balance a TCP connection for reading after a worker processes one packet.  Set to PARALLEL in order to have proto modules re-balance a TCP conn for reading before processing a fully read packet, but only if they have support for this (e.g. proto_tcp).  Default: NONE (lock a connection in a TCP reader process for \"tcp_con_lifetime\" seconds at a time).",
					"combo_default_values" => array("0"=>"None","1"=>"Re-balance", "2"=>"Parallel "),
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
			),
			"keepalive" 	=> 	array (
					"header" 			=> "TCP Keepalive",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Set to DISABLED in order to disable TCP keepalives at Operating System level.  Default is ENABLED.",
					"combo_default_values" => array("1"=>"Enabled","0"=>"Disabled"),
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
			),
			"keepcount" 	=> 	array (
					"header" 			=> "Keepalice Count",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Number of keepalives to send before closing the connection.  Default: 9.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "9"
			),
			"keepidle" 	=> 	array (
					"header" 			=> "Keepalive Idle Time",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Amount of time, in seconds, before OpenSIPS will start to send keepalives if the connection is idle.  Default: 7200. ",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "7200"
			),
			"keepinterval" 	=> 	array (
					"header" 			=> "Keepalive Interval",
					"type"				=> "text",
					"key"				=> NULL,
					"tip"				=> "Interval in seconds between successive (failed) keepalive probes. Default: 75.",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible"	 		=> false,
					"is_optional"		=> "n",
					"default_value" 	=> "75"
			),
	);



 //need to reload 0 or 1
 $custom_config[$module_id][0]['reload'] = 1;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][0]['custom_mi_command'] = "tcp_reload";
 
 //the system ID to send the reload MI command to
 $talk_to_this_assoc_id = 1;


##############################################
######### CUSTOM SEARCH OPTIONS ##############
##############################################
$custom_config[$module_id][0]['custom_search'] = 	array ( "enabled" => true, 
														"action_script" => "custom_actions/search.php"
												);

##############################################
####### CUSTOM ACTIONS COLUMNS ###############
##############################################

 $custom_config[$module_id][0]['custom_action_columns'] = 	array (
									"0" 	=> 	array(
												"header" 			=> "View",
												"show_header" 		=> false,
												"type"				=> "link",
												"action" 			=> "details",
												"icon"				=> "../../../images/share/details.png",
												"action_script" 	=> "custom_actions/details.php",
												"action_template" 	=> "template/custom_templates/details.php"
											),
									"1" 	=> 	array(
												"header" 			=> "Edit",
												"show_header" 		=> false,
												"type"				=> "link",
												"action" 			=> "edit",
												"icon"				=> "../../../images/share/edit.png",
												"action_script" 	=> "custom_actions/edit.php",
												"action_template" 	=> "template/custom_templates/edit.php"
											),
									"2" 	=> 	array(
												"header" 			=> "Delete",
												"show_header" 		=> false,
												"type"				=> "link",
												"action" 			=> "delete",
												"icon"				=> "../../../images/share/delete.png",
												"action_script" 	=> "custom_actions/delete.php",
												"action_template" 	=> "template/custom_templates/delete.php",
												"events"			=> "onclick=\"return confirmDelete()\""
											)
							);
##############################################
####### CUSTOM ACTIONS BUTTON  ###############
##############################################

 $custom_config[$module_id][0]['custom_action_buttons'] = array (
									"0"		=>	array(
												"text" 				=> "Add",
												"action" 			=> "add",
												"style"				=> "formButton",
												"action_script"		=> "custom_actions/add.php",
												"action_template"	=> "template/custom_templates/add.php"
										)
						);

