<?php

$config->siptrace = array(
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
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
    "table_trace" => array(
        "default" => "sip_trace",
        "name" => "Table Trace",
        "type" => "text"
    ),
    "proxy_list" => array(
        "default" => array("udp:78.46.64.50:5060","tcp:78.46.64.50:5060"),
        "name" => "Proxy list",
        "type" => "json"
    ),
);