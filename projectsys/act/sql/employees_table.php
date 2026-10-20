<?php

require_once __DIR__ . '/../includes/db.php';

if (!$connection) {
    exit('Database connection failed. Start MySQL in XAMPP first.');
}

$query = "CREATE TABLE IF NOT EXISTS employees (
    employee_id VARCHAR(30) NOT NULL PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    role VARCHAR(80) NOT NULL,
    department VARCHAR(80) NOT NULL,
    shift VARCHAR(50) NOT NULL,
    file_name VARCHAR(255) DEFAULT NULL,
    status ENUM('Active', 'On Leave') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if (mysqli_query($connection, $query)) {
    echo "Table 'employees' created successfully.<br>";

    $check = mysqli_query($connection, 'SELECT COUNT(*) AS total FROM employees');
    $row = $check ? mysqli_fetch_assoc($check) : ['total' => 0];
    if ((int) $row['total'] === 0) {
        $seedSql = "INSERT INTO employees (employee_id, full_name, role, department, shift, status) VALUES
        ('EMP-101', 'Ana Santos', 'Head Barista', 'Coffee Bar', 'Morning Shift', 'Active'),
        ('EMP-102', 'Marco Reyes', 'Barista', 'Coffee Bar', 'Afternoon Shift', 'Active'),
        ('EMP-103', 'Lia Cruz', 'Cashier', 'Front Counter', 'Morning Shift', 'Active'),
        ('EMP-104', 'Noah Garcia', 'Pastry Specialist', 'Kitchen', 'Afternoon Shift', 'On Leave'),
        ('EMP-105', 'Mia Flores', 'Service Staff', 'Front Counter', 'Morning Shift', 'Active')";
        mysqli_query($connection, $seedSql);
        echo "Sample records seeded for 'employees'.<br>";
    }
} else {
    echo 'Unable to create the employees table: ' . htmlspecialchars(mysqli_error($connection), ENT_QUOTES, 'UTF-8');
}
