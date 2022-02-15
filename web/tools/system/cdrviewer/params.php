<?php

$config->cdrviewer = array(
	"cdr_table" => array(
		"default" => "acc",
		"name"    => "CDR table",
		"type"    => "text",
		"validation_regex" => null,
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
	"export_csv" => array(
		"default" => array (
			"id" => "CDR ID",
			"time" => "Call Start Time",
			"method" => "SIP Method",
			"callid" => "Sip Call ID",
			"sip_code" => "Sip Code",
			"sip_reason" => "Sip Reason",
			"setuptime" => "Setup Time",
			"duration" => "Duration",
			"from_tag" => "Sip From Tag",
			"to_tag" => "Sip To Tag"
		),
		"type" => "json",
		"name" => "Export CSV"
	),
	"show_field" => array(
		"default" => array(
			"time" => "Time",
			"method" => "Method",
			"callid" => "Sip Call ID",
			"sip_code" => "Sip Code",
			"sip_reason" => "Sip Reason",
			"setuptime" => "Setup Time",
			"duration" => "Duration",
			"from_tag" => "Sip From Tag",
			"to_tag" => "Sip To Tag"
		),
		"type" => "json",
		"name" => "Show Field"
	),
	"title0" => array(
		"type" => "title",
		"title" => "Color scheme"
	),
	"from_color" => array(
		"default" => "black",
		"name" => "From color",
		"type" => "text"
	),
	"to_color" => array(
		"default" => "white",
		"name" => "To color",
		"type" => "text"
	),
	"callid_color" => array(
		"default" => "black",
		"name" => "Call ID color",
		"type" => "text"
	),
	"cseq_color" => array(
		"default" => "white",
		"name" => "CSeq color",
		"type" => "text"
	),
	"regexp_color" => array(
		"default" => "navy",
		"name" => "Regex color",
		"type" => "text"
	),
	"from_bgcolor" => array(
		"default" => "yellow",
		"name" => "From bgcolor",
		"type" => "text"
	),
	"to_bgcolor" => array(
		"default" => "blue",
		"name" => "To bgcolor",
		"type" => "text"
	),
	"callid_bgcolor" => array(
		"default" => "orange",
		"name" => "Call ID bgcolor",
		"type" => "text"
	),
	"cseq_bgcolor" => array(
		"default" => "navy",
		"name" => "CSeq bgcolor",
		"type" => "text"
	),
	"regexp_bgcolor" => array(
		"default" => "red",
		"name" => "Regex bgcolor",
		"type" => "text"
	),
  
    );