<?php

$config->clusterer = array(
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
        "tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association ID
         pointing to the group of servers (system) which needs to be provision with this clusterer information.",
	),
    "table_clusterer" => array(
        "default" => "clusterer",
        "name" => "Table Clusterer",
        "tip" => "The name of the DB table holding the cluster configuration 
        (this needs to be correlated with the OpenSIPS configuration). The default value is 'clusterer'.",
        "type" => "text"
    )
);