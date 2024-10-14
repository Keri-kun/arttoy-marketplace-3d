<?php
include 'database.php';

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
        case 'delete':
            $res =  $db->update_delete("DELETE FROM product  WHERE ProductID = :id ;", ["id" => $_GET['id']]);
            if ($res) {
                header('location:manage_product.php');
            }
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'add':

            if (isset($_FILES['ImageURL']) && $_FILES['ImageURL']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['ImageURL']['tmp_name'];
                $fileName = $_FILES['ImageURL']['name'];
                $fileSize = $_FILES['ImageURL']['size'];
                $fileType = $_FILES['ImageURL']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // อนุญาตเฉพาะประเภทไฟล์ภาพที่ระบุ
                $allowedExts = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($fileExtension, $allowedExts)) {
                    // สร้างชื่อไฟล์สุ่ม
                    $newFileName = uniqid('product_', true) . '.' . $fileExtension;

                    // กำหนดตำแหน่งที่จัดเก็บไฟล์
                    $uploadFileDir = './uploaded_images/';
                    $dest_path = $uploadFileDir . $newFileName;

                    // ตรวจสอบว่ามีไดเรกทอรีสำหรับเก็บไฟล์หรือไม่ ถ้าไม่มีก็สร้างใหม่
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    // ย้ายไฟล์จาก temporary directory ไปยังตำแหน่งที่กำหนด
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $_POST['ImageURL'] = $newFileName;
                    } else {
                        die('There was some error moving the file to the upload directory.');
                    }
                } else {
                    die('Upload failed. Allowed file types: jpg, jpeg, png, gif.');
                }
            } else {
                die('There was some error uploading the file. Error code: ' . $_FILES['ImageURL']['error']);
            }

            $res = $db->create(
                "product",
                [
                    "ProductName" => $_POST['ProductName'], "Description" => $_POST['Description'], "CategoryID" => $_POST['category'], "Price" => $_POST['Price'], "ImageURL" => $_POST['ImageURL']
                ]
            );
            if ($res) {
                header('location:manage_product.php');
            }
            break;
        case 'edit':


            if (isset($_FILES['ImageURL']) && $_FILES['ImageURL']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['ImageURL']['tmp_name'];
                $fileName = $_FILES['ImageURL']['name'];
                $fileSize = $_FILES['ImageURL']['size'];
                $fileType = $_FILES['ImageURL']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // อนุญาตเฉพาะประเภทไฟล์ภาพที่ระบุ
                $allowedExts = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($fileExtension, $allowedExts)) {
                    // สร้างชื่อไฟล์สุ่ม
                    $newFileName = uniqid('product_', true) . '.' . $fileExtension;



                    // กำหนดตำแหน่งที่จัดเก็บไฟล์
                    $uploadFileDir = './uploaded_images/';
                    $dest_path = $uploadFileDir . $newFileName;

                    // ตรวจสอบว่ามีไดเรกทอรีสำหรับเก็บไฟล์หรือไม่ ถ้าไม่มีก็สร้างใหม่
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    // ย้ายไฟล์จาก temporary directory ไปยังตำแหน่งที่กำหนด
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $_POST['ImageURL'] = $newFileName;
                    } else {
                        die('There was some error moving the file to the upload directory.');
                    }
                } else {
                    die('Upload failed. Allowed file types: jpg, jpeg, png, gif.');
                }
            } else {
                $_POST['ImageURL'] = $_POST['image_old'];
            }

            $res =  $db->update_delete("UPDATE  product SET ProductName = :ProductName, Description = :Description
            , CategoryID = :CategoryID, Price = :Price , ImageURL = :ImageURL 
            WHERE ProductID = :id ;", [
                "ProductName" => $_POST['ProductName'], "Description" => $_POST['Description'], "CategoryID" => $_POST['category'], "Price" => $_POST['Price'], "ImageURL" => $_POST['ImageURL'], "id" => $_POST['id']
            ]);

            if ($res) {
                header('location:manage_product.php');
            }
            break;


        default:
            # code...
            break;
    }
}
