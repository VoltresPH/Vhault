<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session and check authentication
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

require_once '../include/db_connect.php';

$uploadsDir = '../uploads';
$files = [];

try {
    // Get files from database for current user only
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare('SELECT id, filename, filepath, uploaded_at FROM file_uploads WHERE user_id = :user_id ORDER BY uploaded_at DESC');
    $stmt->execute([':user_id' => $user_id]);
    $dbFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($dbFiles as $dbFile) {
        $filePath = $uploadsDir . '/' . $dbFile['filepath'];
        
        // Check if file still exists on filesystem
        if (is_file($filePath)) {
            $fileSize = filesize($filePath);
            $mimeType = mime_content_type($filePath);
            
            $files[] = [
                'id' => (string)$dbFile['id'],
                'name' => $dbFile['filename'], // Original filename from database
                'size' => $fileSize,
                'upload_date' => date('c', strtotime($dbFile['uploaded_at'])), // ISO 8601 format
                'uploaded_at' => date('c', strtotime($dbFile['uploaded_at'])), // Keep for backward compatibility
                'type' => $mimeType ?: 'application/octet-stream',
                'url' => 'uploads/' . $dbFile['filepath'], // Use stored filepath for actual file access
                'stored_filename' => $dbFile['filepath'] // Keep stored filename for operations
            ];
        }
    }
    
} catch (PDOException $e) {
    // Fallback to filesystem-based listing if database fails
    error_log('Database error in files.php: ' . $e->getMessage());
    
    if (is_dir($uploadsDir)) {
        $fileList = scandir($uploadsDir);
        $id = 1;
        
        foreach ($fileList as $filename) {
            if ($filename === '.' || $filename === '..' || $filename === '.gitkeep') {
                continue;
            }
            
            $filePath = $uploadsDir . '/' . $filename;
            
            if (is_file($filePath)) {
                $fileSize = filesize($filePath);
                $uploadTime = filemtime($filePath);
                $mimeType = mime_content_type($filePath);
                
                $files[] = [
                    'id' => (string)$id,
                    'name' => $filename, // Fallback to stored filename
                    'size' => $fileSize,
                    'upload_date' => date('c', $uploadTime),
                    'uploaded_at' => date('c', $uploadTime),
                    'type' => $mimeType ?: 'application/octet-stream',
                    'url' => 'uploads/' . $filename,
                    'stored_filename' => $filename
                ];
                
                $id++;
            }
        }
        
        // Sort files by upload time (newest first)
        usort($files, function($a, $b) {
            return strtotime($b['uploaded_at']) - strtotime($a['uploaded_at']);
        });
    }
}

// Return the files as JSON
echo json_encode([
    'success' => true,
    'files' => $files
]);
?>
