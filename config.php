<?php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'rra');
define('DB_PASS', 'Byn@1234');
define('DB_NAME', 'archivemanagerdb');
define('SECRET', 'RRA SECRET KEY');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
