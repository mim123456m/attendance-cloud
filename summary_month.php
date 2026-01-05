<?php
session_start();
require "config.php";

// if (!isset($_SESSION['user'])) header("Location: login.php");

$year  = $_GET['year']  ?? date('Y');
$month = $_GET['month'] ?? date('m');

$sql = "
SELECT 
  emp_code,
  emp_name,
  SUM(minutes_worked) AS total_minutes
FROM (
  SELECT 
    a1.emp_code,
    a1.emp_name,
    a1.date,
    TIMESTAMPDIFF(
      MINUTE,
      MIN(a1.datetime),
      MAX(a2.datetime)
    ) AS minutes_worked
  FROM attendance a1
  JOIN attendance a2
    ON a1.emp_code = a2.emp_code
    AND a1.date = a2.date
  WHERE a1.action='IN'
    AND a2.action='OUT'
    AND a1.year = ?
    AND a1.month = ?
  GROUP BY a1.emp_code, a1.emp_name, a1.date
) t
GROUP BY emp_code, emp_name
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $year, $month);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Monthly Summary | Attendance</title>
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

.filter input{
  padding:8px 12px;
  border-radius:10px;
  border:1px solid var(--border);
  width:100px;
}

.filter button{
  padding:8px 18px;
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
  <h1>📆 Monthly Attendance Summary</h1>
  <p>สรุปเวลาทำงานรายเดือน</p>
</div>

<div class="container">
  <div class="card">

    <form class="filter" method="get">
      <label>ปี</label>
      <input type="number" name="year" value="<?= $year ?>">
      <label>เดือน</label>
      <input type="number" name="month" value="<?= $month ?>" min="1" max="12">
      <button type="submit">ดูข้อมูล</button>
    </form>

    <div class="table-wrap">
      <table>
        <tr>
          <th>รหัสพนักงาน</th>
          <th>ชื่อพนักงาน</th>
          <th>ชั่วโมงทำงานรวม</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): 
          $hours = round($row['total_minutes'] / 60, 2);
        ?>
        <tr>
          <td><?= $row['emp_code'] ?></td>
          <td><?= $row['emp_name'] ?></td>
          <td><strong><?= $hours ?></strong> ชม.</td>
        </tr>
        <?php endwhile; ?>

      </table>
    </div>

    <div class="footer">
      สรุปข้อมูลประจำเดือน <?= $month ?>/<?= $year ?>
    </div>

  </div>
</div>

</body>
</html>
