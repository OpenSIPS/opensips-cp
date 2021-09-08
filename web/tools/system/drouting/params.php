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
		"tip"    => "Number of results page range",
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
	),
	"table_groups" => array(
		"default" => "dr_groups",
		"name"    => "Table groups",
		"type"    => "text",
		"validation_regex" => null,
	),
	"table_rules" => array(
		"default" => "dr_rules",
		"name"    => "Table rules",
		"type"    => "text",
		"validation_regex" => null,
	),
	"table_carriers" => array(
		"default" => "dr_carriers",
		"name"    => "Table carriers",
		"type"    => "text",
		"validation_regex" => null,
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
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