<?php

require_once __DIR__ . '/../includes/db.php';

if (!$connection) {
    exit('Database connection failed. Start MySQL in XAMPP first.');
}

$query = "CREATE TABLE IF NOT EXISTS daily_menu (
    item_id VARCHAR(50) PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    file_name VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB";

if (mysqli_query($connection, $query)) {
    echo "Table 'daily_menu' created successfully.<br>";
    $check = mysqli_query($connection, 'SELECT COUNT(*) AS total FROM daily_menu');
    $row = $check ? mysqli_fetch_assoc($check) : ['total' => 0];
    if ((int) $row['total'] === 0) {
        $seedSql = "INSERT INTO daily_menu (item_id, item_name, category, price, status) VALUES
        ('MNU-001', 'Espresso', 'Coffee', 3.00, 'Available'),
        ('MNU-002', 'Caramel Frappe', 'Frappe', 4.50, 'Available'),
        ('MNU-003', 'Jasmine Tea', 'Tea', 3.25, 'Available'),
        ('MNU-004', 'Mocha Pastry', 'Pastry', 5.75, 'Sold Out'),
        ('MNU-005', 'Blueberry Muffin', 'Pastry', 2.95, 'Available')";
        mysqli_query($connection, $seedSql);
        echo "Sample records seeded for 'daily_menu'.<br>";
    }
} else {
    echo 'Unable to create the daily menu table: ' . htmlspecialchars(mysqli_error($connection), ENT_QUOTES, 'UTF-8');
}

$columnCheck = mysqli_query($connection, "SHOW COLUMNS FROM daily_menu LIKE 'file_name'");
if ($columnCheck && mysqli_num_rows($columnCheck) === 0) {
    mysqli_query($connection, 'ALTER TABLE daily_menu ADD COLUMN file_name VARCHAR(255) DEFAULT NULL AFTER price');
}
if ($columnCheck) {
    mysqli_free_result($columnCheck);
}
