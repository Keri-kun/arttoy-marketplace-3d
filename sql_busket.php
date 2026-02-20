<?php
session_start();
include 'database.php';

$db = new Database();

// ตรวจสอบว่า login แล้วมี user_id หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "กรุณาเข้าสู่ระบบก่อนใช้งานตะกร้า"]);
    exit;
}

$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'count_busket':
                $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE UserID = :UserID", [":UserID" => $userID]);
                echo json_encode(["num" => $res[0]->num ?? 0]);
                break;

            case 'get_cart_items':
                $res = $db->read("
                    SELECT b.CartID, b.ProductID, b.quantity as Quantity, 
                           p.ProductName, p.Price, p.ImageURL, 
                           c.CategoryName
                    FROM busket b 
                    INNER JOIN product p ON b.ProductID = p.ProductID 
                    INNER JOIN category c ON p.CategoryID = c.CategoryID 
                    WHERE b.UserID = :UserID 
                    ORDER BY b.CartID DESC
                ", [":UserID" => $userID]);
                
                echo json_encode(["success" => true, "items" => $res ?? []]);
                break;

            default:
                echo json_encode(["error" => "Invalid action"]);
        }
    } else {
        echo json_encode(["error" => "No action specified"]);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {

            case 'add_busket':
                if (isset($_POST['ProductID'])) {
                    $productID = $_POST['ProductID'];

                    // ตรวจสอบว่ามีสินค้าในตะกร้าแล้วหรือยัง
                    $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE ProductID = :ProductID AND UserID = :UserID", [
                        ":ProductID" => $productID,
                        ":UserID" => $userID
                    ]);

                    if ($res && $res[0]->num == 0) {
                        $db->create("busket", [
                            "UserID" => $userID,
                            "ProductID" => $productID,
                            "quantity" => 1
                        ]);
                    } else {
                        $db->update_delete("UPDATE busket 
                                            SET quantity = quantity + 1 
                                            WHERE ProductID = :ProductID AND UserID = :UserID", [
                            ":ProductID" => $productID,
                            ":UserID" => $userID
                        ]);
                    }

                    $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE UserID = :UserID", [":UserID" => $userID]);
                    echo json_encode(["success" => true, "num" => $res[0]->num ?? 0]);
                } else {
                    echo json_encode(["error" => "ProductID is required"]);
                }
                break;

            case 'update_quantity':
                // แก้ไข: รับ CartID แทน ProductID
                if (isset($_POST['CartID']) && isset($_POST['quantity'])) {
                    $cartID = $_POST['CartID'];
                    $quantity = intval($_POST['quantity']);
                    
                    if ($quantity <= 0) {
                        $result = $db->update_delete("DELETE FROM busket WHERE CartID = :CartID AND UserID = :UserID", [
                            ":CartID" => $cartID,
                            ":UserID" => $userID
                        ]);
                    } else {
                        $result = $db->update_delete("UPDATE busket 
                                                      SET quantity = :quantity 
                                                      WHERE CartID = :CartID AND UserID = :UserID", [
                            ":quantity" => $quantity,
                            ":CartID" => $cartID,
                            ":UserID" => $userID
                        ]);
                    }
                    
                    if ($result) {
                        $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE UserID = :UserID", [":UserID" => $userID]);
                        echo json_encode(["success" => true, "cart_count" => $res[0]->num ?? 0]);
                    } else {
                        echo json_encode(["error" => "ไม่สามารถอัพเดทจำนวนสินค้าได้"]);
                    }
                } else {
                    echo json_encode(["error" => "CartID and quantity are required"]);
                }
                break;

            case 'delete_product':
                if (isset($_POST['cart_id'])) {
                    $cart_id = $_POST['cart_id'];
                    $result = $db->update_delete("DELETE FROM busket WHERE CartID = :CartID AND UserID = :UserID", [
                        ":CartID" => $cart_id,
                        ":UserID" => $userID
                    ]);
                    
                    if ($result) {
                        $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE UserID = :UserID", [":UserID" => $userID]);
                        echo json_encode(['success' => true, 'cart_count' => $res[0]->num ?? 0]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'ไม่สามารถลบสินค้าได้']);
                    }
                } else {
                    echo json_encode(["error" => "CartID is required"]);
                }
                break;

            // เพิ่ม case สำหรับ remove_item
            case 'remove_item':
                if (isset($_POST['CartID'])) {
                    $cartID = $_POST['CartID'];
                    $result = $db->update_delete("DELETE FROM busket WHERE CartID = :CartID AND UserID = :UserID", [
                        ":CartID" => $cartID,
                        ":UserID" => $userID
                    ]);
                    
                    if ($result) {
                        $res = $db->read("SELECT COUNT(*) AS num FROM busket WHERE UserID = :UserID", [":UserID" => $userID]);
                        echo json_encode(['success' => true, 'cart_count' => $res[0]->num ?? 0]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'ไม่สามารถลบสินค้าได้']);
                    }
                } else {
                    echo json_encode(["error" => "CartID is required"]);
                }
                break;

            case 'add_order':
                // สร้าง OrderID เอง (เช่น ORD20250818120001)
                $orderID = "ORD" . date("YmdHis");

                // ดึงรายการสินค้าในตะกร้าของผู้ใช้ พร้อม ProductName
                $cartItems = $db->read("
                    SELECT b.ProductID, b.quantity, p.Price, p.ProductName
                    FROM busket b
                    JOIN product p ON b.ProductID = p.ProductID
                    WHERE b.UserID = :UserID
                    ", [":UserID" => $userID]);

                if (empty($cartItems)) {
                    echo json_encode(["error" => "ตะกร้าของคุณว่างเปล่า"]);
                    exit;
                }

                $totalAmount = 0;
                foreach ($cartItems as $item) {
                    $totalAmount += $item->Price * $item->quantity;
                }

                // บันทึกคำสั่งซื้อ
                $db->update_delete("INSERT INTO orders (OrderID, UserID, TotalAmount, Status) 
                            VALUES (:OrderID, :UserID, :TotalAmount, 'pending')", [
                    ":OrderID" => $orderID,
                    ":UserID" => $userID,
                    ":TotalAmount" => $totalAmount
                ]);

                // บันทึกรายการสินค้าใน order_product พร้อม price และ ProductName
                foreach ($cartItems as $item) {
                    $db->update_delete("INSERT INTO order_product (OrderID, ProductID, amount, price, ProductName) 
                                        VALUES (:OrderID, :ProductID, :amount, :price, :ProductName)", [
                        ":OrderID" => $orderID,
                        ":ProductID" => $item->ProductID,
                        ":amount" => $item->quantity,
                        ":price" => $item->Price,
                        ":ProductName" => $item->ProductName
                    ]);
                }

                // ล้างตะกร้า
                $db->update_delete("DELETE FROM busket WHERE UserID = :UserID", [":UserID" => $userID]);

                echo json_encode(["success" => true, "message" => "คำสั่งซื้อสำเร็จ", "orderID" => $orderID]);
                break;

            default:
                echo json_encode(["error" => "Invalid action"]);
        }
    } else {
        echo json_encode(["error" => "No action specified"]);
    }

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>