<!DOCTYPE html>
<html lang="th">

<head>
  <?php
  $title = 'แดชบอร์ดยอดขาย';
  include 'include/header.php';

  // ตรวจสอบสิทธิ์ (เฉพาะ admin)
  if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
      die('คุณไม่มีสิทธิ์เข้าถึง');
  }

  // เชื่อมต่อ database
  $conn = new mysqli('localhost', 'root', '', 'shop');
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // ตั้งค่า charset
  $conn->set_charset("utf8mb4");

  // ✅ ดึงยอดขายรวมทั้งหมด (รองรับสถานะภาษาไทย)
  $totalSalesQuery = "
      SELECT 
          COALESCE(SUM(op.amount * op.price), 0) AS total_revenue, 
          COALESCE(SUM(op.amount), 0) AS total_items,
          COUNT(DISTINCT o.OrderID) AS total_orders
      FROM orders o
      LEFT JOIN order_product op ON o.OrderID = op.OrderID
      WHERE o.Status IN ('ชำระแล้ว', 'รอชำระ', 'จัดส่งแล้ว')
  ";

  $totalResult = $conn->query($totalSalesQuery);
  if (!$totalResult) {
      die('Query Error: ' . $conn->error);
  }
  $totalData = $totalResult->fetch_assoc();

  // ✅ ดึงเฉพาะสินค้าขายดี 3 อันดับแรก
  $productSalesQuery = "
      SELECT 
          p.ProductID,
          p.ProductName,
          p.Price AS current_price,
          COUNT(op.rowno) AS times_sold,
          COALESCE(SUM(op.amount), 0) AS total_quantity_sold,
          COALESCE(SUM(op.amount * op.price), 0) AS total_revenue,
          CASE 
              WHEN SUM(op.amount) > 0 THEN ROUND(SUM(op.amount * op.price) / SUM(op.amount), 2)
              ELSE 0
          END AS avg_price
      FROM product p
      LEFT JOIN order_product op ON p.ProductID = op.ProductID
      LEFT JOIN orders o ON op.OrderID = o.OrderID
      GROUP BY p.ProductID
      ORDER BY total_quantity_sold DESC
      LIMIT 3
  ";

  $productResult = $conn->query($productSalesQuery);
  $products = [];
  while ($row = $productResult->fetch_assoc()) {
      $products[] = $row;
  }

  $conn->close();
  ?>
  <style>
    .rank {
      display: inline-block;
      background: #667eea;
      color: white;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      line-height: 30px;
      text-align: center;
      font-weight: bold;
      font-size: 0.9em;
    }
    .rank.top1 { background: #ffd700; color: #333; }
    .rank.top2 { background: #c0c0c0; color: #333; }
    .rank.top3 { background: #cd7f32; }
    .price { color: #10b981; font-weight: 600; }
    .no-data { text-align: center; padding: 40px; color: #999; }
    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php include 'include/navbar.php'; ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">📊 แดชบอร์ดยอดขาย</h1>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">
          
          <!-- Cards แสดงยอดรวม -->
          <div class="row">
            <div class="col-lg-4 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>฿<?php echo number_format($totalData['total_revenue'] ?? 0, 2); ?></h3>
                  <p>ยอดขายรวม</p>
                </div>
                <div class="icon">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo number_format($totalData['total_items'] ?? 0); ?></h3>
                  <p>จำนวนสินค้าที่ขาย</p>
                </div>
                <div class="icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo number_format($totalData['total_orders'] ?? 0); ?></h3>
                  <p>จำนวนคำสั่งซื้อ</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-invoice"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- ตาราง Top 3 สินค้าขายดี -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-trophy"></i> 3 อันดับสินค้าขายดี</h3>
                </div>
                <div class="card-body">
                  <table class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th style="width: 5%">อันดับ</th>
                        <th style="width: 30%">ชื่อสินค้า</th>
                        <th style="width: 15%">ขายได้ (ชิ้น)</th>
                        <th style="width: 15%">จำนวนครั้งที่ขาย</th>
                        <th style="width: 20%">ยอดขาย</th>
                        <th style="width: 15%">ราคาเฉลี่ย</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (empty($products)) {
                          echo '<tr><td colspan="6" class="no-data">ยังไม่มีข้อมูลการขาย</td></tr>';
                      } else {
                          $rank = 1;
                          foreach ($products as $product) {
                              $rankClass = '';
                              if ($rank == 1) $rankClass = 'top1';
                              elseif ($rank == 2) $rankClass = 'top2';
                              elseif ($rank == 3) $rankClass = 'top3';
                              
                              echo '<tr>';
                              echo '<td class="text-center"><span class="rank ' . $rankClass . '">' . $rank . '</span></td>';
                              echo '<td><strong>' . htmlspecialchars($product['ProductName']) . '</strong></td>';
                              echo '<td class="text-center">' . number_format($product['total_quantity_sold']) . ' ชิ้น</td>';
                              echo '<td class="text-center">' . number_format($product['times_sold']) . ' ครั้ง</td>';
                              echo '<td class="text-right"><span class="price">฿' . number_format($product['total_revenue'], 2) . '</span></td>';
                              echo '<td class="text-right">฿' . number_format($product['avg_price'], 2) . '</td>';
                              echo '</tr>';
                              $rank++;
                          }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </div>

    <?php include 'include/footer.php'; ?>
  </div>

  <?php include 'include/script.php'; ?>
</body>
</html>
