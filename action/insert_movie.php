<?php
include "connect.php";

$title = $_POST["title"];
$release_year = $_POST["release_year"];
$duration_min = $_POST["duration_min"];
$genre_id = $_POST["genre_id"];

$sql = "INSERT INTO `movies` (`title`, `release_year`, `duration_min`, `genre_id`) 
        VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "siii", $title, $release_year, $duration_min, $genre_id);
$result = mysqli_stmt_execute($stmt);

if (!$result) {
    echo "Error";
} else {
    header("Location: ../index.php"); // เช็คชื่อไฟล์ปลายทางด้วยนะ (ดูข้อ 4 ที่แล้ว)
    exit;
}