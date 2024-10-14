<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $title = 'หน้าแรก';
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
              <h1 class="m-0">หน้าแรก</h1>
            </div>

          </div>
        </div>
      </div>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><span id="category_count"></span> <span style="font-size: 20px;">รายการ</span> </h3>

                  <p>ประเภทสินค้า</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="manage_category.php" class="small-box-footer">ข้อมูลเพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><span id="product_count"></span> <span style="font-size: 20px;">รายการ</span></h3>

                  <p>สินค้า</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="manage_product.php" class="small-box-footer">ข้อมูลเพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-6">
              <div id="piechart" style="width: 900px; height: 500px;"></div>
            </div>
          </div>

        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <?php
    include 'include/footer.php';
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  </div>
  <!-- ./wrapper -->

  <?php
  include 'include/script.php';
  ?>
  <script type="text/javascript">
    google.charts.load('current', {
      'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(load_data);

    function load_data() {
      $.ajax({
        type: "GET",
        url: "api_category.php",
        data: {
          action: 'count_category'
        },
        dataType: "json",
        success: function(res) {
          let arr = []
          arr.push(['รายการ', 'จำนวน'])
          res.forEach(e => {
            arr.push([e.CategoryName, e._count])
          });
          drawChart(arr)
        }
      });
    }

    function get_count() {
      $.ajax({
        type: "GET",
        url: "api_category.php",
        data: {
          action: 'count_all'
        },
        dataType: "json",
        success: function(res) {
          $("#category_count").text(res.category);
          $("#product_count").text(res.product);
        }
      });
    }
    get_count()

    function drawChart(arr) {
     
      console.log(arr);
      var data = google.visualization.arrayToDataTable(arr);

      var options = {
        title: 'จำนวนสินค้าแต่ละประเภท'
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));

      chart.draw(data, options);
    }
  </script>

</body>

</html>