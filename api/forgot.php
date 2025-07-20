<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and new password are required.']);
    exit;
}

require_once '../include/db_connect.php';

$stmt = $db->prepare('SELECT id FROM users WHERE email = :email');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Email not found.']);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$update = $db->prepare('UPDATE users SET password = :password WHERE email = :email');
if ($update->execute([':password' => $hashed_password, ':email' => $email])) {
    echo json_encode(['success' => true, 'message' => 'Password has been reset. You can now log in.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to reset password.']);
} 