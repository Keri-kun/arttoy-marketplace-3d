<?php
// เชื่อมต่อฐานข้อมูล
$host = 'localhost'; // ปกติจะเป็น localhost
$dbname = 'shop'; // ชื่อฐานข้อมูล
$username = 'root'; // ชื่อผู้ใช้ MySQL (ปกติจะเป็น root)
$password = ''; // รหัสผ่าน MySQL

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // ตรวจสอบรหัสผ่านที่ตรงกัน
    if ($pass === $confirm_pass) {
        // ไม่เข้ารหัสรหัสผ่าน (เก็บเป็น plaintext)

        // เตรียมคำสั่ง SQL
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // เตรียม statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user, $email, $pass); // เก็บรหัสผ่านแบบ plaintext

        // ดำเนินการบันทึกข้อมูล
        if ($stmt->execute()) {
            echo "Registration successful!";
            
            // รอ 2 วินาทีแล้วกลับไปหน้า login.php
            sleep(2);
            header("Location: login.php");
            exit(); // ให้แน่ใจว่าจะไม่ดำเนินการโค้ดหลังจากนี้
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // ปิด statement
        $stmt->close();
    } else {
        echo "Passwords do not match!";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
