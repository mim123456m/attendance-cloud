<?php
require "config.php";
require "auth.php";
require_manager();

/* ===== Summary ===== */
$totalEmp = $conn->query("
  SELECT COUNT(DISTINCT emp_code) c FROM attendance
")->fetch_assoc()['c'];

$todayIn = $conn->query("
  SELECT COUNT(DISTINCT emp_code) c 
  FROM attendance 
  WHERE action='IN' AND DATE(datetime)=CURDATE()
")->fetch_assoc()['c'];

$todayOut = $conn->query("
  SELECT COUNT(DISTINCT emp_code) c 
  FROM attendance 
  WHERE action='OUT' AND DATE(datetime)=CURDATE()
")->fetch_assoc()['c'];

/* ===== Today Attendance ===== */
$today = $conn->query("
  SELECT emp_code, emp_name, action, datetime
  FROM attendance
  WHERE DATE(datetime)=CURDATE()
  ORDER BY datetime DESC
  LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Manager Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#0ea5e9;
  --bg:#f1f5f9;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
  --in:#16a34a;
  --out:#dc2626;
}

*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,sans-serif;}
body{margin:0;background:var(--bg);color:var(--text);}

.header{
  background:linear-gradient(135deg,var(--primary),#0284c7);
  color:#fff;
  padding:30px;
}

.container{
  max-width:1100px;
  margin:-30px auto 40px;
  padding:0 15px;
}

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

.label{color:var(--muted);font-size:14px;}

table{
  width:100%;
  border-collapse:collapse;
  margin-top:15px;
}
th,td{
  padding:12px;
  border-bottom:1px solid #e5e7eb;
  font-size:14px;
}
th{background:#f8fafc;text-align:left;}

.badge{
  padding:5px 12px;
  border-radius:999px;
  font-size:12px;
  color:#fff;
}
.in{background:var(--in);}
.out{background:var(--out);}
</style>
</head>

<body>

<div class="header">
  <h1>📊 Manager Dashboard</h1>
  <p>ภาพรวมการทำงานของพนักงาน</p>
</div>

<div class="container">

  <!-- Summary -->
  <div class="grid">
    <div class="card">
      <div class="stat"><?= $totalEmp ?></div>
      <div class="label">พนักงานทั้งหมด</div>
    </div>
    <div class="card">
      <div class="stat"><?= $todayIn ?></div>
      <div class="label">เข้างานวันนี้</div>
    </div>
    <div class="card">
      <div class="stat"><?= $todayOut ?></div>
      <div class="label">ออกงานวันนี้</div>
    </div>
  </div>

  <!-- Today Table -->
  <div class="card" style="margin-top:30px;">
    <h3>📋 สถานะวันนี้</h3>
    <table>
      <tr>
        <th>รหัส</th>
        <th>ชื่อ</th>
        <th>สถานะ</th>
        <th>เวลา</th>
      </tr>
      <?php while($r=$today->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($r['emp_code']) ?></td>
        <td><?= htmlspecialchars($r['emp_name']) ?></td>
        <td>
          <?php if($r['action']==='IN'): ?>
            <span class="badge in">IN</span>
          <?php else: ?>
            <span class="badge out">OUT</span>
          <?php endif; ?>
        </td>
        <td><?= $r['datetime'] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <div style="margin-top:20px;text-align:right;">
    <a href="dashboard.php">← กลับ Dashboard</a>
  </div>

</div>

</body>
</html>
