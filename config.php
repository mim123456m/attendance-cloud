<?php
/* ===============================
   Database Configuration (Railway)
================================ */

$host = "trolley.proxy.rlwy.net";
$user = "root";
$pass = "QztuXhPPHVBhutpGgCOCflUlGWDvewgJ";
$db   = "railway";
$port = 44425;

date_default_timezone_set("Asia/Bangkok");

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die("❌ DB ERROR: " . $e->getMessage());
}
