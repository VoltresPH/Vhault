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
$stmt = $db->prepare('SELECT id, password FROM users WHERE email = :email');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(['success' => true, 'message' => 'Login successful.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}
