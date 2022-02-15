<?php

$config->alias_management = array(
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
	"table_aliases" => array(
		"default" => array( 
			"DBaliases" => "dbaliases"
		),
		"name"    => "Table aliases",
		"type"    => "json",
		"tip"     => "Parameter used for the aliases tables if there are more than the standard dbaliases table. The defined array has as key the label and as value the table name.For defining more 
		than one attribute/value pair, complete the list with identical elements separated by comma."
	),
	"alias_format" => array(
		"default" => "/^[0-9a-zA-Z]+/",
		"name" => "Alias Format",
		"tip" => "Pattern/regexp to validate the inserted aliases (in order to enforce a certain format for the aliases).",
		"type" => "text"
	)
    );