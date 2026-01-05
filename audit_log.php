<?php
require "config.php";
require "auth.php";
require_admin();

$res = $conn->query("
  SELECT admin_username, action, target, created_at
  FROM audit_logs
  ORDER BY created_at DESC
  LIMIT 200
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Audit Log | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#2563eb;
  --bg:#f1f5f9;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
  --border:#e5e7eb;
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
.card{
  background:var(--card);
  border-radius:16px;
  box-shadow:0 15px 40px rgba(0,0,0,.1);
  padding:25px;
}

.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;margin-top:15px;}
th,td{
  padding:12px;
  border-bottom:1px solid var(--border);
  text-align:left;
  font-size:14px;
}
th{background:#f8fafc;color:#334155;font-weight:600;}
tr:hover{background:#f1f5f9;}

.badge{
  padding:4px 10px;
  border-radius:999px;
  font-size:12px;
  font-weight:600;
  background:#e5e7eb;
}

.footer{
  margin-top:15px;
  display:flex;
  justify-content:space-between;
  font-size:13px;
  color:var(--muted);
}

.back a{
  text-decoration:none;
  color:#2563eb;
}
</style>
</head>

<body>

<div class="header">
  <h1>🕵️ Audit Log</h1>
  <p>บันทึกการกระทำของผู้ดูแลระบบ</p>
</div>

<div class="container">
  <div class="card">

    <h3>ประวัติการใช้งาน (Admin)</h3>

    <div class="table-wrap">
      <table>
        <tr>
          <th>Admin</th>
          <th>Action</th>
          <th>Target</th>
          <th>Date / Time</th>
        </tr>

        <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['admin_username']) ?></td>
          <td><span class="badge"><?= htmlspecialchars($row['action']) ?></span></td>
          <td><?= htmlspecialchars($row['target']) ?></td>
          <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <div class="footer">
      <div>แสดงสูงสุด 200 รายการล่าสุด</div>
      <div class="back">
        <a href="manage_users.php">← กลับหน้าจัดการผู้ใช้</a>
      </div>
    </div>

  </div>
</div>

</body>
</html>
