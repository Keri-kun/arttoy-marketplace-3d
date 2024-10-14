<?php
include 'database.php';

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
        case 'delete':
            $res =  $db->update_delete("DELETE FROM category  WHERE CategoryID = :id ;", ["id" => $_GET['id']]);
            if ($res) {
                header('location:manage_category.php');
            }
            break;
        case 'count_category':
            $res =  $db->read("SELECT c.CategoryName, COUNT(c.CategoryName) AS _count
                    FROM `product`  AS p
                    INNER JOIN category AS c
                    ON p.CategoryID = c.CategoryID
                    GROUP BY c.CategoryName");
            if ($res) {
                foreach ($res as $key => $row) {
                    $row->_count = (int)$row->_count;
                }
                echo json_encode($res);
            }
            break;
        case 'count_all':
            $res =  $db->read("SELECT count(*) AS _count FROM category");
            $count_category = $res[0]->_count;

            $res =  $db->read("SELECT count(*) AS _count FROM product");
            $count_product = $res[0]->_count;

            echo json_encode(["category" => $count_category, "product" => $count_product]);
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'add':
            $res = $db->create("category", ["CategoryName" => $_POST['category']]);
            if ($res) {
                header('location:manage_category.php');
            }
            break;
        case 'edit':
            $res =  $db->update_delete("UPDATE  category SET CategoryName = :name WHERE CategoryID = :id ;", ["name" => $_POST['category'], "id" => $_POST['id']]);
            if ($res) {
                header('location:manage_category.php');
            }
            break;


        default:
            # code...
            break;
    }
}
