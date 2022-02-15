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
		"tip"    => "Sets number of pages per range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
    "table_acls" => array(
        "default" => "grp",
        "name" => "Table ACLs",
        "tip" => "The name of the DB table where the groups (and mapping to SIP users) are stored.",
        "type" => "text"
    ),
    "grps" => array(
        "default" =>  array("grp_one","grp_two","grp_three"),
        "name" => "Groups",
        "tip" => "A list with the groups that you are using in your OpenSIPS config file. The value are custom and they are define by the script writer.",
        "type" => "json"
    )
    );