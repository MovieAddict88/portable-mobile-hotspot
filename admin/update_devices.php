<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $devices = json_decode($json, true);

    if (is_array($devices)) {
        foreach ($devices as $device) {
            $ip_address = $device['ip_address'];
            // In a real scenario, you'd get the MAC address and hostname from the device
            $mac_address = '00:00:00:00:00:00';
            $hostname = 'unknown';
            $data_usage = 0;

            $sql = "INSERT INTO connected_devices (mac_address, ip_address, hostname, data_usage) VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE ip_address = ?, hostname = ?, last_seen = NOW()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisss", $mac_address, $ip_address, $hostname, $data_usage, $ip_address, $hostname);
            $stmt->execute();
        }
        echo "Device list updated.";
    } else {
        echo "Invalid data.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
