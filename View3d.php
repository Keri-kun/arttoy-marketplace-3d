<?php
include 'database.php';

$product_id = $_GET['product_id'] ?? 0;
$db = new Database();

// ดึงชื่อไฟล์โมเดลจากฐานข้อมูล
$product = $db->read("SELECT * FROM product WHERE ProductID = :id", ['id' => $product_id]);

if (!$product || empty($product[0]->ModelURL)) {
    die("ไม่พบโมเดล 3 มิติของสินค้านี้");
}

$model_file = 'uploaded_models/' . $product[0]->ModelURL;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ดูสินค้าแบบ 3 มิติ</title>
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <style>
      body { margin: 0; }
      a-scene { width: 100vw; height: 100vh; }
    </style>
  </head>
  <body>
    <a-scene>
      <a-assets>
        <a-asset-item id="model" src="<?= htmlspecialchars($model_file) ?>"></a-asset-item>
      </a-assets>

      <a-entity 
        gltf-model="#model" 
        position="0 0 -3" 
        rotation="0 45 0" 
        scale="3 3 3" 
        animation="property: rotation; to: 0 405 0; loop: true; dur: 10000">
      </a-entity>

      <a-sky color="#ECECEC"></a-sky>
      <a-camera position="0 1.6 0"></a-camera>
    </a-scene>
  </body>
</html>
