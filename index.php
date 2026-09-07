<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

include "action/connect.php";

$sql = "SELECT movies.movie_id, movies.title, movies.release_year, movies.duration_min, genres.genre_name 
        FROM movies 
        LEFT JOIN genres ON movies.genre_id = genres.genre_id";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Catalog - รายการภาพยนตร์</title>
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
            padding: 40px 20px;
            color: #f5f5f1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background: rgba(18, 18, 22, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(229, 9, 20, 0.5);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.6),
                0 0 40px rgba(229, 9, 20, 0.12);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-info {
            font-size: 15px;
            color: #c0c0c8;
        }

        .user-info span {
            color: #ffffff;
            font-weight: 600;
        }

        .btn-logout {
            color: #a0a0ab;
            text-decoration: none;
            font-size: 13px;
            padding: 6px 12px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            transition: all 0.25s ease;
            margin-left: 10px;
        }

        .btn-logout:hover {
            color: #e50914;
            border-color: #e50914;
            background: rgba(229, 9, 20, 0.1);
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
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

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #e50914 0%, #b81d24 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.35);
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #f4121d 0%, #c92027 100%);
            box-shadow: 0 6px 20px rgba(229, 9, 20, 0.5);
            transform: translateY(-1px);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            background: rgba(10, 10, 14, 0.4);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
        }

        th {
            background: rgba(255, 255, 255, 0.03);
            color: #c0c0c8;
            font-weight: 500;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        td {
            color: #f5f5f1;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: rgba(229, 9, 20, 0.15);
            color: #ff4d4d;
            border: 1px solid rgba(229, 9, 20, 0.3);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 400;
        }

        .action-links {
            display: flex;
            gap: 12px;
        }

        .action-links a {
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .btn-edit {
            color: #4da6ff;
        }

        .btn-edit:hover {
            color: #80c0ff;
            text-decoration: underline;
        }

        .btn-delete {
            color: #ff4d4d;
        }

        .btn-delete:hover {
            color: #ff8080;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="user-info">
                สวัสดีคุณ <span><?= htmlspecialchars($_SESSION["username"]) ?></span>
            </div>
            <a href="logout.php" class="btn-logout">ออกจากระบบ</a>
        </div>

        <div class="actions">
            <h2>รายการภาพยนตร์</h2>
            <a href="insert_movie.php" class="btn-add">+ เพิ่มข้อมูลหนัง</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="8%">รหัส</th>
                        <th width="35%">ชื่อเรื่อง</th>
                        <th width="15%">ปีที่ฉาย</th>
                        <th width="15%">ความยาว</th>
                        <th width="15%">หมวดหมู่</th>
                        <th width="12%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>#<?= $row['movie_id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                            <td><?= $row['release_year'] ?></td>
                            <td><?= $row['duration_min'] ?> นาที</td>
                            <td>
                                <span class="badge">
                                    <?= htmlspecialchars($row['genre_name'] ?? 'ไม่ระบุ') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="action/update_movie.php?id=<?= $row['movie_id'] ?>" class="btn-edit">แก้ไข</a>
                                    <a href="action/delete_movie.php?id=<?= $row['movie_id'] ?>" class="btn-delete" onclick="return confirm('ยืนยันการลบภาพยนตร์เรื่องนี้?');">ลบ</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>