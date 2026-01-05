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
   ดึงข้อมูล user
================================ */
$stmt = $conn->prepare("
  SELECT id, username, role, emp_code 
  FROM users WHERE id=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  die("User not found");
}

$user = $res->fetch_assoc();

/* ===============================
   POST : Update
================================ */
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $username = trim($_POST['username'] ?? '');
  $role     = $_POST['role'] ?? '';
  $emp      = trim($_POST['emp_code'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '') {
    $error = "❌ Username ห้ามว่าง";
  } elseif (!in_array($role, ['admin','manager','user'])) {
    $error = "❌ Role ไม่ถูกต้อง";
  } elseif ($_SESSION['user']['id'] == $id && $role !== $user['role']) {
    $error = "❌ ไม่สามารถแก้ไข Role ของตัวเองได้";
  } else {

    /* ===== ตรวจ username ซ้ำ (ยกเว้นตัวเอง) ===== */
    $check = $conn->prepare("
      SELECT id FROM users 
      WHERE username=? AND id!=?
    ");
    $check->bind_param("si", $username, $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "❌ Username ซ้ำ";
    } else {

      /* ===== Update ===== */
      if ($password !== '') {
        // เปลี่ยน password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
          UPDATE users 
          SET username=?, password=?, role=?, emp_code=? 
          WHERE id=?
        ");
        $stmt->bind_param("ssssi", $username, $hash, $role, $emp, $id);

        audit($conn, "UPDATE USER + PASSWORD", "user_id=$id");

      } else {
        // ไม่เปลี่ยน password
        $stmt = $conn->prepare("
          UPDATE users 
          SET username=?, role=?, emp_code=? 
          WHERE id=?
        ");
        $stmt->bind_param("sssi", $username, $role, $emp, $id);

        audit($conn, "UPDATE USER", "user_id=$id");
      }

      $stmt->execute();

      header("Location: manage_users.php");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Edit User</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
  font-family:'Segoe UI',Tahoma,sans-serif;
  background:#f1f5f9;
}
.card{
  max-width:440px;
  margin:60px auto;
  background:#fff;
  padding:25px;
  border-radius:14px;
  box-shadow:0 15px 40px rgba(0,0,0,.1);
}
label{font-size:14px;color:#334155}
input,select{
  width:100%;
  padding:10px;
  margin-top:6px;
  margin-bottom:14px;
  border-radius:8px;
  border:1px solid #cbd5e1;
}
button{
  width:100%;
  padding:10px;
  border:none;
  border-radius:10px;
  background:#0ea5e9;
  color:#fff;
  font-weight:600;
}
small{color:#64748b}
.error{
  background:#fee2e2;
  color:#b91c1c;
  padding:10px;
  border-radius:8px;
  margin-bottom:15px;
}
.back{text-align:center;margin-top:15px;}
.back a{text-decoration:none;color:#2563eb;}
</style>
</head>

<body>

<div class="card">
  <h3>✏️ แก้ไขผู้ใช้</h3>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">

    <label>Username</label>
    <input type="text" name="username"
           value="<?= htmlspecialchars($user['username']) ?>" required>

    
    <input type="password" name="password">
    <small>กรอกเฉพาะเมื่ออยากเปลี่ยนรหัสผ่าน</small><br><br>

    <label>Emp Code</label>
    <input type="text" name="emp_code"
           value="<?= htmlspecialchars($user['emp_code']) ?>">

    <label>Role</label>
    <select name="role">
    <option value="admin"   <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
    <option value="manager" <?= $user['role']=='manager'?'selected':'' ?>>Manager</option>
    <option value="user"    <?= $user['role']=='user'?'selected':'' ?>>User</option>

    </select>

    <button type="submit">บันทึกการแก้ไข</button>

  </form>

  <div class="back">
    <a href="manage_users.php">← กลับหน้าจัดการผู้ใช้</a>
  </div>
</div>

</body>
</html>
