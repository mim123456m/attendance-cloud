<?php
/* ===============================
   Database Configuration
================================ */

$host = "localhost";
$user = "root";
$pass = "12345678";
$db   = "attendance_db";

/* ===============================
   Timezone (สำคัญกับ attendance)
================================ */
date_default_timezone_set("Asia/Bangkok");

/* ===============================
   Create Connection
================================ */
$conn = new mysqli($host, $user, $pass, $db);

/* ===============================
   Error Handling
================================ */
if ($conn->connect_error) {
  http_response_code(500);
  die("❌ Database connection failed");
}

/* ===============================
   Charset (ภาษาไทย / Emoji)
================================ */
$conn->set_charset("utf8mb4");

/* ===============================
   Strict SQL Mode (องค์กรใช้จริง)
================================ */
$conn->query("SET sql_mode = 'STRICT_ALL_TABLES'");
