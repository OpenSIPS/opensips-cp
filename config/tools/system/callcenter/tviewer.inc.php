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
 $module_id = "callcenter";
 $custom_config[$module_id] = array ();

 // a custom global name for the tool
 $custom_config[$module_id]['custom_name'] = "Call Center";
 
 //if you want submenu (horizontal) items add them here:
 $custom_config[$module_id]['submenu_items'] = get_settings_value_from_tool("submenu_items", "callcenter");


/* config for each submenu item */

/*
+---------------+------------------+------+-----+---------+----------------+
| Field         | Type             | Null | Key | Default | Extra          |
+---------------+------------------+------+-----+---------+----------------+
| id            | int(10) unsigned | NO   | PRI | NULL    | auto_increment | 
| agentid       | char(128)        | NO   | UNI |         |                | 
| location      | char(128)        | NO   |     |         |                | 
| logstate      | int(10) unsigned | NO   |     | 0       |                | 
| skills        | char(255)        | NO   |     |         |                | 
| last_call_end | int(11)          | NO   |     | 0       |                | 
+---------------+------------------+------+-----+---------+----------------+
*/

 $custom_config[$module_id][0]['custom_table'] = get_settings_value_from_tool("agents_custom_table", "callcenter");
 $custom_config[$module_id][0]['custom_table_primary_key'] = "id";
 $custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
 $custom_config[$module_id][0]['per_page'] = get_settings_value_from_tool("agents_per_page", "callcenter");
 $custom_config[$module_id][0]['page_range'] = get_settings_value_from_tool("agents_page_range", "callcenter");

 //column types definitions 
 // in forms - should be text / combo / datetime / checkbox = right now implemented are text and combo
 // as a comment: if you have a column that is readonly you should set a default value for it ... or not - perhaps if it's readonly you should choose not to display it in edit and/or add
 // do not forget that as disable the value is not submitted in forms
 
 $custom_config[$module_id][0]['custom_table_column_defs'] = array (	
								"id" 		=> 	array (
												"header" 			=> "ID",
												"type"				=> "text",
												"key"				=> "PRI",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> true,
												"default_value" 	=> NULL,
												"visible" 			=> false
											), 
								"agentid"	=> 	array (
												"header" 			=> "Agent ID",
												"type"				=> "text",
												"key"				=> "UNI",
												"tip"				=> "The unique ID (alphanumerical) of the agent. This ID will be used to reference the agent.",
												"validation_regex" 	=> "^[a-zA-Z0-9_]+$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											), 
								"location" 	=> 	array (
												"header" 			=> "Location",
												"type"				=> "text",
												"key"				=> NULL,
												"tip"				=> "The location of the agent, as a SIP URI, for receiving the audio calls.",
												"validation_regex" 	=> "^sip:[A-Za-z0-9_]+@[A-Za-z0-9-.]+(:[0-9]+)?$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"msrp_location" 	=> 	array (
												"header" 			=> "MSRP Location",
												"type"				=> "text",
												"key"				=> NULL,
												"tip"				=> "The location of the agent, as a SIP URI, for receiving the MSRP calls/chats.",
												"validation_regex" 	=> "^sip:[A-Za-z0-9_]+@[A-Za-z0-9-.]+(:[0-9]+)?$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"msrp_max_sessions" 	=> 	array (
												"header" 			=> "MSRP Max Sessions",
												"type"				=> "text",
												"key"				=> NULL,
												"tip"				=> "The number of maximum MSRP sessions the agent can simultaneously do. Makes sense only if the MSRP Location is set.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> "0",
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"logstate"  =>	array (       
												"header" 			=> "Log State",
												"type"  			=> "combo",
												"key"				=> NULL,
												"tip"				=> "If the agent should be by default considered as logged into the system (able to receive calls).",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> "0",
												"combo_default_values"	=> array("1"=>"Yes","0"=>"No"),
												"combo_table"		=> NULL,
												"combo_value_col"	=> NULL,
												"combo_display_col"	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"skills" 	=>	array (       
												"header" 			=> "Skills",
												"type"  			=> "text",
												"key"				=> NULL,
												"tip"				=> "A comma separated list of skills provided by this agent. The agent will receive calls from the queues that require one of these skills.",
												"validation_regex" 	=> "^[A-Za-z0-9_]+(,[A-Za-z0-9_]+)*$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											),
								"wrapup_time" 	=>	array (       
												"header" 		=> "Wrapup time",
												"type"  		=> "text",
												"key"			=> NULL,
												"tip"			=> "The wrapup time of this agent, before receiving a new call. This value may limited by the wrapup time of the flow, if smaller. A 0 value means there is no per-agent wrapup time.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"default_value" 	=> "0"
											)
							);



 //need to reload 0 or 1
 $custom_config[$module_id][0]['reload'] = 1;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][0]['custom_mi_command'] = "cc_reload";

 // what system to talk to for MI functions
 $talk_to_this_assoc_id = 1 ;



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
												"header" 			=> "Edit",
												"show_header" 		=> false,
												"type"				=> "link",
												"action" 			=> "edit",
												"icon"				=> "../../../images/share/edit.png",
												"action_script" 	=> "custom_actions/edit.php",
												"action_template" 	=> "template/custom_templates/edit.php"
											),
									"1" 	=> 	array(
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
												"color"				=> "red",
												"style"				=> "formButton",
												"action_script"		=> "custom_actions/add.php",
												"action_template"	=> "template/custom_templates/add.php"
										)
						);

/* FLOWS SUB MENU */

 $custom_config[$module_id][1]['custom_table'] = get_settings_value("flows_custom_table");
 $custom_config[$module_id][1]['custom_table_primary_key'] = "id";
 $custom_config[$module_id][1]['custom_table_order_by'] = $custom_config[$module_id][1]['custom_table_primary_key'];
 $custom_config[$module_id][1]['per_page'] = get_settings_value("flows_per_page");
 $custom_config[$module_id][1]['page_range'] = get_settings_value("flows_page_range");

/*
+-----------------+------------------+------+-----+---------+----------------+
| Field           | Type             | Null | Key | Default | Extra          |
+-----------------+------------------+------+-----+---------+----------------+
| id              | int(10) unsigned | NO   | PRI | NULL    | auto_increment | 
| flowid          | char(64)         | NO   | UNI |         |                | 
| priority        | int(11) unsigned | NO   |     | 256     |                | 
| skill           | char(64)         | NO   |     |         |                | 
| prependcid      | char(32)         | NO   |     |         |                | 
| message_welcome | char(128)        | YES  |     | NULL    |                | 
| message_queue   | char(128)        | NO   |     |         |                | 
+-----------------+------------------+------+-----+---------+----------------+
*/
 

 //column types definitions 
 // in forms - should be text / combo / datetime / checkbox = right now implemented are text and combo
 // as a comment: if you have a column that is readonly you should set a default value for it ... or not - perhaps if it's readonly you should choose not to display it in edit and/or add
 // do not forget that disabled fields are not submitted in forms
 
 $custom_config[$module_id][1]['custom_table_column_defs'] = array (	
								"id" 		=> 	array (
												"header" 			=> "ID",
												"type"				=> "text",
												"key"				=> "PRI",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> false,
												"disabled" 			=> true,
												"readonly" 			=> true,
												"default_value" 	=> NULL,
												"visible" 			=> false
											), 
								"flowid"	=> 	array (
												"header" 			=> "Flow ID",
												"type"				=> "text",
												"key"				=> "UNI",
												"tip"				=> "The unique ID (alphanumerical) of this flow. This ID will be used to reference this flow from OpenSIPS script.",
												"validation_regex" 	=> "^[a-zA-Z0-9_]+$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											), 
								"priority" 	=> 	array (
												"header" 			=> "Priority",
												"type"				=> "text",
												"key"				=> NULL,
												"tip"				=> "The priority of the flow/queue. Calls received via flows with higher priority will be delivered first.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> '0',
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"skill"  =>	array (       
												"header" 			=> "Skill",
												"type"  			=> "text",
												"key"				=> NULL,
												"tip"				=> "The skill (only one) required by this flow. The calls received via this queue will be delivered only to agents advertising this skill.",
												"validation_regex" 	=> "^[A-Za-z0-9_]+$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"prependcid" =>	array (       
												"header" 			=> "Prepend CID",
												"type"  			=> "text",
												"key"				=> NULL,
												"tip"				=> "An alphanumerical prefix to be add to the caller ID of the calls distributed via this flow.",
												"validation_regex" 	=> "^[a-zA-Z0-9]+$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"=> NULL,
												"events"			=> NULL
											),
								"max_wrapup_time" 	=> 	array (
												"header" 		=> "Max Wrapup Time",
												"type"			=> "text",
												"key"			=> NULL,
												"tip"			=> "The maximum warpup time (in seconds) allowed for the call terminated via this flow. This value will limit the default or per-agent wrapup time. A 0 value means no limit/max defined.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "y",
												"default_value" 	=> '0',
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"dissuading_hangup" 	=> 	array (
												"header" 		=> "Hangup on Dissuading",
												"type"			=> "combo",
												"key"			=> NULL,
												"tip"			=> "If enabled (yes), the calls diverted to dissuading destination will be closed after the dissuading terminates (useful when using a playback for dissuading).",
												"validation_regex" 	=> NULL,
												"is_optional"		=> "n",
												"combo_default_values"=>array("1"=>"Yes","0"=>"No"),
												"default_value" 	=> '0',
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> false,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"dissuading_onhold_th" 	=> 	array (
												"header" 		=> "Dissuading OnHold Threshold",
												"type"			=> "text",
												"key"			=> NULL,
												"tip"			=> "For how long (in seconds) a call will wait in the queue before getting a dissuading redirect. This is an options parameter. A 0 value means no dissuading after the call got into the queue.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "n",
												"default_value" 	=> '0',
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> false,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"dissuading_ewt_th" 	=> 	array (
												"header" 		=> "Dissuading EWT Threshold",
												"type"			=> "text",
												"key"			=> NULL,
												"tip"			=> "The Estimated Waiting Time (in seconds) threshold that will redirect a new incoming call (not queued yet) to the dissuading destination. This is an options parameter. A 0 value means no dissuading when receiving new calls into the queue.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "n",
												"default_value" 	=> '0',
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> false,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"dissuading_qsize_th" 	=> 	array (
												"header" 		=> "Dissuading Queue-Size Threshold",
												"type"			=> "text",
												"key"			=> NULL,
												"tip"			=> "The Size of the Queue (as already waiting calls in this flow) that will redirect a new incoming call (not queued yet) to the dissuading destination. This is an optional parameter, A 0 value means no dissuading when receiving new calls into the queue.",
												"validation_regex" 	=> "^[0-9]+$",
												"is_optional"		=> "n",
												"default_value" 	=> '0',
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> false,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"message_welcome"  =>	array (       
												"header" 		=> "Welcome Message",
												"type"  		=> "text",
												"key"			=> NULL,
												"tip"			=> "An optional SIP URI to provide the payback of the welcome message (followed by a hang up).",
												"validation_regex" 	=> "^sip:[A-Za-z0-9_]+@[A-Za-z0-9-.]+(:[0-9]+)?$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> true,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"message_queue"  =>	array (       
												"header" 		=> "Queue Message",
												"type"  		=> "text",
												"key"			=> NULL,
												"tip"			=> "A  SIP URI to provide the payback of the on-hold music. The playback must not hanged by the remote SIP URI, it must continously do playback.",
												"validation_regex" 	=> "^sip:[A-Za-z0-9_]+@[A-Za-z0-9-.]+(:[0-9]+)?$",
												"is_optional"		=> "n",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> true,
												"searchable" 		=> true,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"message_dissuading"  =>	array (       
												"header" 		=> "Dissuading Message",
												"type"  		=> "text",
												"key"			=> NULL,
												"tip"			=> "An optional SIP URI pointing to a media server; this is used for playing the dissuading message for this flow (followed by a hang up).",
												"validation_regex" 	=> "^sip:[A-Za-z0-9_]+@[A-Za-z0-9-.]+(:[0-9]+)?$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> true,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
								"message_flow_id"  =>	array (       
												"header" 		=> "Queue-ID Message",
												"type"  		=> "text",
												"key"			=> NULL,
												"tip"			=> "An optional SIP URI pointing to a media server;  this is used for playing the name of the flow id to the agent before delivering a call to hito provide the payback of the welcome message (followed by a hang up).",
												"validation_regex" 	=> "^sip:[A-Za-z0-9_]+@[A-Za-z0-9-.]+(:[0-9]+)?$",
												"is_optional"		=> "y",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"visible"		=> false,
												"searchable" 		=> true,
												"disabled" 		=> false,
												"readonly" 		=> false,
												"default_value" 	=> NULL,
												"value_wrapper_func"	=> NULL,
												"events"		=> NULL
											),
							);



 //need to reload 0 or 1
 $custom_config[$module_id][1]['reload'] = 1;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][1]['custom_mi_command'] = "cc_reload";

 // what system to talk to for MI functions
 $talk_to_this_assoc_id = 1 ;



##############################################
######### CUSTOM SEARCH OPTIONS ##############
##############################################
$custom_config[$module_id][1]['custom_search'] = 	array ( "enabled" => true, 
														"action_script" => "custom_actions/search.php"
												);

##############################################
####### CUSTOM ACTIONS COLUMNS ###############
##############################################

 $custom_config[$module_id][1]['custom_action_columns'] = 	array (
									"0" 	=> 	array(
												"header" 		=> "Details",
												"show_header" 		=> false,
												"type"			=> "link",
												"action" 		=> "details",
												"icon"			=> "../../../images/share/details.png",
												"action_script" 	=> "custom_actions/details.php",
												"action_template" 	=> "template/custom_templates/details.php"
											),
									"1" 	=> 	array(
												"header" 		=> "Edit",
												"show_header" 		=> false,
												"type"			=> "link",
												"action" 		=> "edit",
												"icon"			=> "../../../images/share/edit.png",
												"action_script" 	=> "custom_actions/edit.php",
												"action_template" 	=> "template/custom_templates/edit.php"
											),
									"2" 	=> 	array(
												"header" 		=> "Delete",
												"show_header" 		=> false,
												"type"			=> "link",
												"action" 		=> "delete",
												"icon"			=> "../../../images/share/delete.png",
												"action_script" 	=> "custom_actions/delete.php",
												"action_template" 	=> "template/custom_templates/delete.php",
												"events"		=> "onclick=\"return confirmDelete()\""
											)
							);
##############################################
####### CUSTOM ACTIONS BUTTON  ###############
##############################################

 $custom_config[$module_id][1]['custom_action_buttons'] = array (
									"0"		=>	array(
												"text" 				=> "Add",
												"action" 			=> "add",
												"color"				=> "red",
												"style"				=> "formButton",
												"action_script"		=> "custom_actions/add.php",
												"action_template"	=> "template/custom_templates/add.php"
										)
						);

/* CC CDRS SUBMENU ITEM */


 $custom_config[$module_id][2]['custom_table'] = get_settings_value("cdrs_custom_table");
 $custom_config[$module_id][2]['custom_table_primary_key'] = "id";
 $custom_config[$module_id][2]['custom_table_order_by'] = $custom_config[$module_id][2]['custom_table_primary_key'];
 $custom_config[$module_id][2]['per_page'] = get_settings_value("cdrs_per_page");
 $custom_config[$module_id][2]['page_range'] = get_settings_value("cdrs_page_range");

/*
+--------------------+------------------+------+-----+---------+----------------+
| Field              | Type             | Null | Key | Default | Extra          |
+--------------------+------------------+------+-----+---------+----------------+
| id                 | int(10) unsigned | NO   | PRI | NULL    | auto_increment | 
| caller             | char(64)         | NO   |     |         |                | 
| received_timestamp | datetime         | NO   |     |         |                | 
| wait_time          | int(11) unsigned | NO   |     | 0       |                | 
| pickup_time        | int(11) unsigned | NO   |     | 0       |                | 
| talk_time          | int(11) unsigned | NO   |     | 0       |                | 
| flow_id            | char(128)        | NO   |     |         |                | 
| agent_id           | char(128)        | YES  |     | NULL    |                | 
| call_type          | int(11)          | NO   |     | -1      |                | 
| rejected           | int(11) unsigned | NO   |     | 0       |                | 
| fstats             | int(11) unsigned | NO   |     | 0       |                | 
| cid                | int(11) unsigned | YES  |     | 0       |                | 
+--------------------+------------------+------+-----+---------+----------------+
*/
 

 //column types definitions 
 // in forms - should be text / combo / datetime / checkbox = right now implemented are text and combo
 // as a comment: if you have a column that is readonly you should set a default value for it ... or not - perhaps if it's readonly you should choose not to display it in edit and/or add
 // do not forget that as disable the value is not submitted in forms
 
 $custom_config[$module_id][2]['custom_table_column_defs'] = array (	
								"id" 		=> 	array (
												"header" 			=> "ID",
												"type"				=> "text",
												"key"				=> "PRI",
												"validation_regex" 	=> "/^\d+$/",
												"validation_err" 	=> "Invalid ID",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> true,
												"readonly" 			=> true,
												"default_value" 	=> NULL
											), 
								"media"		=> 	array (
												"header" 			=> "Media",
												"type"				=> "combo",
												"key"				=> NULL,
												"validation_regex" 	=> "/^\d+$/",
												"validation_err" 	=> "",
												"combo_default_values"=>array("1"=>"RTP/audio","2"=>"MSRP/chat"),
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> true,
												"default_value" 	=> NULL
											), 
								"caller"	=> 	array (
												"header" 			=> "Caller",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"received_timestamp"	=> 	array (
												"header" 			=> "Received Time",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"wait_time"	=> 	array (
												"header" 			=> "Wait Time",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"pickup_time"	=> 	array (
												"header" 			=> "Pickup Time",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> false,
												"disabled" 			=> true,
												"readonly" 			=> true,
												"default_value" 	=> NULL
											), 
								"talk_time"	=> 	array (
												"header" 			=> "Duration",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"flow_id"	=> 	array (
												"header" 			=> "Flow ID",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"call_type"	=> 	array (
												"header" 			=> "Call Type",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"rejected"	=> 	array (
												"header" 			=> "Rejected",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"fstats"	=> 	array (
												"header" 			=> "Stats",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> false,
												"disabled" 			=> true,
												"readonly" 			=> true,
												"default_value" 	=> NULL
											), 
								"cid"	=> 	array (
												"header" 			=> "CID",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "",
												"show_in_add_form" 	=> false,
												"show_in_edit_form"	=> false,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											) 
							);



 //need to reload 0 or 1
 $custom_config[$module_id][2]['reload'] = 0;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][2]['custom_mi_command'] = "";

 // what system to talk to for MI functions
 $talk_to_this_assoc_id = 1 ;



##############################################
######### CUSTOM SEARCH OPTIONS ##############
##############################################
$custom_config[$module_id][2]['custom_search'] = 	array ( "enabled" => true, 
														"action_script" => "custom_actions/search.php"
												);

##############################################
####### CUSTOM ACTIONS COLUMNS ###############
##############################################

 $custom_config[$module_id][2]['custom_action_columns'] = 	array (
							);
##############################################
####### CUSTOM ACTIONS BUTTON  ###############
##############################################

 $custom_config[$module_id][2]['custom_action_buttons'] = array (
						);
?>
