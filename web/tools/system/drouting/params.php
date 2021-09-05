<?php

$config->drouting = array(
	"gateway_types_file" => array(
		"default" => array(
            0 => "Gateway",
            "1" =>  "Proxy",
            '2'  => "PSTN",
            3 => "Other"
        ),
		"name"    => "Gateway Types File",
		"type"    => "json",
	),
	"group_ids_file" => array(
		"default" => array(
            0 => "Regular",
            1 => "Free"
        ),
		"name"    => "Group IDs File",
		"type"    => "json",
	)
);