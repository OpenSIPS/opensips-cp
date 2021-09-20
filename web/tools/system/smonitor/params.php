<?php

$config->smonitor = array(
	"sampling_time" => array(
		"default" => 10,
		"name"    => "Sampling Time",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
		"tip"     => "Default value for the sampling interval (in seconds)"
	),
	"chart_size" => array(
		"default" => 100,
		"name"    => "Chart Size",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
		"tip"     => "The horizontal size of the charts (as number of samples/dots)"
	),
	"chart_history" => array(
		"default" => 'auto',
		"name"    => "Chart History",
		"type"    => "text",
		"validation_regex" => "^(auto|[0-9]+)$",
		"tip"     => "Amount of smaples (per statistics) to be kept before start deleting them"
	),
	"table_monitored" => array(
		"default" => "monitored_stats",
		"name"    => "Table Monitored",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "Database table name for storing the monitoring data"
	),
	"table_monitoring" => array(
		"default" => "monitoring_stats",
		"name"    => "Table monitoring",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "Database table name for storing the monitoring data"
	),
	"config_type" => array(
		"default" => "global",
		"name"    => "Config type",
		"type"    => "text",
		"validation_regex" => null,
	),
);