<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

$role = $_SESSION['user']['role'];   // admin | user
$username = $_SESSION['user']['username'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#4f46e5;
  --primary-dark:#4338ca;
  --bg:#f3f4f6;
  --card:#ffffff;
  --text:#111827;
  --muted:#6b7280;
  --danger:#dc2626;
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
  background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  color:#fff;
  padding:32px 20px;
  text-align:center;
}

/* ===== Container ===== */
.container{
  max-width:1000px;
  margin:-40px auto 40px;
  padding:0 15px;
}

.card{
  background:var(--card);
  border-radius:18px;
  box-shadow:0 15px 40px rgba(0,0,0,.12);
  padding:30px;
}

/* ===== User Info ===== */
.user-info{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:25px;
  flex-wrap:wrap;
  gap:10px;
}

.user-info h2{
  margin:0;
}

.role{
  padding:6px 16px;
  border-radius:999px;
  font-size:14px;
  color:#fff;
}

.role.admin{
  background:#16a34a; /* เขียว = อำนาจสูงสุด */
}

.role.manager{
  background:#f59e0b; /* ส้ม = ผู้จัดการ */
}

.role.user{
  background:#2563eb; /* น้ำเงิน = ผู้ใช้ทั่วไป */
}


/* ===== Menu ===== */
.menu{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
  gap:20px;
}

.menu a{
  text-decoration:none;
  background:#f9fafb;
  border-radius:16px;
  padding:22px;
  display:flex;
  align-items:center;
  gap:16px;
  color:var(--text);
  border:1px solid #e5e7eb;
  transition:.25s;
}

.menu a:hover{
  transform:translateY(-6px);
  box-shadow:0 12px 30px rgba(0,0,0,.12);
  background:#fff;
}

.icon{
  font-size:30px;
}

.menu strong{
  font-size:16px;
}

.menu small{
  color:var(--muted);
}

/* ===== Logout ===== */
.logout{
  margin-top:30px;
  text-align:right;
}

.logout a{
  color:var(--danger);
  text-decoration:none;
  font-weight:600;
}

.logout a:hover{
  text-decoration:underline;
}

/* ===== Responsive ===== */
@media(max-width:600px){
  .user-info{
    flex-direction:column;
    align-items:flex-start;
  }
}
</style>
</head>

<body>

<div class="header">
  <h1>📊 Attendance Dashboard</h1>
  <p>ระบบจัดการเวลาเข้า–ออกงาน</p>
</div>

<div class="container">
  <div class="card">

    <!-- User Info -->
    <div class="user-info">
      <h2>สวัสดี, <?= htmlspecialchars($username) ?></h2>
      <span class="role <?= $role ?>">
        <?= strtoupper($role) ?>
      </span>
    </div>

    <!-- Menu -->
    <div class="menu">

      <!-- ทุกคนเห็น -->
      <a href="my_attendance.php">
        <span class="icon">👤</span>
        <div>
          <strong>ประวัติของฉัน</strong><br>
          <small>ดูข้อมูลเข้า–ออกของตัวเอง</small>
        </div>
      </a>

      <?php if (in_array($role, ['admin','manager'])): ?>


        <a href="index.php">
          <span class="icon">📋</span>
          <div>
            <strong>ประวัติทั้งหมด</strong><br>
            <small>ดูข้อมูลเข้า–ออกทั้งหมด</small>
          </div>
        </a>

        <a href="summary_day.php">
          <span class="icon">📅</span>
          <div>
            <strong>สรุปรายวัน</strong><br>
            <small>สรุปข้อมูลตามวัน</small>
          </div>
        </a>

        <a href="summary_month.php">
          <span class="icon">📆</span>
          <div>
            <strong>สรุปรายเดือน</strong><br>
            <small>ดูสถิติรายเดือน</small>
          </div>
        </a>

        <a href="export_csv.php">
          <span class="icon">📤</span>
          <div>
            <strong>Export CSV</strong><br>
            <small>ดาวน์โหลดไฟล์ข้อมูล</small>
          </div>
        </a>
      <?php if ($role === 'admin'): ?>
  <a href="admin_dashboard.php">
    <span class="icon">📊</span>
    <div>
      <strong>Admin Dashboard</strong><br>
      <small>ภาพรวมระบบ (Admin)</small>
    </div>
  </a>
          <a href="manage_users.php">
          <span class="icon">👥</span>
          <div>
            <strong>จัดการผู้ใช้</strong><br>
            <small>Admin เท่านั้น</small>
          </div>
        </a>
<?php endif; ?>
<?php if (in_array($role, ['admin','manager'])): ?>
  <a href="manager_dashboard.php">
    <span class="icon">🧑‍💼</span>
    <div>
      <strong>Manager Dashboard</strong><br>
      <small>ภาพรวมสำหรับผู้จัดการ</small>
    </div>
  </a>
<?php endif; ?>
      <?php endif; ?>

    </div>

    <!-- Logout -->
    <div class="logout">
      <a href="logout.php">🚪 Logout</a>
    </div>

  </div>
</div>

</body>
</html>