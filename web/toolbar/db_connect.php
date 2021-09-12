<?php

require_once("../../config/db.inc.php");

global $config;

$conn = new mysqli($config->db_host, $config->db_user, $config->db_pass, $config->db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
