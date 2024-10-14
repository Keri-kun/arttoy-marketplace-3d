<?php

session_start();

include 'database.php';

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':

                $result = ['status' => 'error', 'message' => 'ไม่พบข้อมูลบัญชีผู้ใช้'];

                $res =  $db->read("SELECT * FROM users  WHERE username = :username LIMIT 1;", ["username" => $_POST['username']]);
                if (count($res) > 0) {
                    $data = $res[0];
                    if (password_verify($_POST['password'], $data->PasswordHash)) {
                        $_SESSION['username'] = $_POST['username'];
                        $result = ['status' => 'success', 'message' => 'เข้าสู่ระบบสำเร็จ'];
                    } else {
                        $result = ['status' => 'error', 'message' => 'รหัสผ่านไม่ถูกต้อง'];
                    }
                }

                echo json_encode($result);
                break;


            default:
                # code...
                break;
        }
    }
}
