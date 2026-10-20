<?php

class CustomerModel
{
    public function ensureSchema(): void
    {
        db_query("CREATE TABLE IF NOT EXISTS customers (
            customer_id VARCHAR(30) PRIMARY KEY,
            full_name VARCHAR(120) NOT NULL,
            contact_number VARCHAR(30) DEFAULT NULL,
            payment_method VARCHAR(40) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");

        $indexResult = db_query("SHOW INDEX FROM customers WHERE Key_name = 'uq_customers_customer_id'");
        $indexExists = mysqli_num_rows($indexResult) > 0;
        mysqli_free_result($indexResult);
        if (!$indexExists) {
            db_query('ALTER TABLE customers ADD UNIQUE KEY uq_customers_customer_id (customer_id)');
        }
    }

    public function all()
    {
        return db_rows(db_query('SELECT customer_id, full_name, contact_number, payment_method FROM customers ORDER BY full_name'));
    }

    public function find($customerId)
    {
        $customerId = db_escape($customerId);
        $rows = db_rows(db_query("SELECT customer_id, full_name, contact_number, payment_method FROM customers WHERE customer_id = '$customerId' LIMIT 1"));
        return $rows[0] ?? null;
    }

    public function save($customer)
    {
        global $connection;

        $customerId = db_escape(trim((string) ($customer['customer_id'] ?? '')));
        $name = db_escape(trim((string) ($customer['full_name'] ?? '')));
        $phone = db_escape(trim((string) ($customer['contact_number'] ?? '')));
        $payment = db_escape(trim((string) ($customer['payment_method'] ?? '')));
        if ($customerId === '' || $name === '') {
            return false;
        }

        $query = "INSERT INTO customers (customer_id, full_name, contact_number, payment_method)
            VALUES ('$customerId', '$name', '$phone', '$payment')
            ON DUPLICATE KEY UPDATE full_name = VALUES(full_name),
            contact_number = VALUES(contact_number), payment_method = VALUES(payment_method)";
        return mysqli_query($connection, $query) !== false;
    }

    public function delete($customerId)
    {
        $customerId = db_escape($customerId);
        return db_query("DELETE FROM customers WHERE customer_id = '$customerId' LIMIT 1") !== false;
    }

    public function nextId()
    {
        $result = db_query("SELECT customer_id FROM customers WHERE customer_id LIKE 'CUS-%' ORDER BY customer_id DESC LIMIT 1");
        $lastRow = $result ? mysqli_fetch_assoc($result) : null;
        if ($result) {
            mysqli_free_result($result);
        }
        $lastId = (string) ($lastRow['customer_id'] ?? 'CUS-000');
        $number = (int) preg_replace('/[^0-9]/', '', $lastId);
        return 'CUS-' . str_pad((string) ($number + 1), 3, '0', STR_PAD_LEFT);
    }
}
