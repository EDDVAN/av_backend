<?php
include_once 'config.php';
include_once 'sessionManager.php';
include_once 'handleUpload.php';
include_once 'handleDownlaod.php';

// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    try {
        if (!empty($_POST['operation'])) {
            if ($_POST['operation'] === "generateSession" && !empty($_POST['user'])) {
                $token = generateSession($_POST['user'], $db);
                echo json_encode([
                    "token" =>   $token
                ]);
                exit();
            } else if ($_POST['operation'] === "upload" && !empty($_POST['token']) && !empty($_FILES['upfile']['name']) && !empty($_POST['path'])) {
                if (checkToken($_POST['token'], $db)) { {
                        handleUpload($_POST['token'], $_POST['path'], $db);
                    }
                } else {
                    echo json_encode([
                        "error" => "Invalid token"
                    ]);
                    exit();
                }
            } else if ($_POST['operation'] === "download" && !empty($_POST['token']) && !empty($_POST['path'])) {
                if (checkToken($_POST['token'], $db)) {
                    handleDownload($_POST['token'], $_POST['path'], $db);
                } else {
                    echo json_encode([
                        "error" => "Invalid token"
                    ]);
                    exit();
                }
            } else {
                echo json_encode([
                    "error" => "Invalid operation"
                ]);
                exit();
            }
        }
    } catch (Exception $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
        exit();
    }
} else if ($requestMethod === "GET") {
}
