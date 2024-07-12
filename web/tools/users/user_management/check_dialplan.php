<?php

session_start();

header('Content-Type: application/json');

require_once("../../../common/mi_comm.php");
require_once("../../../common/cfg_comm.php");

$errors = [];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if ($input === null) {
            http_response_code(400);
            throw new Exception('Invalid JSON input');
        }

        if (isset($input['command']) && isset($input['params'])) {
            $command = $input['command'];
            $params = $input['params'];

            // Obține proxys
            $mi_connectors = get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

            if (empty($mi_connectors)) {
                http_response_code(500);
                throw new Exception('No MI connectors available');
            }

            $result = mi_command($command, $params, $mi_connectors[0], $errors);

            if (!empty($errors)) {
                http_response_code(406);
                echo json_encode(['error' => $errors]);
            } else {
                http_response_code(200);
                echo json_encode(['result' => $result]);
            }
        } else {
            http_response_code(400);
            throw new Exception('Invalid input');
        }
    } else {
        http_response_code(405);
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
