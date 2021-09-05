<?php

$config->domains = array(
    "table_domains" => array(
        "default" => "domains",
        "name" => "Table Domains",
        "type" => "text"
    ),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	)
);