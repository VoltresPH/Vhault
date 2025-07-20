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
$filename = uniqid() . '_' . basename($file['name']);
$filepath = $uploadDir . $filename;
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Save to DB
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