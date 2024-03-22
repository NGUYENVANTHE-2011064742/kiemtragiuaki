<?php
session_start();

$servername = "localhost";
$database = "QL_NhanSu";
$username = "root";
$password = "";

// Xử lý khi người dùng submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay không
    if (!empty($_POST['ma_nv']) && !empty($_POST['ten_nv']) && !empty($_POST['phai']) && !empty($_POST['noi_sinh']) && !empty($_POST['ma_phong']) && !empty($_POST['luong'])) {
        // Lấy dữ liệu từ form
        $ma_nv = $_POST['ma_nv'];
        $ten_nv = $_POST['ten_nv'];
        $phai = $_POST['phai'];
        $noi_sinh = $_POST['noi_sinh'];
        $ma_phong = $_POST['ma_phong'];
        $luong = $_POST['luong'];

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Thêm dữ liệu vào bảng NHANVIEN
        $sql = "INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES ('$ma_nv', '$ten_nv', '$phai', '$noi_sinh', '$ma_phong', '$luong')";
        if (mysqli_query($conn, $sql)) {
            echo "Thêm nhân viên thành công";
            header("location: ListNhanVien.php");
        } else {
            echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "Vui lòng điền đầy đủ thông tin";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>
<body>
    <h2>Add New Employee</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="ma_nv">Ma_NV:</label><br>
        <input type="text" id="ma_nv" name="ma_nv"><br>
        <label for="ten_nv">Ten_NV:</label><br>
        <input type="text" id="ten_nv" name="ten_nv"><br>
        <label for="phai">Phai:</label><br>
        <input type="text" id="phai" name="phai"><br>
        <label for="noi_sinh">Noi_Sinh:</label><br>
        <input type="text" id="noi_sinh" name="noi_sinh"><br>
        <label for="ma_phong">Ma_Phong:</label><br>
        <input type="text" id="ma_phong" name="ma_phong"><br>
        <label for="luong">Luong:</label><br>
        <input type="text" id="luong" name="luong"><br><br>
        <button type="submit">Thêm Nhân Viên</button>
    </form>
</body>
</html>
