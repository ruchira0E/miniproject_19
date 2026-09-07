<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include "connect.php";

if (!isset($_GET['id'])) {
    die("ไม่พบ id ที่จะลบ");
}

$id = $_GET['id'];

$sql = "DELETE FROM movies WHERE movie_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id); // "i" = integer เพราะ movie_id น่าจะเป็นเลข
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) === 0) {
    echo "Error หรือไม่พบข้อมูลที่จะลบ";
} else {
    header("Location: ../manage_menu.php");
    exit;
}