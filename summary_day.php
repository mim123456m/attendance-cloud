<?php
session_start();
require "config.php";

// ถ้าเปิดใช้งาน login
// if (!isset($_SESSION['user'])) header("Location: login.php");

$date = $_GET['date'] ?? date('Y-m-d');

$sql = "
SELECT 
  a1.emp_code,
  a1.emp_name,
  MIN(a1.datetime) AS time_in,
  MAX(a2.datetime) AS time_out,
  TIMESTAMPDIFF(MINUTE, MIN(a1.datetime), MAX(a2.datetime)) AS minutes_worked
FROM attendance a1
JOIN attendance a2
  ON a1.emp_code = a2.emp_code
  AND a1.date = a2.date
WHERE a1.action = 'IN'
  AND a2.action = 'OUT'
  AND a1.date = ?
GROUP BY a1.emp_code, a1.emp_name
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Daily Summary | Attendance</title>
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

/* ===== Filter ===== */
.filter{
  display:flex;
  align-items:center;
  gap:12px;
  margin-bottom:20px;
  flex-wrap:wrap;
}

.filter label{
  font-size:14px;
  color:var(--muted);
}

.filter input[type=date]{
  padding:8px 12px;
  border-radius:10px;
  border:1px solid var(--border);
}

.filter button{
  padding:8px 16px;
  border:none;
  border-radius:10px;
  background:var(--primary);
  color:#fff;
  cursor:pointer;
  font-weight:600;
}

.filter button:hover{
  opacity:.9;
}

/* ===== Table ===== */
.table-wrap{
  overflow-x:auto;
}

table{
  width:100%;
  border-collapse:collapse;
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
  <h1>📅 Daily Attendance Summary</h1>
  <p>สรุปเวลาทำงานรายวัน</p>
</div>

<div class="container">
  <div class="card">

    <form class="filter" method="get">
      <label>เลือกวันที่</label>
      <input type="date" name="date" value="<?= $date ?>">
      <button type="submit">ดูข้อมูล</button>
    </form>

    <div class="table-wrap">
      <table>
        <tr>
          <th>รหัสพนักงาน</th>
          <th>ชื่อพนักงาน</th>
          <th>เวลาเข้า</th>
          <th>เวลาออก</th>
          <th>ชั่วโมงทำงาน</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): 
          $hours = round($row['minutes_worked'] / 60, 2);
        ?>
        <tr>
          <td><?= $row['emp_code'] ?></td>
          <td><?= $row['emp_name'] ?></td>
          <td><?= $row['time_in'] ?></td>
          <td><?= $row['time_out'] ?></td>
          <td><strong><?= $hours ?></strong> ชม.</td>
        </tr>
        <?php endwhile; ?>

      </table>
    </div>

    <div class="footer">
      สรุปข้อมูลประจำวันที่ <?= $date ?>
    </div>

  </div>
</div>

</body>
</html>
