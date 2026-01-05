<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "รหัสผ่านไม่ถูกต้อง";
    }
  } else {
    $error = "ไม่พบบัญชีผู้ใช้";
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Login | Attendance System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#2563eb;
  --primary-dark:#1e40af;
  --bg:#f1f5f9;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
  --danger:#dc2626;
}

*{
  box-sizing:border-box;
  font-family:'Segoe UI', Tahoma, sans-serif;
}

body{
  margin:0;
  min-height:100vh;
  background:linear-gradient(135deg,#e0e7ff,#f8fafc);
  display:flex;
  align-items:center;
  justify-content:center;
}

/* ===== Card ===== */
.login-card{
  width:100%;
  max-width:380px;
  background:var(--card);
  padding:35px;
  border-radius:16px;
  box-shadow:0 20px 50px rgba(0,0,0,.15);
}

/* ===== Header ===== */
.login-header{
  text-align:center;
  margin-bottom:25px;
}

.login-header h2{
  margin:0;
  font-size:26px;
}

.login-header p{
  margin-top:6px;
  color:var(--muted);
  font-size:14px;
}

/* ===== Input ===== */
.form-group{
  margin-bottom:18px;
}

label{
  display:block;
  font-size:14px;
  margin-bottom:6px;
  color:var(--muted);
}

input{
  width:100%;
  padding:12px 14px;
  border-radius:10px;
  border:1px solid #cbd5e1;
  font-size:15px;
  outline:none;
  transition:.2s;
}

input:focus{
  border-color:var(--primary);
  box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* ===== Button ===== */
button{
  width:100%;
  padding:12px;
  border:none;
  border-radius:10px;
  background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  color:#fff;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  transition:.25s;
}

button:hover{
  opacity:.9;
  transform:translateY(-1px);
}

/* ===== Error ===== */
.error{
  background:#fee2e2;
  color:var(--danger);
  padding:10px;
  border-radius:10px;
  text-align:center;
  margin-bottom:18px;
  font-size:14px;
}

/* ===== Footer ===== */
.footer{
  margin-top:20px;
  text-align:center;
  font-size:13px;
  color:var(--muted);
}
</style>
</head>

<body>

<div class="login-card">

  <div class="login-header">
    <h2>🔐 Sign in</h2>
    <p>Attendance Management System</p>
  </div>

  <?php if ($error): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" required autofocus>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>

    <button type="submit">Login</button>
  </form>

  <div class="footer">
    © <?= date("Y") ?> Attendance System
  </div>

</div>

</body>
</html>
