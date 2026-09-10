<?php
include "connect.php";

$title = $_POST["title"];
$release_year = $_POST["release_year"];
$duration_min = $_POST["duration_min"];
$genre_id = $_POST["genre_id"];

$cover_image = null;

if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
    $cover_image = uniqid() . '.' . $ext; // ตั้งชื่อไฟล์ใหม่กันชื่อซ้ำ

    $target_path = "../uploads/" . $cover_image;
    move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_path);
}


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