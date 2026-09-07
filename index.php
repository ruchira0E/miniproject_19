<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
        session_start();
        include "action/connect.php";

        $error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $_SESSION['username'] = $username; // ให้ตรงกับ index.php
                header("Location: index.php");
                exit();
            } else {
                $error = "Username หรือ Password ไม่ถูกต้อง!";
            }
        }
        ?>

        สวัสดี คุณ <?=  $_SESSION["username"] ?>

        <a href="logout.php">Logout</a>

        <br><br>
        <a href="insert_movie.php">+ เพิ่มข้อมูลหนัง</a>
        <br><br>

        <!-- ตารางแสดงรายการหนัง -->
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>รหัส</th>
                <th>ชื่อเรื่อง</th>
                <th>ปีที่ฉาย</th>
                <th>ความยาว (นาที)</th>
                <th>หมวดหมู่</th>
                <th>จัดการ</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['movie_id'] ?></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['release_year'] ?></td>
                    <td><?= $row['duration_min'] ?></td>
                    <td><?= $row['genre_name'] ?></td>
                    <td>
                        <!-- ลิงก์ชี้ไปที่ action/update_movie.php ให้ตรงกับไฟล์ที่คุณมี -->
                        <a href="action/update_movie.php?id=<?= $row['movie_id'] ?>">แก้ไข</a> | 
                        <a href="action/delete_movie.php?id=<?= $row['movie_id'] ?>" onclick="return confirm('ยืนยันการลบ?');">ลบ</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

</body>
</html>