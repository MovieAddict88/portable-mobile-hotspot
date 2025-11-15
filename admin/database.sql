CREATE TABLE settings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    update_schedule VARCHAR(50) NOT NULL DEFAULT 'daily',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE connected_devices (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mac_address VARCHAR(255) NOT NULL,
    ip_address VARCHAR(255) NOT NULL,
    hostname VARCHAR(255),
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    data_usage BIGINT(20) UNSIGNED NOT NULL DEFAULT 0
);

INSERT INTO settings (password, update_schedule) VALUES ('password123', 'daily');
