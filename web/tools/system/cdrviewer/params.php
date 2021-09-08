<?php

$config->cdrviewer = array(
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
	"sip_call_id_field_name" => array(
		"default" => "callid",
		"name"    => "SIP call ID field name",
		"type"    => "text",
		"validation_regex" => null,
	),
	"cdr_id_field_name" => array(
		"default" => "id",
		"name"    => "CDR ID field name",
		"type"    => "text",
		"validation_regex" => null,
	),
	"cdr_repository_path" => array(
		"default" => "/var/lib/opensips_cdrs",
		"name"    => "CDR repository path",
		"type"    => "text",
		"validation_regex" => null,
	),
	"cdr_repository_path" => array(
		"default" => "/var/lib/opensips_cdrs",
		"name"    => "CDR repository path",
		"type"    => "text",
		"validation_regex" => null,
	),	
	"cdr_set_field_names" => array(
		"default" => 1,
		"name"    => "CDR set field names",
		"options" => array('Off'=>'0', 'On'=>'1'),
		"type"    => "dropdown",
	),
    "delay" => array(
		"default" => 3600,
		"name"    => "Delay",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
  
    );