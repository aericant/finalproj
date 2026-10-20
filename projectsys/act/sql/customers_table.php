<?php

require_once __DIR__ . '/../includes/db.php';

if (!$connection) {
    exit('Database connection failed. Start MySQL in XAMPP first.');
}

$query = "CREATE TABLE IF NOT EXISTS customers (
    customer_id VARCHAR(30) PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    contact_number VARCHAR(30) DEFAULT NULL,
    payment_method VARCHAR(40) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if (mysqli_query($connection, $query)) {
    echo "Table 'customers' created successfully.<br>";
} else {
    echo 'Unable to create the customers table: ' . htmlspecialchars(mysqli_error($connection), ENT_QUOTES, 'UTF-8');
}

$statusColumn = mysqli_query($connection, "SHOW COLUMNS FROM customers LIKE 'status'");
if ($statusColumn && mysqli_num_rows($statusColumn) > 0) {
    mysqli_query($connection, 'ALTER TABLE customers DROP COLUMN status');
}
if ($statusColumn) {
    mysqli_free_result($statusColumn);
}
