<?php
require "config.php";
require "auth.php";
require_admin();

/* ===== สถิติผู้ใช้ ===== */
$totalUsers = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalAdmin = $conn->query("SELECT COUNT(*) c FROM users WHERE role='admin'")->fetch_assoc()['c'];
$totalUser  = $conn->query("SELECT COUNT(*) c FROM users WHERE role='user'")->fetch_assoc()['c'];

/* ===== สถิติ Audit ===== */
$totalAudit = $conn->query("SELECT COUNT(*) c FROM audit_logs")->fetch_assoc()['c'];

/* ===== Audit ล่าสุด ===== */
$audit = $conn->query("
  SELECT admin_username, action, target, created_at
  FROM audit_logs
  ORDER BY created_at DESC
  LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#2563eb;
  --bg:#f1f5f9;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
}

*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,sans-serif;}
body{margin:0;background:var(--bg);color:var(--text);}

.header{
  background:linear-gradient(135deg,var(--primary),#1e40af);
  color:#fff;
  padding:25px;
}
.header h1{margin:0;font-size:24px;}

.container{max-width:1100px;margin:-30px auto 40px;padding:0 15px;}

.grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
}

.card{
  background:var(--card);
  border-radius:16px;
  box-shadow:0 15px 40px rgba(0,0,0,.1);
  padding:20px;
}

.stat{
  font-size:28px;
  font-weight:700;
}

.label{
  color:var(--muted);
  font-size:14px;
}

.table{
  width:100%;
  border-collapse:collapse;
  margin-top:10px;
}
.table th,.table td{
  padding:10px;
  border-bottom:1px solid #e5e7eb;
  font-size:14px;
}
.table th{
  background:#f8fafc;
  text-align:left;
}

.actions a{
  text-decoration:none;
  color:#2563eb;
  font-size:14px;
  margin-right:10px;
}
</style>
</head>

<body>

<div class="header">
  <h1>📊 Admin Dashboard</h1>
  <p>ภาพรวมระบบและการจัดการ</p>
</div>

<div class="container">

  <!-- ===== Summary ===== -->
  <div class="grid">
    <div class="card">
      <div class="stat"><?= $totalUsers ?></div>
      <div class="label">ผู้ใช้ทั้งหมด</div>
    </div>
    <div class="card">
      <div class="stat"><?= $totalAdmin ?></div>
      <div class="label">Admin</div>
    </div>
    <div class="card">
      <div class="stat"><?= $totalUser ?></div>
      <div class="label">User</div>
    </div>
    <div class="card">
      <div class="stat"><?= $totalAudit ?></div>
      <div class="label">Audit Logs</div>
    </div>
  </div>

  <!-- ===== Recent Audit ===== -->
  <div class="card" style="margin-top:30px;">
    <h3>🕵️ Audit ล่าสุด</h3>

    <table class="table">
      <tr>
        <th>Admin</th>
        <th>Action</th>
        <th>Target</th>
        <th>Date</th>
      </tr>
      <?php while($a = $audit->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($a['admin_username']) ?></td>
        <td><?= htmlspecialchars($a['action']) ?></td>
        <td><?= htmlspecialchars($a['target']) ?></td>
        <td><?= $a['created_at'] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <!-- ===== Quick Links ===== -->
  <div class="card actions" style="margin-top:30px;">
    <h3>⚙️ จัดการระบบ</h3>
    <a href="manage_users.php">👥 จัดการผู้ใช้</a>
    <a href="audit_log.php">🕵️ Audit Log</a>
    <a href="dashboard.php">📋 Attendance</a>
  </div>

</div>

</body>
</html>
