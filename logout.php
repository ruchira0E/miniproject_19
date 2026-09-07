<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กำลังออกจากระบบ...</title>
    <!-- หน่วงเวลา 1.5 วินาที แล้ว redirect ไปยัง login.php -->
    <meta http-equiv="refresh" content="1.5;url=login.php">
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

        .logout-card {
            background: rgba(18, 18, 22, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(229, 9, 20, 0.5);
            border-radius: 20px;
            padding: 40px 32px;
            width: 100%;
            max-width: 380px;
            text-align: center;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.6),
                0 0 40px rgba(229, 9, 20, 0.12);
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid #e50914;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffffff;
        }

        p {
            font-size: 14px;
            color: #a0a0ab;
            margin-bottom: 20px;
        }

        .manual-link {
            color: #e50914;
            text-decoration: none;
            font-size: 13px;
            transition: opacity 0.2s;
        }

        .manual-link:hover {
            text-decoration: underline;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="logout-card">
        <div class="spinner"></div>
        <h2>ออกจากระบบสำเร็จ</h2>
        <p>กำลังพากลับไปยังหน้าเข้าสู่ระบบ...</p>
        <a href="login.php" class="manual-link">หากหน้าจอไม่เปลี่ยน คลิกที่นี่</a>
    </div>

</body>
</html>