<?php
session_start();        // เริ่ม session เพื่อให้สามารถเข้าถึง session ที่จะลบ
session_unset();        // เคลียร์ตัวแปรทั้งหมดใน session
session_destroy();      // ทำลาย session ปัจจุบันทั้งหมด

// เปลี่ยนเส้นทางกลับไปหน้า login หรือหน้าแรก
header("Location: login.php");
exit();
?>
