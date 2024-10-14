<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
</head>
<body>
<?php
include "database.php"; // รวมไฟล์ database.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database(); // สร้าง instance ของ Database
    $conn = $database->getConnection(); // เชื่อมต่อฐานข้อมูล

    $username = $_POST["username"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $address = $_POST["address"] ?? '';

    if (empty($username) || empty($phone) || empty($address)) {
        die("กรุณากรอกข้อมูลให้ครบถ้วน");
    }

    $data = [
        'username' => $username,
        'phone' => $phone,
        'address' => $address
    ];

    $result = $database->create('data_user', $data); // เรียกใช้งานฟังก์ชัน create

    if ($result) {
        echo "ใส่ข้อมูลลง Database เรียบร้อย";
        header("Location: Paymentmethod.php");
        exit();
    } else {
        die("เกิดข้อผิดพลาดในการเพิ่มข้อมูล: " . $result);
    }
} else {
    echo "Invalid request method";
}
?>
</body>
</html>
