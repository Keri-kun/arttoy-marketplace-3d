<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
        }
        .form-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .form-section-title {
            background-color: #dc3545; /* Red color */
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .form-control {
            border-radius: 0.375rem;
        }
        button {
            min-width: 120px;
        }
        .img-preview {
            margin-top: 15px;
            max-height: 200px;
            object-fit: cover;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 80%; 
            height: auto;
        }
        .thank-you-message {
            display: none;
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <title>หน้าชำระเงิน</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <form id="payment-form" action="index.php" method="post" enctype="multipart/form-data">
                <h2 class="text-center form-title">หน้าชำระเงิน</h2>

                <!-- QR Code Section -->
                <div class="form-section-title">QR Code เพื่อจ่ายเงิน</div>
                <div class="qr-code">
                    <img src="pay.jpg" alt="QR Code" class="img-fluid">
                </div>

                <!-- Upload Section -->
                <div class="form-section-title">อัปโหลดรูปภาพเพื่อสแกนจ่าย</div>

                <!-- Image Upload -->
                <div class="mb-3 row">
                    <label class="col-12 col-form-label">กรุณาอัปโหลดรูปภาพการชำระเงิน:</label>
                    <div class="col">
                        <input class="form-control" type="file" id="paymentImage" name="payment_image" accept="image/*" required onchange="previewImage(event)">
                    </div>
                </div>

                <!-- Image Preview -->
                <div class="mb-3 row">
                    <div class="col text-center">
                        <img id="imagePreview" class="img-preview" src="#" alt="Image Preview" style="display: none;">
                    </div>
                </div>

                <!-- Error Message -->
                <div class="mb-3 row">
                    <div class="col text-center">
                        <span id="error-message" style="color: red; display: none;">กรุณาอัพสลิปจ่ายเงินด้วย</span>
                    </div>
                </div>

                <!-- Thank You Message -->
                <div id="thank-you-message" class="thank-you-message">ขอบคุณที่ใช้บริการ</div>

                <!-- Buttons -->
                <div class="mb-3 row">
                    <div class="col text-center">
                        <button class="btn btn-danger me-2" type="submit">ยืนยันการชำระเงิน</button>
                        <button class="btn btn-secondary" type="button" onclick="location.href='Paymentmethod.php'">ยกเลิก</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                imagePreview.src = reader.result;
                imagePreview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('payment-form').addEventListener('submit', function(event) {
            const paymentImage = document.getElementById('paymentImage').files.length;
            const errorMessage = document.getElementById('error-message');
            const thankYouMessage = document.getElementById('thank-you-message');

            if (paymentImage === 0) {
                event.preventDefault();
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
                thankYouMessage.style.display = 'block';

                // หน่วงเวลา 2 วินาทีก่อนที่จะทำการ submit ฟอร์ม
                setTimeout(function() {
                    document.getElementById('payment-form').submit();
                }, 2000);
            }
        });
    </script>
</body>
</html>
