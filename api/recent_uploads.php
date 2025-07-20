<?php
header('Content-Type: application/json');
require_once '../include/db_connect.php';

$baseUrl = dirname($_SERVER['SCRIPT_NAME'], 2) . '/uploads';

$files = [];

try {
    $stmt = $db->query('SELECT filename, filepath FROM file_uploads ORDER BY uploaded_at DESC LIMIT 10');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $files[] = [
            'name' => $row['filename'],
            'url' => $baseUrl . '/' . $row['filepath']
        ];
    }
} catch (Exception $e) {}

echo json_encode(['files' => $files]); 