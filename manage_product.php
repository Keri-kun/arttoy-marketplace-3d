<?php
include 'database.php';

$db = new Database();

$result = $db->read("SELECT p.*, c.CategoryName FROM product AS p LEFT JOIN category AS c ON p.CategoryID = c.CategoryID;");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $title = 'สินค้า';
  include 'include/header.php';
  ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">


    <?php
    include 'include/navbar.php';
    ?>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <a href="manage_product_add.php" class="btn btn-success">
                <i class="fas fa-plus-circle mr-2"></i>สินค้า</a>

            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <table id="table" class="table table-light">
            <thead class="thead-light">
              <tr>
                <th style="width: 10%;">No.</th>
                <th>ประเภทสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>รายละเอียด</th>
                <th>ราคา</th>
                <th>รูปสินค้า</th>
                <th style="width: 5%;"></th>
                <th style="width: 5%;"></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result as $key => $value) {
              ?>
                <tr>
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo $value->CategoryName; ?></td>
                  <td><?php echo $value->ProductName; ?></td>
                  <td><?php echo $value->Description; ?></td>
                  <td><?php echo $value->Price; ?></td>
                  <td><img src="uploaded_images/<?php echo $value->ImageURL; ?>" alt="" style="max-width: 150px;"></td>
                  <td>
                    <a href="manage_product_edit.php?id=<?php echo $value->ProductID; ?>" class="text-success">
                      <i class="fas fa-edit"></i>
                    </a>
                  </td>
                  <td>
                    <a href="api_product.php?id=<?php echo $value->ProductID; ?>&action=delete" class="text-danger">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php
              }
              ?>


            </tbody>

          </table>

        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <?php
    include 'include/footer.php';
    ?>


  </div>
  <!-- ./wrapper -->
  <?php
  include 'include/script.php';
  ?>
  <script>
    new DataTable("#table")
  </script>
</body>

</html>