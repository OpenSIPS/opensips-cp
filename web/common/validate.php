<?php

function retrieve_validate_function($name) {
	if (!isset($_SESSION["valid-".$name]))
		return null;
	eval("\$func = ".$_SESSION["valid-".$name].';');
	return $func;
}

session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	throw new Exception('Invalid method');
}
$data = json_decode(file_get_contents('php://input'), true);
if ($data === null) {
	http_response_code(400);
	throw new Exception('Invalid JSON input');
}
if (!isset($data["func"])) {
	http_response_code(400);
	throw new Exception('Invalid JSON function');
}
$func = retrieve_validate_function($data["func"]);
if (!$func) {
	http_response_code(404);
	throw new Exception('Validate function not found');
}
$in = (isset($data["input"])?$data["input"]:null);
if ($func($in)) {
	http_response_code(200);
} else {
	http_response_code(400);
}
?>
