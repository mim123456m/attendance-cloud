<?php
require "config.php";

// รับ JSON จาก ESP32
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    http_response_code(400);
    exit("No data");
}

$uid    = $data['uid'];
$code   = $data['code'];
$name   = $data['name'];
$action = $data['action'];
$ts     = $data['ts'];
$date   = $data['date'];
$year   = $data['year'];
$month  = $data['month'];
$day    = $data['day'];

$sql = "INSERT INTO attendance 
(uid, emp_code, emp_name, action, datetime, date, year, month, day)
VALUES (?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssiii",
    $uid, $code, $name, $action, $ts, $date, $year, $month, $day
);

$stmt->execute();
echo "OK";
