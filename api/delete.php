<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit();
}

// Start session and check authentication
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

require_once '../include/db_connect.php';

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || (!isset($input['filename']) && !isset($input['id']))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'File ID or filename is required'
    ]);
    exit();
}

$uploadsDir = '../uploads';

try {
    // If ID is provided, use database lookup (only for current user)
    if (isset($input['id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare('SELECT filepath FROM file_uploads WHERE id = ? AND user_id = ?');
        $stmt->execute([$input['id'], $user_id]);
        $fileRecord = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$fileRecord) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'File record not found'
            ]);
            exit();
        }
        
        $storedFilename = $fileRecord['filepath'];
        $fileId = $input['id'];
    } else {
        // Fallback: treat filename as stored filename (for backward compatibility)
        $storedFilename = $input['filename'];
        $fileId = null;
        
        // Try to find the database record by filepath (only for current user)
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare('SELECT id FROM file_uploads WHERE filepath = ? AND user_id = ?');
        $stmt->execute([$storedFilename, $user_id]);
        $fileRecord = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fileRecord) {
            $fileId = $fileRecord['id'];
        }
    }
    
    // Validate stored filename (prevent directory traversal)
    if (strpos($storedFilename, '..') !== false || strpos($storedFilename, '/') !== false || strpos($storedFilename, '\\') !== false) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid filename'
        ]);
        exit();
    }
    
    $filePath = $uploadsDir . '/' . $storedFilename;
    
    // Check if file exists on filesystem
    if (!file_exists($filePath)) {
        // File doesn't exist on filesystem, but might exist in database - clean up database record
        if ($fileId) {
            $stmt = $db->prepare('DELETE FROM file_uploads WHERE id = ?');
            $stmt->execute([$fileId]);
        }
        
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'File not found on filesystem'
        ]);
        exit();
    }
    
    // Check if it's actually a file (not a directory)
    if (!is_file($filePath)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid file'
        ]);
        exit();
    }
    
    // Attempt to delete the file from filesystem
    if (unlink($filePath)) {
        // Also delete from database if we have the record
        if ($fileId) {
            $stmt = $db->prepare('DELETE FROM file_uploads WHERE id = ?');
            $stmt->execute([$fileId]);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete file from filesystem'
        ]);
    }
    
} catch (PDOException $e) {
    error_log('Database error in delete.php: ' . $e->getMessage());
    
    // Fallback to filesystem-only deletion if database fails
    $filename = $input['filename'] ?? $input['id'];
    
    // Validate filename (prevent directory traversal)
    if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid filename'
        ]);
        exit();
    }
    
    $filePath = $uploadsDir . '/' . $filename;
    
    if (file_exists($filePath) && is_file($filePath)) {
        if (unlink($filePath)) {
            echo json_encode([
                'success' => true,
                'message' => 'File deleted successfully (filesystem only)'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete file'
            ]);
        }
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'File not found'
        ]);
    }
}
?>
