<?php

$config->dialplan = array(
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
		"tip"    => "Control over the pagination when displaying the dialplan rules",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this dialplan information.",
	),
    "table_dialplan" => array(
		"default" => "dialplan",
		"name"    => "Table Dialplan",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "The database table name for storing the diaplan rules",
	),
    "attrs_cb" => array(
        "default" => array(
            "a" => "Descr a",
            "b" => "Descr b",
            "c" => "Descr c",
        ),
        "name" => "Attributes cb",
        "type" => "json",
		"tip" => "If \$dialplan_attributes_mode is set to 1, this array must define the possible attribute options.
		 Each options is a char, the resulting string being the set of the options/chars that are enabled." 
    ),
	"dialplan_attributes_mode" => array(
		"default" => 1,
		"name"    => "Dialplan attributes mode",
		"options" => array('Checkboxes'=>'0', 'Text'=>'1'),
		"type"    => "dropdown",
		"tip" 	  => "How the interpret the attributes of the rules: 0 - an checkbox with predefined value; 1 - an opaque string",
	),
);