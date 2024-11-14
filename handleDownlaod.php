<?php

function printError()
{
    include_once('../includes/config.php');
    echo '
<!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <!-- <link href="../assets/css/bootstrap.css" rel="stylesheet" /> -->
        <!-- <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" /> -->
        <!-- <link href="../assets/css/style.css" rel="stylesheet" /> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <!-- <link href="../assets/css/font-awesome.css" rel="stylesheet" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
        <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
        <style>
            body {
                width: 100vw;
                font-family: "Open Sans", sans-serif;
            }

            a {
                text-decoration: none;
            }

            .row {
                margin: 0 !important;
            }
        </style>
    </head>
    <body>';
    // include_once('./header.php');
    echo '
        <div style="width:100vw;height:100vh;display:flex;align-items:center;justify-content:center;flex-direction:column;"><h1>404</h1>
        <h1>Document non disponible</h1></div>
        </body>
    </html>';
}

function handleDownload($token, $file_url, PDO $db)
{
    try {
        if (is_file($file_url)) {
            $sql = "INSERT into download (id_user, path, date) VALUES ((select id_user from access where token = :token), :path, curdate())";
            $stmt = $db->prepare($sql);
            $stmt->execute(['token' => $token, 'path' => $file_url]);
            header("Content-type: " . mime_content_type($file_url));
            header("Content-disposition: inline; filename=\"" . basename($file_url) . "\"");
            readfile($file_url);
        } else {
            header("Content-type: text/html");
            printError();
        }
    } catch (Exception $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
        exit();
    }
}
