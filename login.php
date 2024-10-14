<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylelog.css">
</head>
<body>
    <div class="login-form">
        <h1>Login</h1>
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2>Log In</h2>
                    <?php
session_start(); // เริ่มต้น session

if (isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shop";

    // สร้างการเชื่อมต่อกับฐานข้อมูล
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepared statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass); // ผูกค่าพารามิเตอร์ $user และ $pass
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบผลลัพธ์จากฐานข้อมูล
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // เก็บข้อมูลใน session
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $row['role'];

        // ตรวจสอบ role และเปลี่ยนหน้า
        if ($row['role'] == 'admin') {
            header("Location: dashboard.php");
            exit(); // หยุดการทำงานเพิ่มเติมหลังจากเปลี่ยนหน้า
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        echo "<p style='color:red;'>Invalid username or password!</p>";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
}
?>

                    <form action="login.php" method="post">
                        <input type="text" name="username" placeholder="User Name" required autofocus>
                        <input type="password" name="password" placeholder="User Password" required>
                        <button class="btn" type="submit" name="submit">Login</button>
                    </form>
                    <p class="account">Don't Have An Account? <a href="Register.html">Register</a></p>
                </div>
                <div class="form-img">
                    <img src="bg.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
