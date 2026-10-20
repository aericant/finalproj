<?php

require_once __DIR__ . '/db_model.php';

function save_uploaded_jpg($upload, $directory, $prefix = 'image_', $fileNameBase = '')
{
    if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['file_name' => '', 'error' => ''];
    }

    if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ['file_name' => '', 'error' => 'The image upload failed.'];
    }

    $extension = strtolower(pathinfo($upload['name'] ?? '', PATHINFO_EXTENSION));
    $imageInfo = @getimagesize($upload['tmp_name'] ?? '');
    $isJpg = $imageInfo
        && ($imageInfo['mime'] ?? '') === 'image/jpeg'
        && in_array($extension, ['jpg', 'jpeg'], true);

    if (!$isJpg || ($upload['size'] ?? 0) > 5 * 1024 * 1024) {
        return ['file_name' => '', 'error' => 'Please choose a JPG image no larger than 5 MB.'];
    }

    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        return ['file_name' => '', 'error' => 'The image directory could not be created.'];
    }

    $safeFileNameBase = preg_replace('/[^A-Za-z0-9_-]/', '_', $fileNameBase);
    if ($safeFileNameBase !== '') {
        $fileName = trim($safeFileNameBase, '_') . '.jpg';
    } else {
        try {
            $fileName = $prefix . bin2hex(random_bytes(8)) . '.jpg';
        } catch (Exception $exception) {
            $fileName = $prefix . uniqid('', true) . '.jpg';
        }
    }

    if (!move_uploaded_file($upload['tmp_name'], rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName)) {
        return ['file_name' => '', 'error' => 'The image could not be saved.'];
    }

    return ['file_name' => $fileName, 'error' => ''];
}

function get_flash_message()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $flash = [
        'message' => $_SESSION['flash_message'] ?? '',
        'type' => $_SESSION['flash_type'] ?? 'info'
    ];
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    return $flash;
}


function set_flash_message($message, $type = 'info')
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}
