<?php

$config->domains = array(
    "table_domains" => array(
        "default" => "domains",
        "name" => "Table Domains",
        "type" => "text",
		"tip"  => "The database table name for storing the domain entries"
    ),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip" 	  => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this domain information."
	)
);