<?php
include './database.php';

$db = new Database();

$result = $db->read("SELECT p.*, c.CategoryName, b.CartID, b.quantity FROM product AS p 
                     INNER JOIN category AS c ON p.CategoryID = c.CategoryID 
                     INNER JOIN busket AS b ON b.ProductID = p.ProductID;"); // เปลี่ยนเป็น CartID
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ตะกร้า</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.bootstrap5.css">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="./" class="navbar-brand">
                    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">foxkie poppop</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="./" class="nav-link">หน้าแรก</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">หมวดหมู่</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="team.html" class="dropdown-item">team </a></li>
                                <li><a href="#" class="dropdown-item">Some other action</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="busket.php">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <span id="num_busket" class="badge badge-danger navbar-badge"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="nav-icon fas fa-sign-in-alt"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"> ข้อมูลตะกร้าสินค้า</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
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
                        $total_price = 0; // ประกาศตัวแปรเก็บราคารวมทั้งหมด

                        foreach ($result as $key => $row) {
                            // คำนวณราคารวมของสินค้าแต่ละรายการ
                            $item_total = $row->quantity * $row->Price;
                            $total_price += $item_total; // เพิ่มราคารวมของสินค้าลงในราคารวมทั้งหมด
                    ?>
                            <tr>
                                <td><?= $row->ProductName ?></td>
                                <td><?= $row->CategoryName ?></td>
                                <td><?= $row->quantity ?></td>
                                <td><?= number_format($row->Price, 2) ?> บาท</td>
                                <td><?= number_format($item_total, 2) ?> บาท</td> <!-- แสดงราคารวมต่อสินค้า -->
                                <td>
                                    <button class="btn btn-danger" onclick="deleteProduct(<?= $row->CartID ?>)">ลบ</button> <!-- ปุ่มลบ -->
                                </td>
                            </tr>
                    <?php
                        }
                    ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align:right;">ราคารวมทั้งหมด:</td>
                                <td><?= number_format($total_price, 2) ?> บาท</td> <!-- แสดงราคารวมทั้งหมด -->
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button class="btn btn-primary" onclick="add_order()">สั่งซื้อสินค้า</button>
                </div>
            </div>
        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark"></aside>

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
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
                    data: {
                        action: 'add_order'
                    },
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
                    url: "./sql_busket.php",  // ไฟล์ที่ใช้ลบสินค้าในฐานข้อมูล
                    data: {
                        action: 'delete_product',
                        cart_id: cartId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert("ลบสินค้าเรียบร้อยแล้ว");
                            location.reload(); // โหลดหน้าเว็บใหม่หลังจากลบสำเร็จ
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
                data: {
                    action: 'count_busket',
                },
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
