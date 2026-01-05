<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/* ===============================
   Login Required
================================ */
function require_login() {
  if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
  }
}

/* ===============================
   Admin Only
================================ */
function require_admin() {
  require_login();

  if ($_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    exit("<h3 style='color:red;text-align:center'>
          ❌ หน้านี้สำหรับ Admin เท่านั้น
          </h3>");
  }
}

/* ===============================
   Manager + Admin
================================ */
function require_manager() {
  require_login();

  if (!in_array($_SESSION['user']['role'], ['admin','manager'])) {
    http_response_code(403);
    exit("<h3 style='color:red;text-align:center'>
          ❌ ไม่มีสิทธิ์เข้าถึงหน้านี้
          </h3>");
  }
}

/* ===============================
   Audit Log
================================ */
function audit($conn, $action, $target) {
  if (!$conn) return;
  if (!isset($_SESSION['user']['username'])) return;

  $actor = $_SESSION['user']['username'];

  $stmt = $conn->prepare("
    INSERT INTO audit_logs (admin_username, action, target)
    VALUES (?, ?, ?)
  ");
  $stmt->bind_param("sss", $actor, $action, $target);
  $stmt->execute();
}
