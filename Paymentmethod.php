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
            max-width: 100%; /* Ensure the QR code fits within its container */
            height: auto; /* Maintain aspect ratio */
        }
    </style>
    <title>หน้าชำระเงิน</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h2 class="text-center form-title">หน้าชำระเงิน</h2>

            <!-- Payment Method Section -->
            <div class="form-section-title">เลือกวิธีชำระเงิน</div>
            <div class="mb-3">
                <select class="form-select" id="paymentMethod" required>
                    <option value="" disabled selected>เลือกวิธีชำระเงิน</option>
                    <option value="card">ตัดบัตร</option>
                    <option value="transfer">โอนเข้าพร้อมเพ</option>
                </select>
            </div>
            <!-- Buttons -->
            <div class="mb-3 row">
                <div class="col text-center">
                    <button class="btn btn-danger me-2" id="confirmButton" type="button">ยืนยัน</button>
                    <button class="btn btn-secondary" type="reset">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('confirmButton').addEventListener('click', function() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            if (paymentMethod === 'card') {
                window.location.href = 'Payment.php';
            } else if (paymentMethod === 'transfer') {
                window.location.href = 'scanpay.php';
            } else {
                alert('กรุณาเลือกวิธีชำระเงิน');
            }
        });
    </script>
</body>
</html>
