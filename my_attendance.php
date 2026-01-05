<?php
require "auth.php";
require "config.php";
require_login();

/* ✅ ใช้ emp_code แทน username */
$emp_code = $_SESSION['user']['emp_code'];

$sql = "SELECT action, datetime
        FROM attendance
        WHERE emp_code = ?
        ORDER BY datetime DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $emp_code);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>ประวัติของฉัน</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#4f46e5;
  --bg:#f3f4f6;
  --card:#ffffff;
  --text:#111827;
  --muted:#6b7280;
  --in:#16a34a;
  --out:#dc2626;
}

*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,sans-serif;}

body{
  margin:0;
  background:var(--bg);
  color:var(--text);
}

/* Header */
.header{
  background:linear-gradient(135deg,var(--primary),#4338ca);
  color:#fff;
  padding:28px 20px;
  text-align:center;
}

/* Layout */
.container{
  max-width:900px;
  margin:-35px auto 40px;
  padding:0 15px;
}

.card{
  background:var(--card);
  border-radius:18px;
  box-shadow:0 15px 40px rgba(0,0,0,.12);
  padding:25px;
}

/* Table */
.table-wrap{overflow-x:auto;}

table{
  width:100%;
  border-collapse:collapse;
  margin-top:15px;
}

th,td{
  padding:12px;
  border-bottom:1px solid #e5e7eb;
  text-align:center;
  font-size:14px;
}

th{
  background:#f8fafc;
  font-weight:600;
  color:#374151;
}

tr:hover{
  background:#f9fafb;
}

/* Badge */
.badge{
  padding:6px 14px;
  border-radius:999px;
  font-size:13px;
  font-weight:600;
  color:#fff;
}

.in{background:var(--in);}
.out{background:var(--out);}

/* Back */
.back{
  margin-top:20px;
  text-align:right;
}

.back a{
  text-decoration:none;
  color:var(--primary);
  font-weight:600;
}
.back a:hover{text-decoration:underline;}
</style>
</head>

<body>

<div class="header">
  <h1>👤 ประวัติการเข้า–ออกงาน</h1>
  <p>ข้อมูลเฉพาะของคุณ</p>
</div>

<div class="container">
  <div class="card">

    <h3>📋 ประวัติของฉัน</h3>

    <div class="table-wrap">
      <table>
        <tr>
          <th>สถานะ</th>
          <th>วันเวลา</th>
        </tr>

        <?php if ($result->num_rows === 0): ?>
          <tr>
            <td colspan="2" style="color:#6b7280;padding:20px;">
              ยังไม่มีข้อมูลการเข้า–ออก
            </td>
          </tr>
        <?php endif; ?>

        <?php while($r = $result->fetch_assoc()): ?>
        <tr>
          <td>
            <?php if ($r['action'] === 'IN'): ?>
              <span class="badge in">IN</span>
            <?php else: ?>
              <span class="badge out">OUT</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($r['datetime']) ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <div class="back">
      <a href="dashboard.php">← กลับ Dashboard</a>
    </div>

  </div>
</div>

</body>
</html>
