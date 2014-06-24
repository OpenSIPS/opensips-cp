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
 $module_id = "tviewer";
 $custom_config[$module_id] = array ();

 // a custom global name for the tool
 $custom_config[$module_id]['custom_name'] = "TViewer";
 

//if you want submenu (horizontal) items add them here:

// $custom_config[$module_id]['submenu_items'] = array(
// 												"0"	=> "Submenu1",
// 												"1"	=> "Submenu2"
//												);


/* config for each submenu item */

/*
Example table: cc_agents
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

####################################################################################
#																				   #
# Uncomment bellow this line - follow the example and adapt it to your DB table    #
#																				   #
####################################################################################

/*
 $custom_config[$module_id][0]['custom_table'] = "cc_agents";
 $custom_config[$module_id][0]['custom_table_primary_key'] = "id";
 $custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
 $custom_config[$module_id][0]['per_page'] = 5;
 $custom_config[$module_id][0]['page_range'] = 3;

 //column types definitions 
 // in forms - should be text / combo / datetime / checkbox = right now implemented are text and combo
 // as a comment: if you have a column that is readonly you should set a default value for it ... or not - perhaps if it's readonly you should choose not to display it in edit and/or add
 // do not forget that as disable the value is not submitted in forms
 
 $custom_config[$module_id][0]['custom_table_column_defs'] = array (	
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
								"agentid"	=> 	array (
												"header" 			=> "Agent ID",
												"type"				=> "text",
												"key"				=> "UNI",
												"validation_regex" 	=> "/^\d+$/",
												"validation_regex" 	=> "/^[a-zA-Z0-9_]+$/",
												"validation_err" 	=> "Invalid Agent ID - only alphanumeric and underscore allowed ",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											), 
								"location" 	=> 	array (
												"header" 			=> "Location",
												"type"				=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^\d+$/",
												"validation_regex" 	=> "/^.+$/",
												"validation_err" 	=> "Invalid location",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											),
								"logstate"  =>	array (       
												"header" 			=> "Log State",
												"type"  			=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^\d$/",
												"validation_err" 	=> "Invalid log state",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> true,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
											),
								"skills" 	=>	array (       
												"header" 			=> "Skills",
												"type"  			=> "text",
												"key"				=> NULL,
												"validation_regex" 	=> "/^.*$/",
												"validation_err" 	=> "Invalid Skills",
												"show_in_add_form" 	=> true,
												"show_in_edit_form"	=> true,
												"searchable" 		=> false,
												"disabled" 			=> false,
												"readonly" 			=> false,
												"default_value" 	=> NULL
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
												"icon"				=> "images/edit.png",
												"action_script" 	=> "custom_actions/edit.php",
												"action_template" 	=> "template/custom_templates/edit.php"
											),
									"1" 	=> 	array(
												"header" 			=> "Delete",
												"show_header" 		=> false,
												"type"				=> "link",
												"action" 			=> "delete",
												"icon"				=> "images/delete.png",
												"action_script" 	=> "custom_actions/delete.php",
												"action_template" 	=> "template/custom_templates/delete.php"
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
												"action_script"		=> "custom_actions/add.php",
												"action_template"	=> "template/custom_templates/add.php"
										)
						);
*/
