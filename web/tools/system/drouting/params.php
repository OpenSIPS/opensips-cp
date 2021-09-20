<?php

$config->drouting = array(
	"gateway_types_file" => array(
		"default" => array(
            0 => "Gateway",
            "1" =>  "Proxy",
            '2'  => "PSTN",
            3 => "Other"
        ),
		"name"    => "Gateway Types File",
		"type"    => "json",
	),
	"group_ids_file" => array(
		"default" => array(
            0 => "Regular",
            1 => "Free"
        ),
		"name"    => "Group IDs File",
		"type"    => "json",
	),
	"results_per_page" => array(
		"default" => 25,
		"name"    => "Results per page",
		"tip"    => "Number of results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"results_page_range" => array(
		"default" => 10,
		"name"    => "Results page range",
		"tip"    => "Control over the pagination when displaying the dynamic routing rules",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"default_gw_type" => array(
		"default" => 1,
		"name"    => "Default gateway type",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"group_id_method" => array(
		"default" => "static",
		"name"    => "Group ID method",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "How the handle the drouting groups : 'static; -
		 the groups are statically configured via the 'Settings' tab ; 'dynamic' - the groups are read from the DB group table."
	),
	"default_domain" => array(
		"default" => "yourdomain.net",
		"name"    => "Default domain",
		"type"    => "text",
		"validation_regex" => null,
	),
	"routing_partition" => array(
		"default" => "",
		"opt"     => "y",
		"name"    => "Routing partition",
		"type"    => "text",
		"validation_regex" => null,
	),
	"table_gateways" => array(
		"default" => "dr_gateways",
		"name"    => "Table gateways",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "Database table for storing the drouting data"
	),
	"table_groups" => array(
		"default" => "dr_groups",
		"name"    => "Table groups",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "Database table for storing the drouting data"
	),
	"table_rules" => array(
		"default" => "dr_rules",
		"name"    => "Table rules",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "Database table for storing the drouting data"
	),
	"table_carriers" => array(
		"default" => "dr_carriers",
		"name"    => "Table carriers",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "Database table for storing the drouting data"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association
		 ID pointing to the group of servers (system) which needs to be provision with this drouting information."
	),
	"gw_attributes" => array(
		"default" => array(
			"display_name" => "Attributes",
			"add_prefill_value" => "",
			"validation_regexp" => NULL,
			"validation_error" => NULL,
		),
		"name"    => "Gateway attributes",
		"type"    => "json",
	)
);