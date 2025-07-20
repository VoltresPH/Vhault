<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

require_once '../include/db_connect.php';

$stmt = $db->prepare('SELECT id FROM users WHERE email = :email');
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $db->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
if ($stmt->execute([':email' => $email, ':password' => $hashed_password])) {
    session_start();
    $_SESSION['user_id'] = $db->lastInsertId();
    echo json_encode(['success' => true, 'message' => 'Account created and logged in.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create account.']);
}
