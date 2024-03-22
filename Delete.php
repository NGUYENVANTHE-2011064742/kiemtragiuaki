<?php
session_start();

$servername = "localhost";
$database = "QL_NhanSu";
$username = "root";
$password = "";

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    // Nếu chưa, chuyển hướng đến trang đăng nhập
    header("location: login.php");
    exit;
}

// Lấy mã nhân viên từ request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ma_nv'])) {
    $ma_nv = $_POST['ma_nv'];

    // Xóa nhân viên từ cơ sở dữ liệu
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "DELETE FROM NHANVIEN WHERE Ma_NV='$ma_nv'";
    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng trở lại trang ListNhanVien.php
        header("location: ListNhanVien.php");
        exit;
    } else {
        echo "Lỗi khi xóa nhân viên: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Mã nhân viên không được cung cấp";
}
?>
