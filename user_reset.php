<?php
require "config.php";
require "auth.php";
require_admin();

/* ===============================
   Validate ID
================================ */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  http_response_code(400);
  die("Invalid request");
}

$id = (int)$_GET['id'];

/* ===============================
   กัน admin reset ตัวเอง
================================ */
if ($_SESSION['user']['id'] == $id) {
  die("<h3 style='color:red;text-align:center'>
        ❌ ไม่สามารถ Reset รหัสผ่านของตัวเองได้
       </h3>");
}

/* ===============================
   Reset password (default)
================================ */
$defaultPassword = "123456";
$newpass = password_hash($defaultPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
  UPDATE users SET password=? WHERE id=?
");
$stmt->bind_param("si", $newpass, $id);
$stmt->execute();

/* ===============================
   Audit log
================================ */
audit($conn, "RESET PASSWORD", "user_id=$id");

/* ===============================
   Redirect
================================ */
header("Location: manage_users.php");
exit;
