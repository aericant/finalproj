<?php

class MenuModel
{
    public function ensureSchema(): void
    {
        $columns = db_query("SHOW COLUMNS FROM daily_menu LIKE 'file_name'");
        $exists = mysqli_num_rows($columns) > 0;
        mysqli_free_result($columns);
        if (!$exists) {
            db_query('ALTER TABLE daily_menu ADD COLUMN file_name VARCHAR(255) DEFAULT NULL AFTER price');
        }
    }

    public function all()
    {
        return db_rows(db_query('SELECT item_id, item_name, category, FORMAT(price, 2) AS price, file_name, status FROM daily_menu ORDER BY item_id'));
    }

    public function nextId()
    {
        $result = db_query("SELECT item_id FROM daily_menu WHERE item_id LIKE 'MNU-%' ORDER BY item_id DESC LIMIT 1");
        $lastRow = $result ? mysqli_fetch_assoc($result) : null;
        if ($result) {
            mysqli_free_result($result);
        }
        $lastId = (string) ($lastRow['item_id'] ?? 'MNU-000');
        $number = (int) preg_replace('/[^0-9]/', '', $lastId);
        return 'MNU-' . str_pad((string) ($number + 1), 3, '0', STR_PAD_LEFT);
    }

    public function save($item, $upload)
    {
        global $connection;

        $uploadInfo = save_uploaded_jpg($upload, __DIR__ . '/../../images', 'image_', (string) $item['item_id']);
        if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE && $uploadInfo['file_name'] === '') {
            return false;
        }
        if ($uploadInfo['file_name'] !== '') {
            $item['file_name'] = $uploadInfo['file_name'];
        }

        $itemId = db_escape((string) $item['item_id']);
        $name = db_escape((string) $item['item_name']);
        $category = db_escape((string) $item['category']);
        $price = number_format((float) $item['price'], 2, '.', '');
        $fileName = db_escape((string) ($item['file_name'] ?? ''));
        $status = db_escape((string) ($item['status'] ?? 'Available'));
        $query = "INSERT INTO daily_menu (item_id, item_name, category, price, file_name, status)
            VALUES ('$itemId', '$name', '$category', '$price', '$fileName', '$status')
            ON DUPLICATE KEY UPDATE item_name = VALUES(item_name), category = VALUES(category),
            price = VALUES(price), file_name = COALESCE(NULLIF(VALUES(file_name), ''), file_name),
            status = VALUES(status)";
        return $connection && mysqli_query($connection, $query) !== false;
    }

    public function toggleStatus($itemId)
    {
        $itemId = db_escape($itemId);
        return db_query("UPDATE daily_menu SET status = IF(status = 'Available', 'Sold Out', 'Available') WHERE item_id = '$itemId'") !== false;
    }

    public function delete($itemId)
    {
        $itemId = db_escape($itemId);
        return delete_record("DELETE FROM daily_menu WHERE item_id = '$itemId' LIMIT 1", $itemId, 'Menu_') !== false;
    }
}
