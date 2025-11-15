<?php
require_once 'db.php';

function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Fetch current settings to check if an update is needed
$sql = "SELECT * FROM settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

$current_time = time();
$last_updated_timestamp = strtotime($settings['last_updated']); // Assuming a 'last_updated' column
$schedule = $settings['update_schedule'];

$needs_update = false;

switch ($schedule) {
    case 'hourly':
        if ($current_time - $last_updated_timestamp >= 3600) {
            $needs_update = true;
        }
        break;
    case 'daily':
        if ($current_time - $last_updated_timestamp >= 86400) {
            $needs_update = true;
        }
        break;
    case 'weekly':
        if ($current_time - $last_updated_timestamp >= 604800) {
            $needs_update = true;
        }
        break;
    case 'monthly':
        if ($current_time - $last_updated_timestamp >= 2592000) { // 30 days
            $needs_update = true;
        }
        break;
}

if ($needs_update) {
    $new_password = generateRandomPassword();
    $sql = "UPDATE settings SET password = ?, last_updated = NOW() WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $new_password);
    $stmt->execute();
    echo "Password updated to: " . $new_password;
} else {
    echo "Password does not need to be updated yet.";
}

$conn->close();
