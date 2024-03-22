<?php
session_start();

$servername = "localhost";
$database = "QL_NhanSu";
$username = "root";
$password = "";

// Xử lý khi người dùng click vào nút Lưu
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

        // Update dữ liệu vào bảng NHANVIEN
        $sql = "UPDATE NHANVIEN SET Ten_NV='$ten_nv', Phai='$phai', Noi_Sinh='$noi_sinh', Ma_Phong='$ma_phong', Luong='$luong' WHERE Ma_NV='$ma_nv'";
        if (mysqli_query($conn, $sql)) {
            // Chuyển hướng đến trang ListNhanVien.php
            header("location: ListNhanVien.php");
            exit;
        } else {
            echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "Vui lòng điền đầy đủ thông tin";
    }
}

// Lấy mã nhân viên từ URL
if (isset($_GET['ma_nv'])) {
    $ma_nv = $_GET['ma_nv'];

    // Lấy thông tin nhân viên từ cơ sở dữ liệu
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM NHANVIEN WHERE Ma_NV='$ma_nv'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Không tìm thấy nhân viên";
        exit;
    }

    mysqli_close($conn);
} else {
    echo "Mã nhân viên không được cung cấp";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
</head>
<body>
    <h2>Edit Employee Information</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="ma_nv" value="<?php echo $row['Ma_NV']; ?>">
        <label for="ten_nv">Ten_NV:</label><br>
        <input type="text" id="ten_nv" name="ten_nv" value="<?php echo $row['Ten_NV']; ?>"><br>
        <label for="phai">Phai:</label><br>
        <input type="text" id="phai" name="phai" value="<?php echo $row['Phai']; ?>"><br>
        <label for="noi_sinh">Noi_Sinh:</label><br>
        <input type="text" id="noi_sinh" name="noi_sinh" value="<?php echo $row['Noi_Sinh']; ?>"><br>
        <label for="ma_phong">Ma_Phong:</label><br>
        <input type="text" id="ma_phong" name="ma_phong" value="<?php echo $row['Ma_Phong']; ?>"><br>
        <label for="luong">Luong:</label><br>
        <input type="text" id="luong" name="luong" value="<?php echo $row['Luong']; ?>"><br><br>
        <button type="submit">Lưu</button>
    </form>
</body>
</html>
