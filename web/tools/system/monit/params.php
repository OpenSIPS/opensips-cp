<?php

$config->monit = array(
	"refresh_timeout" => array(
		"default" => 15,
		"name"    => "Refresh timeout",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
);