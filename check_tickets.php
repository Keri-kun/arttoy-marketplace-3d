<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT total_tickets FROM tickets WHERE id=1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
$tickets_left = $data['total_tickets'];

header('Content-Type: application/json');
echo json_encode(['tickets_left' => $tickets_left]);

$conn->close();
?>
