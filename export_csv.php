<?php
require "config.php";

/* ===== ตั้งค่า Header สำหรับ CSV ===== */
$filename = "attendance_" . date("Y-m-d") . ".csv";
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename");

/* ===== UTF-8 BOM (แก้ภาษาไทยเพี้ยนใน Excel) ===== */
echo "\xEF\xBB\xBF";

/* ===== เปิด output stream ===== */
$out = fopen("php://output", "w");

/* ===== หัวตาราง ===== */
fputcsv($out, [
  "รหัสพนักงาน",
  "ชื่อพนักงาน",
  "สถานะ",
  "วันเวลา"
]);

/* ===== Query ข้อมูล ===== */
$sql = "
SELECT 
  emp_code,
  emp_name,
  action,
  datetime
FROM attendance
ORDER BY datetime DESC
";

$result = $conn->query($sql);

/* ===== เขียนข้อมูลลง CSV ===== */
while ($row = $result->fetch_assoc()) {
  fputcsv($out, [
    $row['emp_code'],
    $row['emp_name'],
    $row['action'],
    $row['datetime']
  ]);
}

/* ===== ปิด stream ===== */
fclose($out);
exit;
