<?php
require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    $connection->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Koneksi database gagal. Jalankan generate.php dan periksa config.php.');
}