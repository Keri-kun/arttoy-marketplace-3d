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
              <p class="mb-0">
                <i class="fas fa-plus-circle mr-2"></i>ประเภทสินค้า
              </p>

            </div><!-- /.col -->

          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
              <form method="post" action="api_category.php">

                <input type="hidden" name="action" value="add">

                <div class="form-group">
                  <label for="category">ประเภท</label>
                  <input id="category" class="form-control" type="text" name="category" required>
                </div>

                <button class="btn btn-success" type="submit">บันทึก</button>
                <a href="manage_category.php" class="btn btn-dark">กลับ</a>
              </form>
            </div>
          </div>


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

  </script>
</body>

</html>