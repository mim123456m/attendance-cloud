<?php
/* ===============================
   Database Configuration (PDO + Railway)
================================ */

$host = $_ENV['MYSQLHOST'] ?? getenv('MYSQLHOST');
$user = $_ENV['MYSQLUSER'] ?? getenv('MYSQLUSER');
$pass = $_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD');
$db   = $_ENV['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE');
$port = $_ENV['MYSQLPORT'] ?? getenv('MYSQLPORT');

date_default_timezone_set("Asia/Bangkok");

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("❌ DB ERROR: " . $e->getMessage());
}
