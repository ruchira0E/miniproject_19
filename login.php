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
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username หรือ Password ไม่ถูกต้อง!";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

    <?php if (!empty($error)) { ?>
        <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form action="" method="post">
        <label for="username">username</label>
        <input type="text" id="username" name="username"> <br>

        <label for="password">password</label>
        <input type="password" id="password" name="password"> <br>

        <button>Login</button>
    </form>

</body>
</html>