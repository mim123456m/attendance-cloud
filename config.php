<?php
/* ===============================
   Database Configuration (Railway)
================================ */

// ===== Railway MySQL =====
$host = "trolley.proxy.rlwy.net";   // จาก Railway
$user = "root";                     // จาก Railway
$pass = "QztuXhPPHVBhutpGgCOCflUlGWDvewgJ";   // 🔴 ใส่ของจริง
$db   = "railway";                  // Railway ใช้ชื่อนี้
$port = 44425;                      // สำคัญมาก

/* ===============================
   Timezone (สำคัญกับ attendance)
================================ */
date_default_timezone_set("Asia/Bangkok");

/* ===============================
   Create Connection
================================ */
$conn = new mysqli($host, $user, $pass, $db, $port);

/* ===============================
   Error Handling
================================ */
if ($conn->connect_error) {
  http_response_code(500);
  die("❌ Database connection failed : " . $conn->connect_error);
}

/* ===============================
   Charset (ภาษาไทย / Emoji)
================================ */
$conn->set_charset("utf8mb4");

/* ===============================
   Strict SQL Mode (องค์กรใช้จริง)
================================ */
$conn->query("SET sql_mode = 'STRICT_ALL_TABLES'");
