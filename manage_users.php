<?php
require "config.php";
require "auth.php";
require_admin();

$res = $conn->query("
  SELECT id, username, role, emp_code 
  FROM users 
  ORDER BY role DESC, username ASC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>User Management | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
  --primary:#2563eb;
  --bg:#f1f5f9;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
  --border:#e5e7eb;
  --admin:#16a34a;
  --user:#64748b;
  --danger:#dc2626;
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

.btn{
  padding:6px 12px;
  border-radius:8px;
  text-decoration:none;
  font-size:13px;
  font-weight:600;
  display:inline-block;
}
.btn-add{background:var(--primary);color:#fff;}
.btn-edit{background:#0ea5e9;color:#fff;}
.btn-reset{background:#f59e0b;color:#fff;}
.btn-del{background:var(--danger);color:#fff;}
.btn:hover{opacity:.9;}

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
  padding:5px 12px;
  border-radius:999px;
  font-size:12px;
  font-weight:600;
}
.admin{background:#dcfce7;color:var(--admin);}
.user{background:#e5e7eb;color:var(--user);}

.manager{
  background:#fff7ed;
  color:#ea580c;
}


.footer{margin-top:15px;text-align:right;font-size:13px;color:var(--muted);}
</style>
</head>

<body>

<div class="header">
  <h1>👥 User Management</h1>
  <p>ระบบจัดการผู้ใช้งาน (Admin Only)</p>
</div>

<div class="container">
  <div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;">
      <h3>รายชื่อผู้ใช้ในระบบ</h3>
      <!-- ✅ แก้ตรงนี้ -->
    <a href="user_add.php" class="btn btn-add">➕ เพิ่มผู้ใช้</a>

    </div>

    <div class="table-wrap">
      <table>
        <tr>
          <th>Username</th>
          <th>Emp Code</th>
          <th>Role</th>
          <th>Action</th>
        </tr>

        <?php while($u = $res->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['emp_code']) ?></td>
          <td>
          <?php if ($u['role'] === 'admin'): ?>
            <span class="badge admin">ADMIN</span>

          <?php elseif ($u['role'] === 'manager'): ?>
              <span class="badge manager">MANAGER</span>

            <?php else: ?>
              <span class="badge user">USER</span>
            <?php endif; ?>

          </td>
          <td>
            <a class="btn btn-edit"  href="user_edit.php?id=<?= $u['id'] ?>">Edit</a>
            <a class="btn btn-reset" href="user_reset.php?id=<?= $u['id'] ?>"
               onclick="return confirm('Reset password ผู้ใช้นี้?')">Reset</a>
            <a class="btn btn-del"   href="user_delete.php?id=<?= $u['id'] ?>"
               onclick="return confirm('ลบผู้ใช้นี้ถาวร?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <div class="footer">
      ระบบนี้มี Audit Log ทุกการกระทำของ Admin
    </div>

  </div>
</div>

</body>
</html>
