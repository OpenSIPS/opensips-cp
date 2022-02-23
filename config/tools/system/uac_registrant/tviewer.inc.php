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
 $module_id = "uac_registrant";
 $custom_config[$module_id] = array ();

 // a custom global name for the tool
 $custom_config[$module_id]['custom_name'] = "UAC Registrant";
 

 $custom_config[$module_id][0]['custom_table'] = get_settings_value_from_tool("custom_table", "uac_registrant");
 $custom_config[$module_id][0]['custom_table_primary_key'] = "id";
 $custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
 $custom_config[$module_id][0]['per_page'] = get_settings_value_from_tool("per_page", "uac_registrant");
 $custom_config[$module_id][0]['page_range'] = get_settings_value_from_tool("page_range", "uac_registrant");

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
			"registrar"	=> 	array (
					"header" 		=> "Registrar Server",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "SIP URI pointing to the remote registrar",
					"is_optional" 		=> "n",
					"validation_regex" 	=> $re_sip_uri,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"proxy"	=> 	array (
					"header" 		=> "Outbound Proxy",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "SIP URI pointing to the outbond proxy",
					"is_optional" 		=> "y",
					"validation_regex" 	=> $re_sip_uri,
					"visible"		=> false,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"aor"	=> 	array (
					"header" 		=> "Address-of-Record",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "SIP URI defining the account to be registered",
					"is_optional" 		=> "n",
					"validation_regex" 	=> $re_sip_uri,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"third_party_registrant"=> 	array (
					"header" 		=> "3rd Party Registrant",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "SIP URI defining as sender/registrant (From header) something else than actual registered account (AOR)",
					"is_optional" 		=> "y",
					"validation_regex" 	=> $re_sip_uri,
					"visible"		=> false,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"username"	=> 	array (
					"header" 		=> "Auth Username",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "Username to be used for the authentication process",
					"is_optional" 		=> "y",
					"validation_regex" 	=> "[a-zA-Z0-9_]+",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"password"	=> 	array (
					"header" 		=> "Auth Password",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "Password to be used for the authentication process",
					"is_optional" 		=> "y",
					"validation_regex" 	=> NULL,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"visible"		=> false,
					"searchable" 		=> false
			),
			"binding_URI"	=> 	array (
					"header" 		=> "Contact URI",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "Username to be used for the authentication process",
					"is_optional" 		=> "y",
					"validation_regex" 	=> $re_sip_uri,
					"default_value"		=> "",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"expiry"	=> 	array (
					"header" 		=> "Expires",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The expiration time of the registration, in seconds",
					"is_optional" 		=> "y",
					"validation_regex" 	=> "[0-9]+",
					"default_value"		=> "3600",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"forced_socket"	=> 	array (
					"header" 		=> "Local Socket",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "OpenSIPS SIP socket/listener to be used for doing this registration",
					"is_optional" 		=> "y",
					"validation_regex" 	=> $re_socket,
					"visible"		=> false,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"cluster_shtag"	=> 	array (
					"header" 		=> "Clustering Sharing TAG",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "A cluster sharing tag (as [tag_name/custer_id]) used to control this registration in clustering scenarios",
					"is_optional" 		=> "y",
					"validation_regex" 	=> "^[a-z0-9A-Z_-]+/[0-9]+$",
					"visible"		=> false,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			),
			"state"	=> 	array (
					"header" 		=> "Status",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "If Enabled or Disabled",
					"combo_default_values" => array("1"=>"Disabled","0"=>"Enabled"),
					"is_optional" 		=> "n",
					"visible"		=> true,
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false
			)
	);



 //need to reload 0 or 1
 $custom_config[$module_id][0]['reload'] = 1;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][0]['custom_mi_command'] = "reg_reload";
 
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
