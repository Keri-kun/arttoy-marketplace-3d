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
      /* display: none; */
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
                <i class="fas fa-plus-circle mr-2"></i>สินค้า
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
              <form method="post" action="api_product.php" enctype="multipart/form-data">

                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" name="image_old" value="<?php echo $row->ImageURL ?>">

                <!-- $result  -->
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
                <div class="form-group">
                  <label for="ProductName">สินค้า</label>
                  <input id="ProductName" class="form-control" type="text" name="ProductName" value="<?php echo $row->ProductName ?>" required>
                </div>
                <div class="form-group">
                  <label for="Description">รายละเอียดสินค้า</label>
                  <textarea id="Description" class="form-control" name="Description" rows="3"><?php echo $row->Description ?></textarea>
                </div>
                <div class="form-group">
                  <label for="Price">ราคา</label>
                  <input id="Price" class="form-control" type="number" name="Price" value="<?php echo $row->Price ?>" required>
                </div>
                <div class="form-group">
                  <label for="ImageURL">รูปสินค้า</label>
                  <input id="ImageURL" class="form-control-file" type="file" name="ImageURL" accept="image/*">
                </div>

                <div class="row">
                  <div class=" col-sm-6 col-12">
                    <img id="preview" src="uploaded_images/<?php echo $row->ImageURL ?>" alt="Image Preview">
                  </div>
                </div>

                <button class="btn btn-success" type="submit">บันทึก</button>
                <a href="manage_product.php" class="btn btn-dark">กลับ</a>
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
      $('#ImageURL').on('change', function() {
        // รับไฟล์จาก input
        var file = this.files[0];

        // ตรวจสอบว่ามีไฟล์ไหม
        if (file) {
          // สร้าง URL ของไฟล์
          var reader = new FileReader();

          reader.onload = function(e) {
            // ตั้งค่าที่อยู่ของภาพใน src ของ <img>
            $('#preview').attr('src', e.target.result).show();
          };

          // อ่านไฟล์
          reader.readAsDataURL(file);
        } else {
          $('#preview').hide();
        }
      });
    });
  </script>
</body>

</html>