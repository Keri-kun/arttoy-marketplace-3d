<?php
include 'include/header.php';
include 'include/script.php';
include 'include/navbar.php';
include 'database.php';

$db = new Database();
$conn = $db->getConnection();

// อัปเดตสถานะ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['Status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['Status'];

    // เพิ่ม 'รออนุมัติ' ในรายการที่อนุญาต
    $allowed = ['รอชำระ', 'รออนุมัติ', 'ชำระแล้ว', 'ยกเลิก', 'pending'];
    if (in_array($status, $allowed)) {
        $sql = "UPDATE orders SET Status = :status WHERE OrderID = :order_id";
        $params = [
            ':status' => $status,
            ':order_id' => $order_id
        ];
        try {
            $result = $db->update_delete($sql, $params);
            $msg = "อัปเดตสถานะเรียบร้อยแล้ว (Order: {$order_id})";
        } catch (PDOException $e) {
            $msg = "เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    } else {
        $msg = "สถานะไม่ถูกต้อง";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">📦 รายการสั่งซื้อ</h2>

    <?php if (!empty($msg)) { ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php
    $sql = "SELECT o.OrderID, u.username, o.Status, o.slip_image, o.OrderDate, o.TotalAmount
            FROM orders o 
            JOIN users u ON o.UserID = u.id 
            ORDER BY o.OrderDate DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($orders) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>ชื่อผู้สั่งซื้อ</th>
                    <th>ยอดรวม</th>
                    <th>สถานะการชำระเงิน</th>
                    <th>สลิปการโอน</th>
                </tr>
              </thead>
              <tbody>';

        foreach ($orders as $row) {
            // แสดงสถานะปัจจุบันด้วยสี
            $statusBadge = '';
            switch($row['Status']) {
                case 'ชำระแล้ว':
                    $statusBadge = '<span class="badge bg-success">ชำระแล้ว</span>';
                    break;
                case 'รออนุมัติ':
                    $statusBadge = '<span class="badge bg-info">รออนุมัติ</span>';
                    break;
                case 'ยกเลิก':
                    $statusBadge = '<span class="badge bg-danger">ยกเลิก</span>';
                    break;
                case 'pending':
                case 'รอชำระ':
                    $statusBadge = '<span class="badge bg-warning text-dark">รอชำระ</span>';
                    break;
                default:
                    $statusBadge = '<span class="badge bg-secondary">' . htmlspecialchars($row['Status']) . '</span>';
            }

            echo '<tr>
                    <td><strong>' . htmlspecialchars($row['OrderID']) . '</strong></td>
                    <td>' . htmlspecialchars($row['username']) . '</td>
                    <td>' . number_format($row['TotalAmount'], 2) . ' ฿</td>
                    <td>
                        <div class="mb-2">' . $statusBadge . '</div>
                        <form method="post" class="d-flex align-items-center gap-2" onsubmit="return confirm(\'ยืนยันการเปลี่ยนสถานะ?\')">
                            <input type="hidden" name="order_id" value="' . htmlspecialchars($row['OrderID']) . '">
                            <select name="Status" class="form-select form-select-sm" style="width: auto;">
                                <option value="รอชำระ"' . (in_array($row['Status'], ['รอชำระ', 'pending']) ? ' selected' : '') . '>รอชำระ</option>
                                <option value="รออนุมัติ"' . ($row['Status'] == 'รออนุมัติ' ? ' selected' : '') . '>รออนุมัติ</option>
                                <option value="ชำระแล้ว"' . ($row['Status'] == 'ชำระแล้ว' ? ' selected' : '') . '>ชำระแล้ว</option>
                                <option value="ยกเลิก"' . ($row['Status'] == 'ยกเลิก' ? ' selected' : '') . '>ยกเลิก</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">อัพเดต</button>
                        </form>
                    </td>
                    <td class="text-center">';
                        if (!empty($row['slip_image'])) {
                            echo '<a href="uploads_slips/' . htmlspecialchars($row['slip_image']) . '" target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-image"></i> ดูสลิป
                                  </a>';
                        } else {
                            echo '<span class="text-muted">ไม่มีสลิป</span>';
                        }
            echo    '</td>
                  </tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">ยังไม่มีรายการสั่งซื้อ</div>';
    }

    $conn = null;
    ?>
</div>

<?php include 'include/footer.php'; ?>