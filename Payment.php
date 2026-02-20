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
            <form id="payment-form" action="index.php" method="post">
                <h2 class="text-center form-title">หน้าชำระเงิน</h2>

                <!-- Payment Information Section -->
                <div class="form-section-title">ข้อมูลการชำระเงิน</div>

                <!-- Credit Card Number -->
                <div class="mb-3 row">
                    <label class="col-3 col-form-label">หมายเลขบัตรเครดิต:</label>
                    <div class="col">
                        <input class="form-control" type="text" name="card_number" placeholder="หมายเลขบัตรเครดิต" required>
                    </div>
                </div>

                <!-- Expiration Date -->
                <div class="mb-3 row">
                    <label class="col-3 col-form-label">วันหมดอายุ:</label>
                    <div class="col">
                        <input class="form-control" type="text" name="expiry_date" placeholder="MM/YY" required>
                    </div>
                </div>

                <!-- CVV -->
                <div class="mb-3 row">
                    <label class="col-3 col-form-label">CVV:</label>
                    <div class="col">
                        <input class="form-control" type="text" name="cvv" placeholder="CVV" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mb-3 row">
                    <div class="col text-center">
                        <button class="btn btn-danger me-2" type="submit">ยืนยันการชำระเงิน</button>
                        <button class="btn btn-secondary" type="reset" onclick="location.href='Paymentmethod.php'">ยกเลิก</button>
                    </div>
                </div>
            </form>

            <!-- Thank You Message -->
            <div id="thank-you-message" class="thank-you-message">ขอบคุณที่ใช้บริการ</div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('thank-you-message').style.display = 'block';
            setTimeout(function() {
                document.getElementById('payment-form').submit();
            }, 2000); // หน่วงเวลา 2 วินาทีก่อนที่จะทำการ submit ฟอร์ม
        });
    </script>
</body>
</html>
