<?php
include 'database.php';

$db = new Database();

$result = $db->read("SELECT * FROM category WHERE CategoryID = :id;", ['id' => $_GET['id']]);
$row = $result[0];

// Get product count for this category
$countResult = $db->read("SELECT COUNT(*) as count FROM product WHERE CategoryID = :id", ['id' => $_GET['id']]);
$productCount = $countResult[0]->count;

// Get products in this category
$products = $db->read("SELECT ProductID, ProductName FROM product WHERE CategoryID = :id ORDER BY ProductName", ['id' => $_GET['id']]);
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
              <p class="mb-0">
                <i class="fas fa-edit mr-2"></i>แก้ไขประเภทสินค้า
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
            <div class="col-lg-6 col-md-8 col-12">

              <!-- Category Edit Form -->
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">ข้อมูลประเภทสินค้า</h3>
                </div>

                <form method="post" action="api_category.php">
                  <div class="card-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

                    <div class="form-group">
                      <label for="category">ประเภท</label>
                      <input id="category" class="form-control" type="text" name="category" value="<?php echo $row->CategoryName ?>" required>
                    </div>

                    <div class="form-group">
                      <label>จำนวนสินค้าทั้งหมด</label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?php echo $productCount; ?>" disabled>
                        <span class="input-group-append">
                          <span class="input-group-text">รายการ</span>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="card-footer">
                    <button class="btn btn-success" type="submit">บันทึก</button>
                    <a href="manage_category.php" class="btn btn-dark">กลับ</a>
                  </div>
                </form>
              </div>

              <!-- Product List -->
              <?php if ($productCount > 0) { ?>
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">รายการสินค้า (<?php echo $productCount; ?> รายการ)</h3>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10%;">No.</th>
                          <th>ชื่อสินค้า</th>
                          <th style="width: 15%;">การกระทำ</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($products as $key => $product) {
                        ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo $product->ProductName; ?></td>
                          <td>
                            <a href="manage_product_edit.php?id=<?php echo $product->ProductID; ?>" class="btn btn-sm btn-info" title="แก้ไข">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="manage_product.php?id=<?php echo $product->ProductID; ?>" class="btn btn-sm btn-warning" title="ดู">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fas fa-info-circle mr-2"></i>ไม่มีสินค้าในประเภทนี้
              </div>
              <?php } ?>

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