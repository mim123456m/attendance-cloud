<?php
require "config.php";
require "auth.php";
require_admin();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $role     = $_POST['role'] ?? 'user';
  $emp      = trim($_POST['emp_code'] ?? '');

  /* ===== Validate ===== */
  if ($username === '' || $password === '') {
    $error = "❌ กรุณากรอก Username และ Password";
  } elseif (!in_array($role, ['admin','manager','user'])) {
    $error = "❌ Role ไม่ถูกต้อง";
  } else {

    // 🔍 เช็ค username ซ้ำ
    $check = $conn->prepare("SELECT id FROM users WHERE username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "❌ Username ซ้ำ";
    } else {

      // 🔐 Hash password
      $hash = password_hash($password, PASSWORD_DEFAULT);

      // ➕ เพิ่มผู้ใช้
      $stmt = $conn->prepare("
        INSERT INTO users (username, password, role, emp_code)
        VALUES (?, ?, ?, ?)
      ");
      $stmt->bind_param("ssss", $username, $hash, $role, $emp);
      $stmt->execute();

      audit($conn, "CREATE USER", "username=$username");

      // ✅ แก้ redirect ให้ตรงระบบ
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
<title>Add User | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{
  font-family:'Segoe UI',Tahoma,sans-serif;
  background:#f1f5f9;
}
.card{
  max-width:420px;
  margin:60px auto;
  background:#fff;
  padding:25px;
  border-radius:14px;
  box-shadow:0 15px 40px rgba(0,0,0,.1);
}
h3{margin-top:0}
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
  background:#2563eb;
  color:#fff;
  font-weight:600;
  cursor:pointer;
}
button:hover{opacity:.9}
.error{
  background:#fee2e2;
  color:#b91c1c;
  padding:10px;
  border-radius:8px;
  margin-bottom:15px;
  font-size:14px;
}
.back{
  text-align:center;
  margin-top:15px;
}
.back a{
  text-decoration:none;
  color:#2563eb;
  font-size:14px;
}
</style>
</head>

<body>

<div class="card">
  <h3>➕ เพิ่มผู้ใช้</h3>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>รหัสพนักงาน (emp_code)</label>
    <input type="text" name="emp_code">

    <label>Role</label>
<select name="role">
  <option value="admin">Admin</option>
  <option value="manager">Manager</option>
  <option value="user" selected>User</option>
</select>


    <button type="submit">บันทึก</button>

  </form>

  <div class="back">
    <a href="manage_users.php">← กลับหน้าจัดการผู้ใช้</a>
  </div>
</div>

</body>
</html>
