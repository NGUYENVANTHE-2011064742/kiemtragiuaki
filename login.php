<?php
session_start();

$servername = "localhost";
$database = "QL_NhanSu";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Nếu thông tin đăng nhập hợp lệ, lưu tên người dùng vào session và chuyển hướng đến trang ListNhanVien.php
        $_SESSION['username'] = $username;
        header("location: ListNhanVien.php");
        exit;
    } else {
        echo "Đăng nhập không thành công. Vui lòng kiểm tra lại tên đăng nhập và mật khẩu.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method="POST">
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Đăng nhập">
    </form>
</body>
</html>
