<?php
include 'database.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
        case 'delete':
            $res = $db->update_delete("DELETE FROM product WHERE ProductID = :id", ["id" => $_GET['id']]);
            if ($res) {
                header('location:manage_product.php');
            }
            break;
            
        case 'increase_stock':
            // เพิ่มจำนวนสินค้า
            $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 1;
            $res = $db->update_delete(
                "UPDATE product SET Quantity = Quantity + :amount WHERE ProductID = :id",
                ["amount" => $amount, "id" => $_GET['id']]
            );
            if ($res) {
                header('location:manage_product.php?msg=stock_increased');
            }
            break;
            
        case 'decrease_stock':
            // ลดจำนวนสินค้า
            $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 1;
            // ตรวจสอบว่ามีสินค้าเพียงพอหรือไม่
            $product = $db->read("SELECT Quantity FROM product WHERE ProductID = :id", ["id" => $_GET['id']]);
            if ($product && $product[0]['Quantity'] >= $amount) {
                $res = $db->update_delete(
                    "UPDATE product SET Quantity = Quantity - :amount WHERE ProductID = :id",
                    ["amount" => $amount, "id" => $_GET['id']]
                );
                if ($res) {
                    header('location:manage_product.php?msg=stock_decreased');
                }
            } else {
                header('location:manage_product.php?error=insufficient_stock');
            }
            break;
            
        case 'set_stock':
            // กำหนดจำนวนสินค้าโดยตรง
            $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 0;
            $res = $db->update_delete(
                "UPDATE product SET Quantity = :quantity WHERE ProductID = :id",
                ["quantity" => $quantity, "id" => $_GET['id']]
            );
            if ($res) {
                header('location:manage_product.php?msg=stock_updated');
            }
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'add':
            // จัดการอัปโหลด ImageURL
            if (isset($_FILES['ImageURL']) && $_FILES['ImageURL']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['ImageURL']['tmp_name'];
                $fileName = $_FILES['ImageURL']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($fileExtension, $allowedExts)) {
                    $newFileName = uniqid('product_', true) . '.' . $fileExtension;
                    $uploadFileDir = './uploaded_images/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $_POST['ImageURL'] = $newFileName;
                    } else {
                        die('There was some error moving the image file.');
                    }
                } else {
                    die('Upload failed. Allowed image file types: jpg, jpeg, png, gif.');
                }
            } else {
                die('There was some error uploading the image file.');
            }

            // จัดการอัปโหลด ModelURL
            $modelFileName = null;
            if (isset($_FILES['ModelURL']) && $_FILES['ModelURL']['error'] == UPLOAD_ERR_OK) {
                $modelTmpPath = $_FILES['ModelURL']['tmp_name'];
                $modelName = $_FILES['ModelURL']['name'];
                $modelExtension = strtolower(pathinfo($modelName, PATHINFO_EXTENSION));

                if ($modelExtension == 'glb') {
                    $modelFileName = uniqid('model_', true) . '.' . $modelExtension;
                    $modelDir = './uploaded_models/';
                    $modelPath = $modelDir . $modelFileName;

                    if (!is_dir($modelDir)) {
                        mkdir($modelDir, 0755, true);
                    }

                    if (!move_uploaded_file($modelTmpPath, $modelPath)) {
                        die('Error uploading model file.');
                    }
                } else {
                    die('Only .glb model files are allowed.');
                }
            }

            // บันทึกข้อมูล (รวมจำนวนสินค้า)
            $quantity = isset($_POST['Quantity']) ? (int)$_POST['Quantity'] : 0;
            
            $res = $db->create("product", [
                "ProductName" => $_POST['ProductName'],
                "Description" => $_POST['Description'],
                "CategoryID" => $_POST['category'],
                "Price" => $_POST['Price'],
                "Quantity" => $quantity,
                "ImageURL" => $_POST['ImageURL'],
                "ModelURL" => $modelFileName
            ]);

            if ($res) {
                header('location:manage_product.php');
            }
            break;

        case 'edit':
            // Image
            if (isset($_FILES['ImageURL']) && $_FILES['ImageURL']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['ImageURL']['tmp_name'];
                $fileName = $_FILES['ImageURL']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExts = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($fileExtension, $allowedExts)) {
                    $newFileName = uniqid('product_', true) . '.' . $fileExtension;
                    $uploadFileDir = './uploaded_images/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $_POST['ImageURL'] = $newFileName;
                    } else {
                        die('Error moving image file.');
                    }
                } else {
                    die('Invalid image file type.');
                }
            } else {
                $_POST['ImageURL'] = $_POST['image_old'];
            }

            // Model
            $modelFileName = $_POST['model_old'] ?? null;
            if (isset($_FILES['ModelURL']) && $_FILES['ModelURL']['error'] == UPLOAD_ERR_OK) {
                $modelTmpPath = $_FILES['ModelURL']['tmp_name'];
                $modelName = $_FILES['ModelURL']['name'];
                $modelExtension = strtolower(pathinfo($modelName, PATHINFO_EXTENSION));

                if ($modelExtension == 'glb') {
                    $modelFileName = uniqid('model_', true) . '.' . $modelExtension;
                    $modelDir = './uploaded_models/';
                    $modelPath = $modelDir . $modelFileName;

                    if (!is_dir($modelDir)) {
                        mkdir($modelDir, 0755, true);
                    }

                    if (!move_uploaded_file($modelTmpPath, $modelPath)) {
                        die('Error uploading model file.');
                    }
                } else {
                    die('Only .glb files allowed.');
                }
            }

            $quantity = isset($_POST['Quantity']) ? (int)$_POST['Quantity'] : 0;

            $res = $db->update_delete("UPDATE product SET ProductName = :ProductName, Description = :Description, CategoryID = :CategoryID, Price = :Price, Quantity = :Quantity, ImageURL = :ImageURL, ModelURL = :ModelURL WHERE ProductID = :id", [
                "ProductName" => $_POST['ProductName'],
                "Description" => $_POST['Description'],
                "CategoryID" => $_POST['category'],
                "Price" => $_POST['Price'],
                "Quantity" => $quantity,
                "ImageURL" => $_POST['ImageURL'],
                "ModelURL" => $modelFileName,
                "id" => $_POST['id']
            ]);

            if ($res) {
                header('location:manage_product.php');
            }
            break;
            
        case 'adjust_stock':
            // ปรับปรุงสต็อกแบบละเอียด (POST method)
            $productId = $_POST['id'];
            $adjustment = (int)$_POST['adjustment']; // จำนวนที่ต้องการเพิ่ม/ลด (+ หรือ -)
            
            if ($adjustment < 0) {
                // ถ้าลด ต้องเช็คว่ามีสินค้าพอหรือไม่
                $product = $db->read("SELECT Quantity FROM product WHERE ProductID = :id", ["id" => $productId]);
                if ($product && $product[0]['Quantity'] >= abs($adjustment)) {
                    $res = $db->update_delete(
                        "UPDATE product SET Quantity = Quantity + :adjustment WHERE ProductID = :id",
                        ["adjustment" => $adjustment, "id" => $productId]
                    );
                    if ($res) {
                        echo json_encode(["success" => true, "message" => "Stock decreased successfully"]);
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Insufficient stock"]);
                }
            } else {
                // เพิ่มสต็อก
                $res = $db->update_delete(
                    "UPDATE product SET Quantity = Quantity + :adjustment WHERE ProductID = :id",
                    ["adjustment" => $adjustment, "id" => $productId]
                );
                if ($res) {
                    echo json_encode(["success" => true, "message" => "Stock increased successfully"]);
                }
            }
            break;
    }
}
?>