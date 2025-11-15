<?php
require_once 'db.php';

header('Content-Type: application/json');

$sql = "SELECT password FROM settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response = [
        'password' => $row['password']
    ];
} else {
    $response = [
        'error' => 'No password found'
    ];
}

echo json_encode($response);

$conn->close();
