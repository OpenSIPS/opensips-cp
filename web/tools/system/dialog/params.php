<?php

$config->dialog = array(
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
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"	  => "association ID pointing to system (group of OpenSIPS servers) to be queried for ongoing calls.
		 Note: only the first server from the group will be used for fetching the dialogs!!",
	),
	"tabs" => array(
		"default" => "0,1",
		"name"    => "Tabs",
		"options" => array('Ongoing calls'=>'0', 'Call profiles'=>'1'),
		"tip"    => "List of available tabs for dialog tool",
		"type"    => "checklist"
	)
);