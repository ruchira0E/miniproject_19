<?php
session_start();

include "action/connect.php"; // ปรับ path ตามตำแหน่งไฟล์จริงของคุณ

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// login ถูก
if ($user) {
    $_SESSION['username'] = $user['username'];
    header("location: index.php");
    exit;
} else {
    // login ผิด
    header("location: login.php");
    exit;
}