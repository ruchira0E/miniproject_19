<?php
include "connect.php";

if (!isset($_GET['id'])) {
    die("ไม่พบ id ที่ต้องการค้นหา");
}

$id = $_GET['id'];

$sql = "SELECT `movie_id`, `title`, `release_year`, `duration_min`, `genre_id` 
        FROM `movies` 
        WHERE `movie_id` = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id); // "i" = integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("ไม่พบข้อมูลหนัง");
}