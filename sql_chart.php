<?php
include 'database.php';

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
        case 'category':
            $res = $db->read("SELECT c.CategoryName, COUNT(*) AS num
            FROM `product`  AS p
            INNER JOIN category  AS c
            ON p.CategoryID = c.CategoryID
            GROUP BY c.CategoryName");
            foreach ($res as $key => $row) {
                $row->num = (int)$row->num;
            }
            echo json_encode($res);
            break;
    }
}
