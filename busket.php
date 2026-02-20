<?php
session_start();
include './database.php';

// ถ้ายังไม่ได้ล็อกอิน ให้เด้งไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 
$db = new Database();

// ดึงเฉพาะตะกร้าของ user คนนี้
$result = $db->read("SELECT p.*, c.CategoryName, b.CartID, b.quantity 
                     FROM product AS p 
                     INNER JOIN category AS c ON p.CategoryID = c.CategoryID 
                     INNER JOIN busket AS b ON b.ProductID = p.ProductID 
                     WHERE b.UserID = ?", [$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ตะกร้า</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.bootstrap5.css">
    
    <style>
        :root {
            --primary-color: #FF6B9D;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #2C3E50;
            --light-bg: #F8F9FA;
            --card-shadow: 0 8px 25px rgba(0,0,0,0.1);
            --hover-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            background: transparent;
            flex: 1;
        }

        /* Navbar Styling */
        .main-header.navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--primary-color);
        }

        .navbar-brand {
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .brand-text {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .badge-danger {
            background: linear-gradient(45deg, var(--primary-color), #FF8FB3) !important;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Content Header */
        .content-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
            padding: 30px;
        }

        .content-header h1 {
            font-family: 'Fredoka', cursive;
            color: white;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 0;
        }

        /* Main Content */
        .content {
            padding: 20px;
        }

        .content .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            text-align: center;
            font-family: 'Fredoka', cursive;
        }

        .table tbody td {
            padding: 15px;
            border: 1px solid #f0f0f0;
            text-align: center;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .table tfoot td {
            background: rgba(255, 107, 157, 0.1);
            font-weight: 600;
            padding: 15px;
            border: 2px solid var(--primary-color);
            color: var(--dark-color);
        }

        .table tfoot td:last-child {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        /* Button Styling */
        .btn-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 15px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-danger:hover {
            background: linear-gradient(45deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--secondary-color), #6FE7DD);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 30px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            margin-top: 20px;
            cursor: pointer;
            font-family: 'Fredoka', cursive;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #6FE7DD, var(--secondary-color));
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 205, 196, 0.4);
            color: white;
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        /* Footer */
        .main-footer {
            background: rgba(44, 62, 80, 0.95);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            border-top: 3px solid var(--primary-color);
        }

        .main-footer strong {
            color: var(--accent-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-header h1 {
                font-size: 1.8rem;
            }

            .content .container {
                padding: 15px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px 5px;
                font-size: 0.9rem;
            }

            .btn-danger {
                padding: 6px 10px;
                font-size: 0.85rem;
            }

            .btn-primary {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light">
            <div class="container">
                <a href="./" class="navbar-brand">
                    <i class="fas fa-robot" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 10px;"></i>
                    <span class="brand-text font-weight-bold">ArtToy Paradise</span>
                </a>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="busket.php">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <span id="num_busket" class="badge badge-danger navbar-badge"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <h1><i class="fas fa-shopping-cart mr-3"></i>ข้อมูลตะกร้าสินค้า</h1>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <table id="table" class="table">
                        <thead>
                            <tr>
                                <th>ชื่อสินค้า</th>
                                <th>ประเภทสินค้า</th>
                                <th>จำนวนสินค้า</th>
                                <th>ราคา (ต่อหน่วย)</th>
                                <th>ราคารวม</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        $total_price = 0;
                        foreach ($result as $row) {
                            $item_total = $row->quantity * $row->Price;
                            $total_price += $item_total;
                    ?>
                            <tr>
                                <td><?= $row->ProductName ?></td>
                                <td><?= $row->CategoryName ?></td>
                                <td><?= $row->quantity ?></td>
                                <td><?= number_format($row->Price, 2) ?> บาท</td>
                                <td><?= number_format($item_total, 2) ?> บาท</td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteProduct(<?= $row->CartID ?>)">
                                        <i class="fas fa-trash mr-1"></i>ลบ
                                    </button>
                                </td>
                            </tr>
                    <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align:right;">ราคารวมทั้งหมด:</td>
                                <td><?= number_format($total_price, 2) ?> บาท</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button class="btn btn-primary" onclick="add_order()">
                        <i class="fas fa-credit-card mr-2"></i>สั่งซื้อสินค้า
                    </button>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2024 All rights reserved.</strong>
        </footer>
    </div>

    <!-- JS -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.bootstrap5.js"></script>

    <script>
        $(function() {
            new DataTable('#table');
        });

        function add_order() {
            let check = confirm("คุณต้องการสั่งซื้อสินค้าหรือไม่?");
            if (check) {
                $.ajax({
                    type: "POST",
                    url: "./sql_busket.php",
                    data: { action: 'add_order' },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "formRegis.php";
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function() {
                        alert("เกิดข้อผิดพลาดในการสั่งซื้อสินค้า");
                    }
                });
            }
        }

        function deleteProduct(cartId) {
            let check = confirm("คุณต้องการลบสินค้านี้ออกจากตะกร้าหรือไม่?");
            if (check) {
                $.ajax({
                    type: "POST",
                    url: "./sql_busket.php",
                    data: {
                        action: 'delete_product',
                        cart_id: cartId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert("ลบสินค้าเรียบร้อยแล้ว");
                            location.reload();
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function() {
                        alert("เกิดข้อผิดพลาดในการลบสินค้า");
                    }
                });
            }
        }

        function view_busket() {
            $.ajax({
                type: "GET",
                url: "sql_busket.php",
                data: { action: 'count_busket' },
                dataType: "json",
                success: function(response) {
                    if (response.num > 0) {
                        $("#num_busket").html(response.num);
                    }
                }
            });
        }

        view_busket();
    </script>
</body>
</html>