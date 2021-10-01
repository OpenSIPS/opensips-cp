<?php

$config->tls_mgm = array(
	"module_id" => array(
		"default" => "smpp",
		"name"    => "Module ID",
		"type"    => "text",
		"validation_regex" => null,
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),

);