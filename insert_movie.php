<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลหนัง</title>
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
            transition: transform 0.3s ease;
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
        input[type="file"] {
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

        /* ตกแต่งปุ่มเลือกไฟล์ */
        input[type="file"]::-webkit-file-upload-button {
            background: #e50914;
            color: #ffffff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
            font-family: 'Kanit', sans-serif;
            transition: background 0.2s ease;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background: #b81d24;
        }

        input[type="text"]::placeholder,
        input[type="number"]::placeholder {
            color: #555560;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="file"]:focus {
            background: rgba(15, 15, 20, 0.9);
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.2);
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
            box-shadow: 0 2px 10px rgba(229, 9, 20, 0.3);
        }
    </style>
</head>
<body>

    <!-- เพิ่ม enctype="multipart/form-data" เพื่อให้รองรับไฟล์รูปภาพ -->
    <form class="form-card" action="action/insert_movie.php" method="post" enctype="multipart/form-data">
        <a href="index.php" class="back-link">&#8592; กลับหน้ารายการหนัง</a>
        <h2>เพิ่มข้อมูลหนัง</h2>

        <div class="input-group">
            <label for="title">ชื่อเรื่อง</label>
            <input type="text" id="title" name="title" placeholder="" required>
        </div>

        <div class="input-group">
            <label for="release_year">ปีที่ฉาย (ค.ศ.)</label>
            <input type="number" id="release_year" name="release_year" placeholder="" min="1888" max="2100" required>
        </div>

        <div class="input-group">
            <label for="duration_min">ความยาว (นาที)</label>
            <input type="number" id="duration_min" name="duration_min" placeholder="" min="1" required>
        </div>

        <div class="input-group">
            <label for="genre_id">รหัสหมวดหมู่ (ID)</label>
            <input type="number" id="genre_id" name="genre_id" placeholder="" min="1" required>
        </div>


        <div class="input-group">
            <label for="cover_image">รูปปกภาพยนตร์ (URL)</label>
            <input type="text" id="cover_image" name="cover_image" placeholder="">
        </div>

        <button type="submit">บันทึกข้อมูล</button>
    </form>

</body>
</html>