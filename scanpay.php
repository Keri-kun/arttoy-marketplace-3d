<?php
session_start();
include 'database.php';
$db = new Database();
$conn = $db->getConnection();

// ตรวจสอบ login
if (!isset($_SESSION['user_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนใช้งาน");
}
$user_id = intval($_SESSION['user_id']);

// ดึง OrderID ล่าสุดที่รอชำระของ User นี้
$sqlGetOrder = "SELECT OrderID, TotalAmount FROM orders 
                WHERE UserID = :uid AND Status IN ('รอชำระ', 'pending') 
                ORDER BY OrderDate DESC LIMIT 1";
$stmtGetOrder = $conn->prepare($sqlGetOrder);
$stmtGetOrder->execute([':uid' => $user_id]);
$latestOrder = $stmtGetOrder->fetch(PDO::FETCH_ASSOC);

if (!$latestOrder) {
    echo "<script>alert('⚠️ ไม่พบคำสั่งซื้อที่รอชำระเงิน'); window.location='index.php';</script>";
    exit;
}

$order_id = $latestOrder['OrderID'];
$total_amount = $latestOrder['TotalAmount'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . "/uploads_slips/";
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                echo "<script>alert('❌ ไม่สามารถสร้างโฟลเดอร์ uploads_slips/');</script>";
                exit;
            }
        }

        // ตรวจสอบประเภทไฟล์
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES["payment_image"]["name"], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            echo "<script>alert('❌ อนุญาตเฉพาะไฟล์รูปภาพเท่านั้น (jpg, png, gif)');</script>";
            exit;
        }

        // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
        if ($_FILES["payment_image"]["size"] > 5 * 1024 * 1024) {
            echo "<script>alert('❌ ไฟล์ใหญ่เกินไป (เกิน 5MB)');</script>";
            exit;
        }

        // สร้างชื่อไฟล์ใหม่ (เพิ่ม OrderID เพื่อง่ายต่อการติดตาม)
        $fileName = "slip_" . $order_id . "_" . time() . "." . $ext;
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["payment_image"]["tmp_name"], $targetFilePath)) {
            // อัปเดต DB โดยระบุ OrderID ชัดเจน
            $sql = "UPDATE orders 
                    SET slip_image = :slip, Status = 'รออนุมัติ' 
                    WHERE OrderID = :order_id AND UserID = :uid";
            
            try {
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute([
                    ':slip' => $fileName,
                    ':order_id' => $order_id,
                    ':uid' => $user_id
                ]);

                // ตรวจสอบว่ามีการอัพเดทจริง
                if ($stmt->rowCount() > 0) {
                    echo "<script>
                        alert('✅ อัปโหลดสลิปสำเร็จ!\\nOrder ID: {$order_id}\\nรอการตรวจสอบจากแอดมิน');
                        window.location='index.php';
                    </script>";
                    exit;
                } else {
                    // ลบไฟล์ที่อัพโหลดไว้ เพราะไม่ได้บันทึกในฐานข้อมูล
                    unlink($targetFilePath);
                    echo "<script>alert('⚠️ ไม่สามารถอัพเดทข้อมูลในฐานข้อมูล\\nกรุณาลองใหม่อีกครั้ง');</script>";
                }
            } catch (PDOException $e) {
                // ลบไฟล์ที่อัพโหลดไว้
                if (file_exists($targetFilePath)) {
                    unlink($targetFilePath);
                }
                echo "<script>alert('❌ Database Error: " . addslashes($e->getMessage()) . "');</script>";
            }
        } else {
            $error = error_get_last();
            echo "<script>alert('❌ ไม่สามารถอัปโหลดไฟล์ได้\\nกรุณาตรวจสอบ permission ของโฟลเดอร์');</script>";
        }
    } else {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'ไฟล์ใหญ่เกินที่กำหนดใน php.ini',
            UPLOAD_ERR_FORM_SIZE => 'ไฟล์ใหญ่เกินที่กำหนดในฟอร์ม',
            UPLOAD_ERR_PARTIAL => 'อัปโหลดไฟล์ไม่สมบูรณ์',
            UPLOAD_ERR_NO_FILE => 'ไม่มีการอัปโหลดไฟล์',
            UPLOAD_ERR_NO_TMP_DIR => 'ไม่พบโฟลเดอร์ temp',
            UPLOAD_ERR_CANT_WRITE => 'ไม่สามารถเขียนไฟล์ลงดิสก์',
            UPLOAD_ERR_EXTENSION => 'การอัปโหลดถูกหยุดโดย extension'
        ];
        
        $errorCode = $_FILES['payment_image']['error'] ?? UPLOAD_ERR_NO_FILE;
        $errorMsg = $uploadErrors[$errorCode] ?? 'เกิดข้อผิดพลาดไม่ทราบสาเหตุ';
        
        echo "<script>alert('⚠️ กรุณาเลือกไฟล์สลิปการโอน\\nError: {$errorMsg}');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าชำระเงิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .container { max-width: 650px; }
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .form-title { 
            font-size: 1.8rem; 
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }
        .form-section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 12px 20px;
            margin: 0 -30px 20px;
            border-radius: 10px;
            font-weight: 600;
        }
        .order-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }
        .order-info h5 {
            font-weight: 700;
            margin-bottom: 15px;
        }
        .order-info p {
            margin: 8px 0;
            font-size: 1.05rem;
        }
        .qr-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
        }
        .img-preview { 
            margin-top: 15px; 
            max-height: 250px; 
            object-fit: cover; 
            border: 3px solid #667eea; 
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-upload {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .file-input {
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 15px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="payment-card p-4">
        <!-- ข้อมูล Order -->
        <div class="order-info">
            <h5><i class="bi bi-receipt"></i> ข้อมูลคำสั่งซื้อ</h5>
            <p class="mb-1"><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
            <p class="mb-1"><strong>ยอดที่ต้องชำระ:</strong> <?php echo number_format($total_amount, 2); ?> บาท</p>
            <p class="mb-0"><small><i class="bi bi-info-circle"></i> กรุณาอัปโหลดสลิปการโอนเงินสำหรับคำสั่งซื้อนี้</small></p>
        </div>

        <form id="payment-form" method="post" enctype="multipart/form-data">
            <h2 class="text-center form-title"><i class="bi bi-credit-card"></i> หน้าชำระเงิน</h2>

            <!-- QR Code -->
            <div class="form-section-title">
                <i class="bi bi-qr-code"></i> QR Code เพื่อจ่ายเงิน
            </div>
            <div class="qr-container text-center">
                <img src="pay.jpg" alt="QR Code" class="img-fluid" style="max-width:350px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
            </div>

            <!-- Upload Section -->
            <div class="form-section-title">
                <i class="bi bi-cloud-upload"></i> อัปโหลดสลิปการโอน
            </div>
            <div class="mb-3">
                <input class="form-control file-input" type="file" name="payment_image" accept="image/*" required onchange="previewImage(event)">
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-info-circle"></i> รองรับไฟล์: JPG, PNG, GIF, WEBP (ไม่เกิน 5MB)
                </small>
            </div>

            <div class="text-center">
                <img id="imagePreview" class="img-preview" src="#" alt="Preview" style="display:none;">
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-danger btn-upload me-2" type="submit">
                    <i class="bi bi-check-circle"></i> ยืนยันการชำระเงิน
                </button>
                <a href="index.php" class="btn btn-secondary btn-lg" style="border-radius: 10px;">
                    <i class="bi bi-x-circle"></i> ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        // ตรวจสอบประเภทไฟล์
        if (!file.type.match('image.*')) {
            alert('❌ กรุณาเลือกไฟล์รูปภาพเท่านั้น');
            event.target.value = '';
            imagePreview.style.display = 'none';
            return;
        }
        
        // ตรวจสอบขนาดไฟล์ (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('❌ ไฟล์ใหญ่เกินไป (เกิน 5MB)');
            event.target.value = '';
            imagePreview.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = function() {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
    }
}

// ป้องกันการ submit ฟอร์มซ้ำ
document.getElementById('payment-form').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังอัปโหลด...';
});
</script>
</body>
</html>