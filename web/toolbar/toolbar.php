<?php

require("db_connect.php");
$message = array();

$extraReq = $_POST;
if (!empty($extraReq['active_module'])) {

    $module = $extraReq['active_module'];

    if ($module == 'domains')
        $module = 'domain';

    $names = $extraReq['name'];
    $attrs = $extraReq['attr'];
    $values = $extraReq['value'];

    foreach ($names as $key => $name) {
        if (!empty($module) && !empty($name)) {

            $sql = "ALTER TABLE `opensips`.`$module` ADD COLUMN `$name` " . $attrs[$key] . "(" . (!empty($values[$key]) ? $values[$key] : 255) . ");";
            if ($conn->query($sql) === TRUE) {
                array_push($message, ['success' => true, 'message' => "New columns added successfully"]);
            } else {
                array_push($message, ['error' => true, 'message' => $conn->error]);
            }
        }
    }
} else
    array_push($message, ['error' => true, 'message' => "Something went wrong!"]);

echo json_encode($message);
$conn->close();