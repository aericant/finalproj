CREATE DATABASE IF NOT EXISTS `sip_n_tell_db`
	DEFAULT CHARACTER SET utf8mb4
	COLLATE utf8mb4_unicode_ci;

USE `sip_n_tell_db`;
CREATE TABLE IF NOT EXISTS `admin` (
	`admin_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(80) NOT NULL,
	`password_hash` VARCHAR(255) NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`admin_id`),
	UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `employees` (
	`employee_id` VARCHAR(30) NOT NULL,
	`full_name` VARCHAR(120) NOT NULL,
	`role` VARCHAR(80) NOT NULL,
	`department` VARCHAR(80) NOT NULL,
	`shift` VARCHAR(50) NOT NULL,
	`file_name` VARCHAR(255) DEFAULT NULL,
	`status` ENUM('Active', 'On Leave') NOT NULL DEFAULT 'Active',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB;

INSERT IGNORE INTO `employees`
	(`employee_id`, `full_name`, `role`, `department`, `shift`, `status`)
VALUES
	('EMP-101', 'Ana Santos', 'Head Barista', 'Coffee Bar', 'Morning Shift', 'Active'),
	('EMP-102', 'Marco Reyes', 'Barista', 'Coffee Bar', 'Afternoon Shift', 'Active'),
	('EMP-103', 'Lia Cruz', 'Cashier', 'Front Counter', 'Morning Shift', 'Active'),
	('EMP-104', 'Noah Garcia', 'Pastry Specialist', 'Kitchen', 'Afternoon Shift', 'On Leave'),
	('EMP-105', 'Mia Flores', 'Service Staff', 'Front Counter', 'Morning Shift', 'Active');

CREATE TABLE IF NOT EXISTS `daily_menu` (
	`item_id` VARCHAR(50) NOT NULL,
	`item_name` VARCHAR(100) NOT NULL,
	`category` VARCHAR(50) NOT NULL,
	`price` DECIMAL(10, 2) NOT NULL,
	`file_name` VARCHAR(255) DEFAULT NULL,
	`status` VARCHAR(20) NOT NULL DEFAULT 'Available',
	PRIMARY KEY (`item_id`)
) ENGINE=InnoDB;

INSERT IGNORE INTO `daily_menu`
	(`item_id`, `item_name`, `category`, `price`, `status`)
VALUES
	('MNU-001', 'Espresso', 'Coffee', 3.00, 'Available'),
	('MNU-002', 'Caramel Frappe', 'Frappe', 4.50, 'Available'),
	('MNU-003', 'Jasmine Tea', 'Tea', 3.25, 'Available'),
	('MNU-004', 'Mocha Pastry', 'Pastry', 5.75, 'Sold Out'),
	('MNU-005', 'Blueberry Muffin', 'Pastry', 2.95, 'Available');

CREATE TABLE IF NOT EXISTS `customers` (
	`customer_id` VARCHAR(30) NOT NULL,
	`full_name` VARCHAR(120) NOT NULL,
	`contact_number` VARCHAR(30) DEFAULT NULL,
	`payment_method` VARCHAR(40) DEFAULT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB;
