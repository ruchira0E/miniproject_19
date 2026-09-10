<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

include "action/connect.php";

if (!isset($_GET['id'])) {
    die("ไม่พบ id ที่ต้องการแก้ไข");
}

$id = $_GET['id'];

session_start();
if (!isset($_SESSION["username"]))
    
$sql = "SELECT `movie_id`, `title`, `release_year`, `duration_min`, `genre_id`, `cover_image` 
        FROM `movies` 
        WHERE `movie_id` = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("ไม่พบข้อมูลหนัง");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลหนัง</title>
</head>
<body>

    <a href="index.php">&laquo; กลับหน้ารายการหนัง</a>
    <h2>แก้ไขข้อมูลหนัง</h2>

    <form action="action/update_movie.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="movie_id" value="<?= $movie['movie_id'] ?>">
        <input type="hidden" name="old_cover_image" value="<?= htmlspecialchars($movie['cover_image'] ?? '') ?>">

        <label>ชื่อเรื่อง</label>
        <input type="text" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required><br>

        <label>ปีที่ฉาย</label>
        <input type="number" name="release_year" value="<?= $movie['release_year'] ?>" required><br>

        <label>ความยาว (นาที)</label>
        <input type="number" name="duration_min" value="<?= $movie['duration_min'] ?>" required><br>

        <label>หมวดหมู่ (genre_id)</label>
        <input type="number" name="genre_id" value="<?= $movie['genre_id'] ?>" required><br>

        <label>รูปปกปัจจุบัน</label><br>
        <?php if (!empty($movie['cover_image'])) { ?>
            <img src="uploads/<?= htmlspecialchars($movie['cover_image']) ?>" width="150"><br>
        <?php } else { ?>
            (ยังไม่มีรูป)<br>
        <?php } ?>

        <label>เปลี่ยนรูปปก (ถ้าไม่เลือก จะใช้รูปเดิม)</label>
        <input type="file" name="cover_image" accept="image/*"><br>

        <button type="submit">บันทึกการแก้ไข</button>
    </form>

</body>
</html>