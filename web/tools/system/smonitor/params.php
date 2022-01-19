<?php

$config->smonitor = array(
	"sampling_time" => array(
		"default" => 1,
		"name"    => "Sampling Time",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
		"tip"     => "Default value for the sampling interval (in minutes)"
	),
	"chart_size" => array(
		"default" => 100,
		"name"    => "Chart Size",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
		"tip"     => "The horizontal size of the charts (in hours)"
	),
	"chart_history" => array(
		"default" => 'auto',
		"name"    => "Chart History",
		"type"    => "text",
		"validation_regex" => "^(auto|[0-9]+)$",
		"tip"     => "Amount of time (in hours) to keep samples before deleting them"
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
	"tabs" => array(
		"default" => "0,1",
		"name"    => "Tabs",
		"options" => array("Realtime Statistics"=>'0', "Statistics Charts"=>'1'),
		"tip"    => "List of available tabs for smonitor tool",
		"type"    => "checklist"
	)
);