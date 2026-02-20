<?php
include 'database.php';

$db = new Database();

$category = $db->read("SELECT * FROM category;");

$result = $db->read("SELECT * FROM product WHERE ProductID = :id;", ["id" => $_GET['id']]);

$row = $result[0];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $title = 'สินค้า';
  include 'include/header.php';
  ?>

  <style>
    #preview {
      max-width: 100%;
      max-height: 300px;
    }
  </style>
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
                <i class="fas fa-edit mr-2"></i>แก้ไขสินค้า
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
              <form method="post" action="api_product.php" enctype="multipart/form-data">

                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" name="image_old" value="<?php echo $row->ImageURL ?>">
                <input type="hidden" name="model_old" value="<?php echo $row->ModelURL ?>">

                <!-- Card for Product Information -->
                <div class="card card-success">
                  <div class="card-header">
                    <h3 class="card-title">ข้อมูลสินค้า</h3>
                  </div>

                  <div class="card-body">
                    <!-- Category -->
                    <div class="form-group">
                      <label for="category">ประเภท</label>
                      <select id="category" class="form-control" name="category" required>
                        <option value="">เลือกประเภท</option>
                        <?php
                        foreach ($category as $value) {
                        ?>
                          <option value="<?php echo $value->CategoryID ?>" <?php echo ($row->CategoryID == $value->CategoryID) ? 'selected' : '' ?>><?php echo $value->CategoryName ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group">
                      <label for="ProductName">ชื่อสินค้า</label>
                      <input id="ProductName" class="form-control" type="text" name="ProductName" value="<?php echo $row->ProductName ?>" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                      <label for="Description">รายละเอียดสินค้า</label>
                      <textarea id="Description" class="form-control" name="Description" rows="3"><?php echo $row->Description ?></textarea>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                      <label for="Price">ราคา</label>
                      <input id="Price" class="form-control" type="number" name="Price" value="<?php echo $row->Price ?>" step="0.01" required>
                    </div>

                    <!-- Quantity/Stock -->
                    <div class="form-group">
                      <label for="Quantity">จำนวนสินค้า (สต็อก)</label>
                      <input id="Quantity" class="form-control" type="number" name="Quantity" value="<?php echo isset($row->Quantity) ? $row->Quantity : 0 ?>" min="0" required>
                      <small class="form-text text-muted">ระบุจำนวนสต็อกสินค้า</small>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                      <label for="ImageURL">รูปสินค้า</label>
                      <input id="ImageURL" class="form-control-file" type="file" name="ImageURL" accept="image/*">
                      <small class="form-text text-muted">อัปโหลดรูปภาพใหม่ (ถ้าต้องการเปลี่ยน)</small>
                    </div>

                    <!-- Image Preview -->
                    <div class="form-group">
                      <label>ตัวอย่างรูปภาพ</label>
                      <div class="row">
                        <div class="col-sm-6">
                          <img id="preview" src="uploaded_images/<?php echo $row->ImageURL ?>" alt="Image Preview" class="img-thumbnail">
                        </div>
                      </div>
                    </div>

                    <!-- 3D Model Upload -->
                    <div class="form-group">
                      <label for="ModelURL">ไฟล์โมเดล 3D (.glb)</label>
                      <input id="ModelURL" class="form-control-file" type="file" name="ModelURL" accept=".glb">
                      <small class="form-text text-muted">อัปโหลดไฟล์โมเดล 3D ใหม่ (ถ้าต้องการเปลี่ยน)</small>
                      
                      <?php if (!empty($row->ModelURL)) { ?>
                      <div class="mt-2">
                        <div class="alert alert-info">
                          <i class="fas fa-cube mr-2"></i>
                          <strong>ไฟล์ปัจจุบัน:</strong> <?php echo $row->ModelURL ?>
                        </div>
                      </div>
                      <?php } else { ?>
                      <div class="mt-2">
                        <small class="text-muted">ยังไม่มีไฟล์โมเดล 3D</small>
                      </div>
                      <?php } ?>
                      
                      <small id="modelFileName" class="form-text text-success" style="display:none;">
                        <i class="fas fa-check-circle mr-1"></i>ไฟล์ที่เลือก: <span></span>
                      </small>
                    </div>
                  </div>

                  <!-- Card Footer -->
                  <div class="card-footer">
                    <button class="btn btn-success" type="submit">
                      <i class="fas fa-save mr-2"></i>บันทึก
                    </button>
                    <a href="manage_product.php" class="btn btn-dark">
                      <i class="fas fa-arrow-left mr-2"></i>กลับ
                    </a>
                  </div>
                </div>

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
    $(function() {
      // Preview รูปภาพ
      $('#ImageURL').on('change', function() {
        var file = this.files[0];

        if (file) {
          var reader = new FileReader();

          reader.onload = function(e) {
            $('#preview').attr('src', e.target.result).show();
          };

          reader.readAsDataURL(file);
        }
      });

      // แสดงชื่อไฟล์ .glb ที่เลือก
      $('#ModelURL').on('change', function() {
        var file = this.files[0];
        if (file) {
          $('#modelFileName span').text(file.name);
          $('#modelFileName').show();
        } else {
          $('#modelFileName').hide();
        }
      });
    });
  </script>
</body>

</html>