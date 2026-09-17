<?php
include "connect.php";

$title = $_POST["title"];
$release_year = $_POST["release_year"];
$duration_min = $_POST["duration_min"];
$genre_id = $_POST["genre_id"];
$cover_image = $_POST["cover_image"]; // รับเป็น URL ข้อความ ไม่ใช่ไฟล์

$sql = "INSERT INTO `movies`
        (`title`, `release_year`, `duration_min`, `genre_id`, `cover_image`) 
        VALUES 
        (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "siiis", $title, $release_year, $duration_min, $genre_id, $cover_image);
$result = mysqli_stmt_execute($stmt);

if (!$result) {
    echo "Error";
} else {
    header("Location: ../index.php"); 
    exit;
}