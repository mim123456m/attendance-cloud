<?php
require "config.php";
require "auth.php";
require_admin();

/* ===============================
   ตรวจสอบ ID
================================ */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  http_response_code(400);
  die("<h3 style='color:red;text-align:center'>❌ ID ไม่ถูกต้อง</h3>");
}

$id = (int)$_GET['id'];

/* ===============================
   กัน admin ลบตัวเอง
================================ */
if ($_SESSION['user']['id'] == $id) {
  die("<h3 style='color:red;text-align:center'>
        ❌ ไม่สามารถลบบัญชีของตัวเองได้
       </h3>");
}

/* ===============================
   ดึงข้อมูล user
================================ */
$stmt = $conn->prepare("
  SELECT username, role 
  FROM users 
  WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  die("<h3 style='color:red;text-align:center'>❌ ไม่พบผู้ใช้</h3>");
}

$user = $res->fetch_assoc();

/* ===============================
   POST : Delete
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  audit($conn, "DELETE USER", "user_id=$id username={$user['username']}");

  header("Location: manage_users.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Delete User</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
  font-family:'Segoe UI',Tahoma,sans-serif;
  background:#f1f5f9;
}
.card{
  max-width:420px;
  margin:80px auto;
  background:#fff;
  padding:25px;
  border-radius:14px;
  box-shadow:0 15px 40px rgba(0,0,0,.1);
  text-align:center;
}
h3{margin-top:0}
.warn{
  background:#fee2e2;
  color:#b91c1c;
  padding:12px;
  border-radius:10px;
  margin-bottom:20px;
  font-size:14px;
}
button{
  padding:10px 18px;
  border:none;
  border-radius:10px;
  font-weight:600;
  cursor:pointer;
}
.btn-del{
  background:#dc2626;
  color:#fff;
}
.btn-cancel{
  background:#e5e7eb;
  color:#334155;
  margin-left:10px;
}
</style>
</head>

<body>

<div class="card">
  <h3>❌ ลบผู้ใช้</h3>

  <div class="warn">
    ต้องการลบผู้ใช้ <b><?= htmlspecialchars($user['username']) ?></b> ใช่หรือไม่?<br>
    การลบนี้ไม่สามารถย้อนกลับได้
  </div>

  <form method="post">
    <button class="btn-del" type="submit">ลบผู้ใช้</button>
    <a href="manage_users.php">
      <button type="button" class="btn-cancel">ยกเลิก</button>
    </a>
  </form>
</div>

</body>
</html>
