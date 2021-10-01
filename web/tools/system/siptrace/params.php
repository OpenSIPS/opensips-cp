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
		"tip"    => "Control over the pagination when displaying the siptrace records",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this siptrace status (on or off)."
	),
    "table_trace" => array(
        "default" => "sip_trace",
        "name" => "Table Trace",
        "type" => "text",
		"tip"  => "the database table name for storing the siptrace data"
    ),
    "proxy_list" => array(
        "default" => array("udp:78.46.64.50:5060","tcp:78.46.64.50:5060"),
        "name" => "Proxy list",
        "type" => "json",
		"tip"  => "An array of SIP interfaces (protocol, IP address and port) to be recognized as belonging to your OpenSIPS servers -
		 you must provide at least one entry. This iss very important to be correctly provision, otherwise the tool will not be able to properly graph the SIP flow (as it will not know which entity in the flow is your OpenSIPS)."
    ),
);