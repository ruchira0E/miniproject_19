<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

include "action/connect.php";

// ดึง cover_image มาเพิ่มด้วย
$sql = "SELECT movies.movie_id, movies.title, movies.release_year, movies.duration_min, movies.cover_image, genres.genre_name 
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
            color: #f5f5f1;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Style */
        .navbar {
            width: 100%;
            background: rgba(18, 18, 22, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .navbar-brand span {
            color: #e50914;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #c0c0c8;
        }

        .navbar-user strong {
            color: #ffffff;
        }

        .btn-logout {
            color: #a0a0ab;
            text-decoration: none;
            font-size: 13px;
            padding: 6px 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            transition: all 0.25s ease;
        }

        .btn-logout:hover {
            color: #e50914;
            border-color: #e50914;
            background: rgba(229, 9, 20, 0.1);
        }

        /* Main Content Style */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 1050px;
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
            vertical-align: middle;
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

        .movie-poster {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: #121216;
            display: block;
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

        /* Footer Style */
        .footer {
            width: 100%;
            background: rgba(12, 12, 15, 0.85);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #888890;
        }

        .footer p span {
            color: #e50914;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            MOVIE<span>CATALOG</span>
        </div>
        <div class="navbar-user">
            <span>ผู้ใช้งาน: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></span>
            <a href="logout.php" class="btn-logout">ออกจากระบบ</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="actions">
                <h2>รายการภาพยนตร์</h2>
                <a href="insert_movie.php" class="btn-add">+ เพิ่มข้อมูลหนัง</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="8%">รหัส</th>
                            <th width="10%">รูปปก</th>
                            <th width="30%">ชื่อเรื่อง</th>
                            <th width="12%">ปีที่ฉาย</th>
                            <th width="15%">ความยาว</th>
                            <th width="13%">หมวดหมู่</th>
                            <th width="12%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td>#<?= $row['movie_id'] ?></td>
                                <td>
                                    <?php if (!empty($row['cover_image'])) { ?>
                                        <img src="<?= htmlspecialchars($row['cover_image']) ?>" class="movie-poster" alt="Poster" onerror="this.src='https://via.placeholder.com/50x70/1a1a20/888888?text=No+Img';">
                                    <?php } else { ?>
                                        <img src="https://via.placeholder.com/50x70/1a1a20/888888?text=No+Img" class="movie-poster" alt="No Cover">
                                    <?php } ?>
                                </td>
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
                                        <a href="update_movie.php?id=<?= $row['movie_id'] ?>" class="btn-edit">แก้ไข</a>
                                        <a href="action/delete_movie.php?id=<?= $row['movie_id'] ?>" class="btn-delete" onclick="return confirm('ยืนยันการลบภาพยนตร์เรื่องนี้?');">ลบ</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; Movie Catalog System | จัดทำโดย <span>รุจิรา ผาด่านแก้ว เลขที่ 19 BIT.2/4</span></p>
    </footer>

</body>
</html>