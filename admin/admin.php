<?php
require_once 'db.php';

// Handle password update
if (isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $update_schedule = $_POST['update_schedule'];

    $sql = "UPDATE settings SET password = ?, update_schedule = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $update_schedule);
    $stmt->execute();
}

// Fetch current settings
$sql = "SELECT * FROM settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

// Fetch connected devices
$sql = "SELECT * FROM connected_devices";
$connected_devices = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cornerstone Hotspot Admin</title>
    <style>
        body { font-family: sans-serif; }
        table { border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        tr:hover { background-color: #f5f5f5; }
        form { margin-bottom: 20px; }
        input[type=text], select { padding: 5px; }
        input[type=submit] { padding: 5px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Cornerstone Hotspot Admin</h1>

    <h2>Settings</h2>
    <form method="post" action="">
        <label for="new_password">New Password:</label>
        <input type="text" name="new_password" id="new_password" value="<?php echo htmlspecialchars($settings['password']); ?>">
        <br>
        <label for="update_schedule">Update Schedule:</label>
        <select name="update_schedule" id="update_schedule">
            <option value="hourly" <?php if ($settings['update_schedule'] == 'hourly') echo 'selected'; ?>>Hourly</option>
            <option value="daily" <?php if ($settings['update_schedule'] == 'daily') echo 'selected'; ?>>Daily</option>
            <option value="weekly" <?php if ($settings['update_schedule'] == 'weekly') echo 'selected'; ?>>Weekly</option>
            <option value="monthly" <?php if ($settings['update_schedule'] == 'monthly') echo 'selected'; ?>>Monthly</option>
        </select>
        <br>
        <input type="submit" name="update_password" value="Update Password">
    </form>

    <h2>Connected Devices</h2>
    <table border="1">
        <tr>
            <th>MAC Address</th>
            <th>IP Address</th>
            <th>Hostname</th>
            <th>Last Seen</th>
            <th>Data Usage</th>
        </tr>
        <?php while ($row = $connected_devices->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['mac_address']); ?></td>
            <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
            <td><?php echo htmlspecialchars($row['hostname']); ?></td>
            <td><?php echo htmlspecialchars($row['last_seen']); ?></td>
            <td><?php echo htmlspecialchars($row['data_usage']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
