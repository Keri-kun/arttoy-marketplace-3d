<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['Status'] ?? null; // ป้องกัน undefined

    if (!$order_id || !$status) {
        die("ข้อมูลไม่ครบ: OrderID หรือ Status ไม่มีค่า");
    }

    $allowed = ['รอชำระ', 'ชำระแล้ว', 'ยกเลิก', 'pending'];
    if (!in_array($status, $allowed)) {
        die("สถานะไม่ถูกต้อง: $status");
    }

    // อัปเดต orders
    $stmt = $conn->prepare("UPDATE orders SET Status=? WHERE OrderID=?");
    $stmt->bind_param("ss", $status, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // อัปเดต order_product
        $stmt2 = $conn->prepare("UPDATE order_product SET st=? WHERE OrderID=?");
        $stmt2->bind_param("ss", $status, $order_id);
        $stmt2->execute();
        $stmt2->close();

        echo "อัปเดตสำเร็จ ✅";
    } else {
        echo "ไม่พบ OrderID หรือสถานะเหมือนเดิม";
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method");
}
?>
