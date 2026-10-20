<?php

define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sip_n_tell_db');

$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
if (mysqli_connect_errno()) {
    die('Database connection failed: ' . mysqli_connect_error() . '(' . mysqli_connect_errno() . ')');
}

$dbCreateQuery = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
mysqli_query($connection, $dbCreateQuery);

mysqli_select_db($connection, DB_NAME);
mysqli_set_charset($connection, 'utf8mb4');

function confirm_query($result_set)
{
    if (!$result_set) {
        die('Database query failed');
    }
}

function db_escape($value)
{
    global $connection;
    return $connection ? mysqli_real_escape_string($connection, $value) : addslashes($value);
}

function db_query($sql)
{
    global $connection;
    $result = mysqli_query($connection, $sql);
    confirm_query($result);
    return $result;
}

function db_rows($result)
{
    if (!$result) {
        return [];
    }

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $rows;
}

function delete_record($sql, $id_to_delete, $entity, $imageFileName = null)
{
    global $connection;

    $delRecord = mysqli_query($connection, $sql) or die(mysqli_error($connection));
    confirm_query($delRecord);

    if ($imageFileName === null) {
        if (in_array($entity, ['Employee_', 'Menu_'], true)) {
            $imageFileName = $id_to_delete . '.jpg';
        } else {
            $imageFileName = 'image_' . $entity . $id_to_delete . '.jpg';
        }
    }

    if ($imageFileName !== '' && basename($imageFileName) === $imageFileName) {
        $imagePath = __DIR__ . '/../images/' . $imageFileName;
        if (is_file($imagePath) && !unlink($imagePath)) {
            return false;
        }
    }

    return $delRecord;
}
