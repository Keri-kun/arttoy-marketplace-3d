<?php
include 'database.php';

$db = new Database();

$result = $db->read("SELECT * FROM category;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $title = 'ประเภทสินค้า';
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
              <a href="manage_category_add.php" class="btn btn-success">
                <i class="fas fa-plus-circle mr-2"></i>ประเภทสินค้า</a>

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
                  <td>
                    <a href="manage_category_edit.php?id=<?php echo $value->CategoryID; ?>" class="text-success">
                      <i class="fas fa-edit"></i>
                    </a>
                  </td>
                  <td>
                    <a href="api_category.php?id=<?php echo $value->CategoryID; ?>&action=delete" class="text-danger">
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
    new DataTable('#table');
  </script>
</body>

</html>