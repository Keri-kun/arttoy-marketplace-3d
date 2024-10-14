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
            background-color: #17a2b8;
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
    </style>
    <title>Application Form</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <form action="insert2db.php" method="post" enctype="multipart/form-data">
                <h2 class="text-center form-title">แบบฟอร์มชื่อที่อยู่จัดส่ง</h2>

                <!-- Personal Information Section -->
                <div class="form-section-title">ข้อมูลส่วนตัว</div>

                <!-- Name -->
                <div class="mb-3 row">
                    <label class="col-3 col-form-label">ชื่อ-นามสกุล:</label>
                    <div class="col">
                        <input class="form-control" type="text" name="username" placeholder="ชื่อ-นามสกุล" required>
                    </div>
                </div>

                <!-- Phone -->
                <div class="mb-3 row">
                    <label class="col-3 col-form-label">เบอร์โทรศัพท์:</label>
                    <div class="col">
                        <input class="form-control" type="text" name="phone" placeholder="เบอร์โทรศัพท์" required>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-3 row">
                    <label class="col-3 col-form-label">ที่อยู่:</label>
                    <div class="col">
                        <input class="form-control" type="text" name="address" placeholder="ที่อยู่" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mb-3 row">
                    <div class="col text-center">
                        <button class="btn btn-primary me-2" type="submit" name="button">ยืนยัน</button>
                        <button class="btn btn-secondary" type="reset" name="reset">ยกเลิก</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
