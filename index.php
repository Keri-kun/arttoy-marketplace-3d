<?php

include './database.php';

$db = new Database();

$result = $db->read("SELECT * FROM product AS p INNER JOIN category AS c ON p.CategoryID = c.CategoryID;");

$data = [];

foreach ($result as $key => $row) {
    $data[$row->CategoryName][] = $row;
}

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าแรก</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    
    <!-- เพิ่ม CSS เพื่อควบคุมขนาดของภาพ -->
    <style>
        .product-image {
            width: 100%; /* ขยายภาพตามขนาดความกว้างของการ์ด */
            height: 200px; /* กำหนดความสูงให้คงที่ */
            object-fit: cover; /* ตัดภาพเพื่อให้ขนาดพอดี */
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

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
                                <li><a href="team.html" class="dropdown-item">Team </a></li>
                                <li><a href="#" class="dropdown-item">Some other action</a></li>
                            </ul>
                        </li>
                    </ul>


                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="busket.php">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <span id="num_busket" class="badge badge-danger navbar-badge"></span>
                        </a>

                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="login.php">
                            <i class="nav-icon  fas fa-sign-in-alt"></i>
                        </a>

                    </li>


                </ul>
            </div>
        </nav>

        <div class="content-wrapper">

            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"> รายการสินค้า</h1>
                        </div>
                    </div>
                </div>
            </div>



            <div class="content">
                <div class="container">
                    <?php
                    foreach ($data as $key => $rows) {
                    ?>
                        <div class="row">
                            <div class="col-6">
                                <h5>
                                    <?= $key ?>
                                </h5>

                            </div>

                        </div>
                        <div class="row mb-2">
    <?php
    foreach ($rows as $k => $row) {
    ?>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card">
                <img class="card-img-top product-image" src="./uploaded_images/<?= $row->ImageURL ?>" alt="">
                <div class="card-body p-2">
                    <h5 class="card-title"><?= $row->ProductName ?></h5> <!-- ดึง Title จากฐานข้อมูล -->
                    <p class="card-text"><?= number_format($row->Price, 0) ?> บาท</p> <!-- ดึงราคาและแสดงผล -->
                </div>
                <div class="card-footer p-0">
                    <button class="btn btn-sm btn-outline-success w-100" type="button" onclick="add_busket('<?= $row->ProductID ?>')">ใส่ตะกร้า</button>
                </div>
            </div>
        </div>

    <?php
    }
    ?>
</div>

                            <?php
                            }
                            ?>

                        </div>

                    <?php
                    ?>


                </div>
            </div>

        </div>


        <aside class="control-sidebar control-sidebar-dark">

        </aside>

        <footer class="main-footer">

        <strong>Copyright &copy; ร้านค้ารุ่นbeta.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>


    <script>
        function add_busket(id) {
            console.log(id);
            $.ajax({
                type: "POST",
                url: "sql_busket.php",
                data: {
                    ProductID: id,
                    action: 'add_busket',
                },
                dataType: "json",
                success: function(response) {
                    $("#num_busket").html(response.num);
                    alert("เพิ่มสินค้าสำเร็จ")
                }
            });
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

        view_busket()
    </script>
</body>

</html>
