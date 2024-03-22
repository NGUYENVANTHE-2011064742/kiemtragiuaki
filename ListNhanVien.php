<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        table td img {
            max-width: 50px;
            max-height: 50px;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
        }
        .action-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .action-buttons button:hover {
            background-color: #ddd;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
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

        // Kiểm tra xem người dùng đã đăng nhập chưa
        if(!isset($_SESSION['username'])){
            // Nếu chưa, chuyển hướng đến trang đăng nhập
            header("location: login.php");
            exit;
        }

        // Lấy tên người dùng từ session
        $username = $_SESSION['username'];

        $sql_role = "SELECT role FROM user WHERE username = '$username'";
        $result_role = mysqli_query($conn, $sql_role);
        $row_role = mysqli_fetch_assoc($result_role);
        $role = $row_role['role'];

        // Xác định trang hiện tại
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Số nhân viên muốn hiển thị trên mỗi trang
        $employees_per_page = 5;

        // Tính offset (vị trí bắt đầu của bản ghi trên trang hiện tại)
        $offset = ($current_page - 1) * $employees_per_page;

        // Query để lấy dữ liệu nhân viên trang hiện tại
        $sql = "SELECT n.Ma_NV, n.Ten_NV, n.Phai, n.Noi_Sinh, p.Ten_Phong as Ma_Phong, n.Luong 
                FROM NHANVIEN n
                INNER JOIN PHONGBAN p ON n.Ma_Phong = p.Ma_Phong
                LIMIT $offset, $employees_per_page";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h2>BẠN ĐANG ĐĂNG NHẬP TÀI KHOẢN $username!</h2>";
            echo "<table>";
            echo "<caption><h2>DANH SÁCH NHÂN VIÊN</h2></caption>";
            echo "<tr><th>Ma_NV</th><th>Ten_NV</th><th>Phai</th><th>Noi_Sinh</th><th>Ma_Phong</th><th>Luong</th><th>Action</th></tr>";
            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row["Ma_NV"]."</td>";
                echo "<td>".$row["Ten_NV"]."</td>";
                echo "<td><img src='image/".($row["Phai"] == 'NAM' ? 'man.jpg' : 'woman.jpg')."' style='max-width: 100px; max-height: 100px;'></td>";
                echo "<td>".$row["Noi_Sinh"]."</td>";
                echo "<td>".$row["Ma_Phong"]."</td>";
                echo "<td>".$row["Luong"]."</td>";
                echo "<td class='action-buttons'>";
                // Kiểm tra role của người dùng để quyết định hiển thị các nút
                if ($role == 'admin') {
                    echo "<button onclick='addEmployee(\"".$row["Ma_NV"]."\")'>Add</button>";
                    echo "<button onclick='editEmployee(\"".$row["Ma_NV"]."\")'>Edit</button>";
                    echo "<button onclick='deleteEmployee(\"".$row["Ma_NV"]."\")'>Delete</button>";
                } else {
                    // Nếu không phải admin, vô hiệu hóa các nút
                    echo "<button onclick='addEmployee(\"".$row["Ma_NV"]."\")' disabled>Add</button>";
                    echo "<button onclick='editEmployee(\"".$row["Ma_NV"]."\")' disabled>Edit</button>";
                    echo "<button onclick='deleteEmployee(\"".$row["Ma_NV"]."\")' disabled>Delete</button>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Tạo liên kết phân trang
            $sql_total = "SELECT COUNT(*) as total FROM NHANVIEN";
            $result_total = mysqli_query($conn, $sql_total);
            $row_total = mysqli_fetch_assoc($result_total);
            $total_records = $row_total['total'];
            $total_pages = ceil($total_records / $employees_per_page);
            
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i'>$i</a> ";
            }
            echo "</div>";
        } else {
            echo "0 results";
        }

        mysqli_close($conn);
        ?>
    </div>

    <form id="deleteForm" action="delete.php" method="post">
        <input type="hidden" id="deleteEmployeeId" name="ma_nv">
    </form>

    <script>
        function addEmployee(employeeId) {
            window.location.href = "Add.php";
        }

        function editEmployee(ma_nv) {
            window.location.href = "edit.php?ma_nv=" + ma_nv;
        }

        function deleteEmployee(ma_nv) {
            // Đặt mã nhân viên vào input ẩn
            document.getElementById("deleteEmployeeId").value = ma_nv;
            // Submit form
            document.getElementById("deleteForm").submit();
        }
    </script>
</body>
</html>

