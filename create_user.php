<?php
require "config.php";

$username = "admin";
$password = "1234";
$role = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$conn->query("DELETE FROM users WHERE username='admin'");
$conn->query("INSERT INTO users (username, password, role)
              VALUES ('$username', '$hash', '$role')");

echo "Admin user created successfully";
