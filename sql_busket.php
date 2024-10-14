<?php
include 'database.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'count_busket':
                // ดึงจำนวนสินค้าที่อยู่ในตะกร้า
                $res = $db->read("SELECT COUNT(*) AS num FROM busket");
                $response = array(
                    "num" => isset($res[0]->num) ? $res[0]->num : 0
                );

                echo json_encode($response);
                break;

            default:
                echo json_encode(array("error" => "Invalid action"));
                break;
        }
    } else {
        echo json_encode(array("error" => "No action specified"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_busket':
                if (isset($_POST['ProductID'])) {
                    // ตรวจสอบว่าสินค้านี้อยู่ในตะกร้าแล้วหรือยัง
                    $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE ProductID = :ProductID;", [":ProductID" => $_POST['ProductID']]);
                    if ($res && $res[0]->num == 0) {
                        // ถ้ายังไม่มี ให้เพิ่มสินค้าใหม่เข้าไป
                        $db->create(
                            "busket",
                            [
                                "ProductID" => $_POST['ProductID'],
                                "quantity" => 1
                            ]
                        );
                    } else {
                        // ถ้ามีแล้ว ให้เพิ่มจำนวนสินค้า
                        $db->update_delete("UPDATE busket SET quantity = quantity + 1 WHERE ProductID = :ProductID;", [":ProductID" => $_POST['ProductID']]);
                    }

                    // นับจำนวนสินค้าหลังจากอัปเดตตะกร้าแล้ว
                    $res = $db->read("SELECT COUNT(*) AS num FROM busket");
                    $response = array(
                        "num" => isset($res[0]->num) ? $res[0]->num : 0
                    );

                    echo json_encode($response);
                } else {
                    echo json_encode(array("error" => "ProductID is required"));
                }
                break;

            case 'add_order':
                // เพิ่มคำสั่งซื้อใหม่
                $orderID = time();  // สร้าง ID สำหรับ Order โดยใช้ timestamp
                $result = $db->update_delete(
                    "INSERT INTO order_product (OrderID, ProductID, amount) 
                     SELECT :OrderID AS OrderID, ProductID, quantity FROM busket;",
                    [":OrderID" => $orderID]
                );

                // ลบข้อมูลในตะกร้าหลังจากทำการสั่งซื้อแล้ว
                $db->update_delete("DELETE FROM busket;", []);

                echo json_encode(array("success" => true, "message" => "Order has been placed", "orderID" => $orderID));
                break;

            case 'delete_product':
                if (isset($_POST['cart_id'])) {
                    $cart_id = $_POST['cart_id'];

                    // ลบสินค้าออกจากตะกร้า
                    $query = "DELETE FROM busket WHERE CartID = :CartID";

                    // ใช้ update_delete ในการรันคำสั่ง
                    $result = $db->update_delete($query, [":CartID" => $cart_id]);

                    if ($result) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'ไม่สามารถลบสินค้าได้']);
                    }
                } else {
                    echo json_encode(array("error" => "CartID is required"));
                }
                break;

            default:
                echo json_encode(array("error" => "Invalid action"));
                break;
        }
    } else {
        echo json_encode(array("error" => "No action specified"));
    }
} else {
    echo json_encode(array("error" => "Invalid request method"));
}
?>
