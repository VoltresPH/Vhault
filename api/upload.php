<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    exit;
}

require_once '../include/db_connect.php';
$user_id = $_SESSION['user_id'];
$file = $_FILES['file'];
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!is_writable($uploadDir)) {
    chmod($uploadDir, 0777);
    if (!is_writable($uploadDir)) {
        echo json_encode(['success' => false, 'message' => 'Upload directory is not writable. Please check permissions.']);
        exit;
    }
}
$filename = uniqid() . '_' . basename($file['name']);
$filepath = $uploadDir . $filename;
if ($file['error'] !== UPLOAD_ERR_OK) {
    $error_messages = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
    ];
    $error_msg = isset($error_messages[$file['error']]) ? $error_messages[$file['error']] : 'Unknown upload error';
    echo json_encode(['success' => false, 'message' => $error_msg]);
    exit;
}

if (move_uploaded_file($file['tmp_name'], $filepath)) {
    $stmt = $db->prepare('INSERT INTO file_uploads (user_id, filename, filepath) VALUES (:user_id, :filename, :filepath)');
    $stmt->execute([
        ':user_id' => $user_id,
        ':filename' => $file['name'],
        ':filepath' => $filename
    ]);
    echo json_encode(['success' => true, 'message' => 'File uploaded successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
}