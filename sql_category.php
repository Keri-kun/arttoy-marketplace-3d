<?php
include 'database.php';

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
        case 'view':

            break;
        case 'delete':
            $res =  $db->update_delete("DELETE FROM category  WHERE CategoryID = :id ;", ["id" => $_GET['id']]);
            if ($res) {
                header('location:manage_category.php');
            }
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'add':
            $res = $db->create("category", ["CategoryName" => $_POST['CategoryName']]);
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
    }
}
