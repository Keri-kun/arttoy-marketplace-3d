<?php
include 'database.php';

$db = new Database();

$result = $db->read("SELECT * FROM data_user ORDER BY id;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    $title = 'แสดงข้อมูลการจัดส่ง';
    include 'include/header.php';
    ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include 'include/navbar.php';
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h2 class="text-center">แสดงข้อมูลของผู้ที่ต้องทำการจัดส่งสินค้า</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <table id="table" class="table table-hover table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">ชื่อ</th>
                                <th class="text-center">เบอร์โทร</th>
                                <th class="text-center">ที่อยู่</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $row) {
                            ?>
                                <tr>
                                    <td class="align-top text-center"><?php echo htmlspecialchars($row->username); ?></td>
                                    <td class="align-top text-center"><?php echo htmlspecialchars($row->phone); ?></td>
                                    <td class="align-top text-center"><?php echo htmlspecialchars($row->address); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
            </section>
        </div>
        <!-- Footer -->
        <?php
        include 'include/footer.php';
        ?>

    </div>
    <!-- ./wrapper -->

    <!-- Script -->
    <?php
    include 'include/script.php';
    ?>
    <script>
        new DataTable('#table');
    </script>
</body>

</html>
