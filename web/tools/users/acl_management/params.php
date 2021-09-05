<?php

$config->acl_management = array(
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
    "table_acls" => array(
        "default" => "grp",
        "name" => "Table ACLs",
        "tip" => "The array containing the alias tables",
        "type" => "text"
    ),
    "grps" => array(
        "default" =>  array("grp_one","grp_two","grp_three"),
        "name" => "Groups",
        "tip" => "List with the name of the groups defined in OpenSIPS cfg",
        "type" => "json"
    )
    );