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
 $module_id = "tls_mgm";
 $custom_config[$module_id] = array ();

 // a custom global name for the tool
 $custom_config[$module_id]['custom_name'] = "TLS Management";
 

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

$custom_config[$module_id][0]['custom_table'] = get_settings_value_from_tool("custom_table", "tls_mgm");
$custom_config[$module_id][0]['custom_table_primary_key'] = "id";
$custom_config[$module_id][0]['custom_table_order_by'] = $custom_config[$module_id][0]['custom_table_primary_key'];
$custom_config[$module_id][0]['per_page'] = get_settings_value_from_tool("per_page", "tls_mgm");
$custom_config[$module_id][0]['page_range'] = get_settings_value_from_tool("page_range", "tls_mgm");

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
					"header" 		=> "ID",
					"type"			=> "text",
					"key"			=> "PRI",
					"show_in_add_form" 	=> false,
					"show_in_edit_form"	=> false,
					"searchable" 		=> false,
					"visible"		=> false
			), 
			"domain"	=> 	array (
					"header" 		=> "TLS Domain Name",
					"type"			=> "text",
					"key"			=> "UNI",
					"tip"			=> "The name of the TLS domain which uniquely identifies a client or a server domain.",
					"is_optional" 		=> "n",
					"validation_regex" 	=> "^[a-zA-Z\.\-_]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"match_ip_address"=> 	array (
					"header" 		=> "Network address",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The IP addresses and ports used to match a TLS connection with a virtual TLS domain. For TLS server domains, these values will be mathced against the socket on which the connection is received. For TLS client domains, the values will be compared with the destination socket of the connection. The format is \"ip:port\" and the special value \"*\" means: match any address.",
					"is_optional" 		=> "y",
					"validation_regex" 	=> "^(\*|([0-9.]+:[0-9]{1,5}))$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"match_sip_domain"=> 	array (
					"header" 		=> "SIP domain",
					"type"			=> "text",
					"key"			=> NULL,
					"tip"			=> "The SIP domains used to match a TLS connection with a virtual TLS domain. For TLS server domains, these values will be matched against the hostname provided in the TLS Servername extension(SNI). For TLS client domains, the values will be compared with the value of the \"client_sip_domain_avp\" AVP",
					"is_optional" 		=> "y",
					"validation_regex" 	=> "^(\*|([0-9a-zA-Z.-]+.[0-9A-Za-z]))$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true
			),
			"type" 	=> 	array (
					"header" 		=> "Type",
					"type"			=> "combo",
					"key"			=> NULL,
					"tip"			=> "The type of the TLS domain.",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"combo_default_values"	=> array("1"=>"Client", "2"=>"Server"),
					"default_value" 	=> "1"
			),
			"method" 	=> 	array (
					"header" 			=> "SSL Method",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "SSL method used by a certain domain.",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"combo_default_values"	=> array("TLSv1"=>"TLSv1", "TLSv1_2"=>"TLSv1_2", "SSLv23"=>"SSLv23", "SSLv3"=>"SSLv3"),
					"default_value" 	=> "SSLv23"
			),
			"verify_cert" 	=> 	array (
					"header" 			=> "Verify Certificates",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Verify against CA the certificates presented for this TLS domain.",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"combo_default_values"	=> array("0"=>"No", "1"=>"Yes"),
					"default_value" 	=> "1"
			),
			"require_cert" 	=> 	array (
					"header" 			=> "Require Certificates",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Require the other peer to present a certificate for all connections of this TLS domain.",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"combo_default_values"	=> array("0"=>"No", "1"=>"Yes"),
					"default_value" 	=> "1"
			),
			"certificate"	=> 	array (
					"header" 			=> "Certificate",
					"type"				=> "textarea",
					"key"				=> NULL,
					"tip" 				=> "The certificate used for TLS Domain Name.",
					"textarea_display_size"	=> 50,
					"is_optional"	 	=> "n",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"private_key"	=> 	array (
					"header" 			=> "Private Key",
					"type"				=> "textarea",
					"key"				=> NULL,
					"tip" 				=> "The private key coresponding to the certificate.",
					"textarea_display_size"	=> 50,
					"is_optional"	 	=> "n",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"crl_check_all"	=>	array (
					"header" 			=> "CRL Check All",
					"type"				=> "combo",
					"key"				=> NULL,
					"tip"				=> "Specifies whether the entire Certificate Revocation chain should be checked.",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> true,
					"visible" 		=> false,
					"combo_default_values"	=> array("0"=>"No", "1"=>"Yes"),
					"default_value" 	=> "0"
			),
			"crl_dir"	=> 	array (
					"header" 			=> "CRL Directory",
					"type"				=> "text",
					"key"				=> NULL,
					"tip" 				=> "The path to the Certificate Revocation List Directory.",
					"is_optional"	 	=> "y",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"ca_list"	=> 	array (
					"header" 			=> "CA List",
					"type"				=> "textarea",
					"key"				=> NULL,
					"tip" 				=> "The list of Certificate Authority chain.",
					"textarea_display_size"	=> 50,
					"is_optional"	 	=> "y",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"ca_dir"	=> 	array (
					"header" 			=> "CA Directory",
					"type"				=> "text",
					"key"				=> NULL,
					"tip" 				=> "The path to the Certificate Autority Directory.",
					"is_optional"	 	=> "y",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"cipher_list"	=> 	array (
					"header" 		=> "Ciphers List",
					"type"			=> "text",
					"key"			=> NULL,
					"tip" 			=> "The list of algorithms, separated using ':', accepted for authentication and encryption.",
					"validation_regex" 	=> "^[a-zA-Z0-9\-:]+$",
					"is_optional"	 	=> "y",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"dh_params"	=> 	array (
					"header" 		=> "Diffie-Hellmann Parameters",
					"type"			=> "textarea",
					"key"			=> NULL,
					"tip" 			=> "Diffie-Hellmann parameters.",
					"textarea_display_size"	=> 50,
					"is_optional"	 	=> "y",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
			"ec_curve"	=> 	array (
					"header" 		=> "Elliptic Curve",
					"type"			=> "text",
					"key"			=> NULL,
					"tip" 			=> "Elliptic Curve which should be used for cipers that demand it.",
					"is_optional"	 	=> "y",
					"validation_regex" 	=> "^[a-z0-9]+$",
					"show_in_add_form" 	=> true,
					"show_in_edit_form"	=> true,
					"searchable" 		=> false,
					"visible" 		=> false,
			),
	);



 //need to reload 0 or 1
 $custom_config[$module_id][0]['reload'] = 1;

 //if you need reload please specify the MI command to be ran
 $custom_config[$module_id][0]['custom_mi_command'] = "tls_reload";
 
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
												"header" 			=> "Details",
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
