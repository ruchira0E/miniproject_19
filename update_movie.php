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
$error = "";

// ถ้ามีการ submit ฟอร์มมา (POST) ให้ทำการอัปเดตก่อน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $release_year = $_POST["release_year"];
    $duration_min = $_POST["duration_min"];
    $genre_id = $_POST["genre_id"];
    
    // รับค่า URL จากช่องกรอกข้อความ (ถ้าว่างไว้ให้ใช้ URL เดิม)
    $cover_image = !empty($_POST["cover_image_url"]) ? $_POST["cover_image_url"] : $_POST["old_cover_image"];

    $sql = "UPDATE `movies` 
            SET `title` = ?, `release_year` = ?, `duration_min` = ?, `genre_id` = ?, `cover_image` = ?
            WHERE `movie_id` = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "siiisi", $title, $release_year, $duration_min, $genre_id, $cover_image, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        $error = "เกิดข้อผิดพลาด แก้ไขไม่สำเร็จ";
    }
}

// ดึงข้อมูลหนังปัจจุบันมาแสดงในฟอร์ม
$sql = "SELECT `movie_id`, `title`, `release_year`, `duration_min`, `genre_id`, `cover_image` 
        FROM `movies` WHERE `movie_id` = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("ไม่พบข้อมูลหนัง");
}

$genre_sql = "SELECT `genre_id`, `genre_name` FROM `genres`";
$genre_result = mysqli_query($con, $genre_sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลหนัง</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #08070b;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(229, 9, 20, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(184, 29, 36, 0.1) 0%, transparent 40%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #f5f5f1;
        }

        .form-card {
            background: rgba(18, 18, 22, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(229, 9, 20, 0.5);
            border-radius: 20px;
            padding: 36px 32px;
            width: 100%;
            max-width: 440px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.6),
                0 0 40px rgba(229, 9, 20, 0.12);
            position: relative;
        }

        .back-link {
            color: #a0a0ab;
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 24px;
            transition: all 0.25s ease;
        }

        .back-link:hover {
            color: #e50914;
            transform: translateX(-4px);
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.5px;
        }

        h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 22px;
            background: #e50914;
            border-radius: 4px;
            box-shadow: 0 0 10px #e50914;
        }

        .alert-error {
            background: rgba(229, 9, 20, 0.2);
            border: 1px solid rgba(229, 9, 20, 0.5);
            color: #ff4d4d;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #c0c0c8;
            letter-spacing: 0.3px;
        }

        input[type="text"],
        input[type="number"],
        input[type="url"],
        select {
            width: 100%;
            padding: 13px 16px;
            background: rgba(10, 10, 14, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #ffffff;
            font-size: 15px;
            outline: none;
            transition: all 0.25s ease;
        }

        /* ตกแต่ง Dropdown / Select */
        select {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23a0a0ab' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }

        select option {
            background-color: #121216;
            color: #ffffff;
            padding: 10px;
        }

        input[type="text"]::placeholder,
        input[type="url"]::placeholder {
            color: #555560;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="url"]:focus,
        select:focus {
            background-color: rgba(15, 15, 20, 0.9);
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.2);
        }

        .preview-box {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(10, 10, 14, 0.4);
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .preview-img {
            width: 60px;
            height: 85px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .no-img {
            font-size: 13px;
            color: #888890;
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e50914 0%, #b81d24 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.35);
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #f4121d 0%, #c92027 100%);
            box-shadow: 0 6px 20px rgba(229, 9, 20, 0.5);
            transform: translateY(-1px);
        }

        button[type="submit"]:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>

    <form class="form-card" action="" method="post">
        <a href="index.php" class="back-link">&#8592; กลับหน้ารายการหนัง</a>
        <h2>แก้ไขข้อมูลหนัง</h2>

        <?php if (!empty($error)) { ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php } ?>

        <input type="hidden" name="old_cover_image" value="<?= htmlspecialchars($movie['cover_image'] ?? '') ?>">

        <div class="input-group">
            <label>ชื่อเรื่อง</label>
            <input type="text" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required>
        </div>

        <div class="input-group">
            <label>ปีที่ฉาย</label>
            <input type="number" name="release_year" value="<?= $movie['release_year'] ?>" required>
        </div>

        <div class="input-group">
            <label>ความยาว (นาที)</label>
            <input type="number" name="duration_min" value="<?= $movie['duration_min'] ?>" required>
        </div>

        <div class="input-group">
            <label>หมวดหมู่</label>
            <select name="genre_id" required>
                <option value="">เลือกหมวดหมู่</option>
                <?php while ($genre = mysqli_fetch_assoc($genre_result)) { ?>
                    <option value="<?= $genre['genre_id'] ?>" <?= ($genre['genre_id'] == $movie['genre_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($genre['genre_name']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="input-group">
            <label>รูปปกปัจจุบัน</label>
            <div class="preview-box">
                <?php if (!empty($movie['cover_image'])) { ?>
                    <img src="<?= htmlspecialchars($movie['cover_image']) ?>" class="preview-img" alt="Current Cover" onerror="this.src='https://via.placeholder.com/60x85/1a1a20/888888?text=No+Img';">
                    <span style="font-size: 13px; color: #a0a0ab;">รูปภาพที่ใช้งานอยู่</span>
                <?php } else { ?>
                    <span class="no-img">(ยังไม่มีรูป)</span>
                <?php } ?>
            </div>
        </div>

        <!-- ช่องกรอก URL รูปภาพ -->
        <div class="input-group">
            <label>ลิงก์รูปปกภาพยนตร์ (URL)</label>
            <input type="text" name="cover_image_url" value="<?= htmlspecialchars($movie['cover_image'] ?? '') ?>" placeholder="วางลิงก์รูปภาพ เช่น https://example.com/poster.jpg">
        </div>

        <button type="submit">บันทึกการแก้ไข</button>
    </form>

</body>
</html>