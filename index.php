<?php
require "config.php";
$res = $conn->query("SELECT * FROM attendance ORDER BY id DESC LIMIT 50");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Smart Attendance | Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#2563eb;
  --bg:#f1f5f9;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
  --border:#e5e7eb;
  --in:#16a34a;
  --out:#dc2626;
}

*{
  box-sizing:border-box;
  font-family:'Segoe UI', Tahoma, sans-serif;
}

body{
  margin:0;
  background:var(--bg);
  color:var(--text);
}

/* ===== Header ===== */
.header{
  background:linear-gradient(135deg,var(--primary),#1e40af);
  color:#fff;
  padding:25px;
}

.header h1{
  margin:0;
  font-size:24px;
}

/* ===== Container ===== */
.container{
  max-width:1200px;
  margin:-30px auto 40px;
  padding:0 15px;
}

/* ===== Card ===== */
.card{
  background:var(--card);
  border-radius:16px;
  box-shadow:0 15px 40px rgba(0,0,0,.1);
  padding:25px;
}

/* ===== Table ===== */
.table-wrap{
  overflow-x:auto;
}

table{
  width:100%;
  border-collapse:collapse;
  margin-top:15px;
}

th, td{
  padding:14px 12px;
  border-bottom:1px solid var(--border);
  text-align:center;
  font-size:14px;
}

th{
  background:#f8fafc;
  font-weight:600;
  color:#334155;
}

tr:hover{
  background:#f1f5f9;
}

/* ===== Badge ===== */
.badge{
  padding:6px 14px;
  border-radius:999px;
  font-size:13px;
  font-weight:600;
  display:inline-block;
}

.in{
  background:#dcfce7;
  color:var(--in);
}

.out{
  background:#fee2e2;
  color:var(--out);
}

/* ===== Footer ===== */
.footer{
  margin-top:15px;
  text-align:right;
  font-size:13px;
  color:var(--muted);
}
</style>
</head>

<body>

<div class="header">
  <h1>📋 Smart Attendance System</h1>
  <p>Real-time Attendance Monitoring</p>
</div>

<div class="container">
  <div class="card">

    <h3>ประวัติการเข้า–ออกล่าสุด</h3>

    <div class="table-wrap">
      <table>
        <tr>
          <th>ID</th>
          <th>UID</th>
          <th>รหัสพนักงาน</th>
          <th>ชื่อพนักงาน</th>
          <th>สถานะ</th>
          <th>วันเวลา</th>
        </tr>

        <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['uid'] ?></td>
          <td><?= $row['emp_code'] ?></td>
          <td><?= $row['emp_name'] ?></td>
          <td>
            <?php if($row['action'] === 'IN'): ?>
              <span class="badge in">เข้า (IN)</span>
            <?php else: ?>
              <span class="badge out">ออก (OUT)</span>
            <?php endif; ?>
          </td>
          <td><?= $row['datetime'] ?></td>
        </tr>
        <?php endwhile; ?>

      </table>
    </div>

    <div class="footer">
      แสดง 50 รายการล่าสุด
    </div>

  </div>
</div>

</body>
</html>
