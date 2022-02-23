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
 $module_id = "smpp";
 $custom_config[$module_id] = array ();

 // a custom global name for the tool
 $custom_config[$module_id]['custom_name'] = "SMPP Gateway";
 

 $custom_config[$module_id][0]['custom_table'] = get_settings_value_from_tool("custom_table", "smpp");
 $custom_config[$module_id][0]['custom_table_primary_key'] = "id";
 $custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
 $custom_config[$module_id][0]['per_page'] = get_settings_value_from_tool("per_page", "smpp");
 $custom_config[$module_id][0]['page_range'] = get_settings_value_from_tool("page_range", "smpp");

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

 // we need this for various predefined regexp to be used for validation
 require("../../forms.php");

 $custom_config[$module_id][0]['custom_table_column_defs'] = array (	
			"id" 		=> 	array (
					"header" 		=> "ID",
					"type"			=> "text",
					"key"			=> "PRI",
					"show_in_add_form" 	=> false,
					"show_in_edit_form"	=> false,
					"searchable" 		=> false,
					"visible"		=> false
			), 
			"name"	=> 	array (
					"header" 		=> "SMSC Name",
					"type"			=> "text",
					"key"			=> "UNI",
					"tip"			=> "An arbitrary name of the SMSC, used to uniquely identify the binding",
					"is_optional" 		=> "n",
					"validation_regex" 	=> "[a-zA-Z0-9_]+",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"ip"	=> 	array (
					"header" 		=> "SMSC IP Address",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The IP address used to connect to the SMSC",
					"is_optional" 		=> "n",
					"validation_regex" 	=> $re_ip,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"port"	=> 	array (
					"header" 		=> "SMSC port",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The port used to connect to the SMSC",
					"is_optional" 		=> "n",
					"validation_regex" 	=> "^[0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"system_id"=> 	array (
					"header" 		=> "System ID",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The System ID (also called user name) for the gateway to use when connecting to the SMPP server (max 16 chars)",
					"is_optional" 		=> "n",
					"validation_regex" 	=> "^.{0,16}$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"password"	=> 	array (
					"header" 		=> "Auth Password",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The password for the gateway to use when connecting to the SMPP server (max 9 chars)",
					"is_optional" 		=> "n",
					"validation_regex" 	=> "^.{0,9}$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
			"system_type"	=> 	array (
					"header" 		=> "System Type",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "Configures the System Type parameter of the the SMPP server (max 13 chars)",
					"is_optional" 		=> "y",
					"validation_regex" 	=> "^.{0,13}$",
					"validation_regex" 	=> NULL,
					"default_value"		=> "",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"src_ton"	=> 	array (
					"header" 		=> "Source TON",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "Specifies the Source TON (Type of Number)",
					"is_optional" 		=> "n",
					"combo_default_values"	=> array(
						"0"=>"Unknown",
						"1"=>"International",
						"2"=>"National",
						"3"=>"Network Specific",
						"4"=>"Subscriber Number",
						"5"=>"Alphanumeric",
						"6"=>"Abbreviated",
					), 
					"default_value"		=> "0",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
			"src_npi"	=> 	array (
					"header" 		=> "Source NPI",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "Specifies the Source NPI (Numbering Plan Indicator)",
					"is_optional" 		=> "n",
					"combo_default_values"	=> array(
						"0"=>"Unknown",
						"1"=>"ISDN/telephone numbering plan (E163/E164)",
						"3"=>"Data numbering plan (X.121)",
						"4"=>"Telex numbering plan (F.69)",
						"6"=>"Land Mobile (E.212)",
						"8"=>"National numbering plan",
						"9"=>"Private numbering plan",
						"10"=>"ERMES numbering plan (ETSI DE/PS 3 01-3)",
						"13"=>"Internet (IP)",
						"18"=>"WAP Client Id (to be defined by WAP Forum)",
					), 
					"default_value"		=> "0",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
			"dst_ton"	=> 	array (
					"header" 		=> "Destination TON",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "Specifies the Destination TON (Type of Number)",
					"is_optional" 		=> "n",
					"combo_default_values"	=> array(
						"0"=>"Unknown",
						"1"=>"International",
						"2"=>"National",
						"3"=>"Network Specific",
						"4"=>"Subscriber Number",
						"5"=>"Alphanumeric",
						"6"=>"Abbreviated",
					), 
					"default_value"		=> "0",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
			"dst_npi"	=> 	array (
					"header" 		=> "Destination NPI",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "Specifies the Destination NPI (Numbering Plan Indicator)",
					"is_optional" 		=> "n",
					"combo_default_values"	=> array(
						"0"=>"Unknown",
						"1"=>"ISDN/telephone numbering plan (E163/E164)",
						"3"=>"Data numbering plan (X.121)",
						"4"=>"Telex numbering plan (F.69)",
						"6"=>"Land Mobile (E.212)",
						"8"=>"National numbering plan",
						"9"=>"Private numbering plan",
						"10"=>"ERMES numbering plan (ETSI DE/PS 3 01-3)",
						"13"=>"Internet (IP)",
						"18"=>"WAP Client Id (to be defined by WAP Forum)",
					), 
					"default_value"		=> "0",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
			"session_type"	=> 	array (
					"header" 		=> "Session Type",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "Specifies the type of binding",
					"is_optional" 		=> "n",
					"combo_default_values"	=> array("1"=>"transciever","2"=>"transmitter","3"=>"receiver","4"=>"outbind"), 
					"default_value"		=> "1",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
	);



 //need to reload 0 or 1
 $custom_config[$module_id][0]['reload'] = 0;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][0]['custom_mi_command'] = "xxxx_reload";
 
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

 $custom_config[$module_id][0]['custom_action_buttons'] = array (
									"0"		=>	array(
												"text" 				=> "Add",
												"action" 			=> "add",
												"style"				=> "formButton",
												"action_script"		=> "custom_actions/add.php",
												"action_template"	=> "template/custom_templates/add.php"
										)
						);
