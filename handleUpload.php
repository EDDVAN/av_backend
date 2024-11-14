<?php
function handleUpload($token, $path, PDO $db)
{
    try {
        $targetDir = $path;
        $targetFile = $targetDir . basename($_FILES['upfile']['name']);

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        if ($_FILES['upfile']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode([
                "success" => false,
                "error" => "File upload error: " . $_FILES['upfile']['error']
            ]);
            exit();
        }
        if (!move_uploaded_file($_FILES['upfile']['tmp_name'], $targetFile)) {
            echo json_encode([
                "success" => false,
                "error" => "Failed to upload file",
            ]);
            exit();
        } else {
            $sql = "INSERT INTO document (id_user, path, file, date) VALUES ((select id_user from access where token = :token), :path, :file, curdate())";
            $stmt = $db->prepare($sql);
            $stmt->execute(['token' => $token, 'path' => $targetDir, 'file' => $_FILES['upfile']['name']]);
            echo json_encode([
                "success" => true,
                "message" => "File uploaded successfully",
                "file" =>  basename($_FILES['upfile']['name'])
            ]);
            exit();
        }
    } catch (Exception $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
        exit();
    }
}
