<?php
header('Content-Type: application/json');
session_start();
require_once '../include/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['email' => null]);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare('SELECT email FROM users WHERE id = :id');
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo json_encode(['email' => $user['email']]);
} else {
    echo json_encode(['email' => null]);
} 