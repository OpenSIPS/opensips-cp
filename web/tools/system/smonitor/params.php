<?php

$config->smonitor = array(
	"sampling_time" => array(
		"default" => 10,
		"name"    => "Sampling Time",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"chart_size" => array(
		"default" => 100,
		"name"    => "Chart Size",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"chart_history" => array(
		"default" => 'auto',
		"name"    => "Chart History",
		"type"    => "text",
		"validation_regex" => "^(auto|[0-9]+)$",
	),
	"table_monitored" => array(
		"default" => "monitored_stats",
		"name"    => "Table Monitored",
		"type"    => "text",
		"validation_regex" => null,
	),
	"table_monitoring" => array(
		"default" => "monitoring_stats",
		"name"    => "Table monitoring",
		"type"    => "text",
		"validation_regex" => null,
	),
	"config_type" => array(
		"default" => "global",
		"name"    => "Config type",
		"type"    => "text",
		"validation_regex" => null,
	),
);