<?php

require_once 'init.php';

function loginDB($username, $password) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, username, password FROM vhault_auth WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows !== 1) {
            $stmt->close();
            header('Location: login.php?error=wrong_username');
            exit();
        }
        
        $user = $result->fetch_assoc();
        $stmt->close();
        
        // Verify the password hash
        if (!password_verify($password, $user['password'])) {
            header('Location: login.php?error=wrong_password');
            exit();
        }
        
        // Success - save session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: login.php?error=system');
        exit();
    }
}

function createAccountDB($username, $password) {
    global $conn;
    
    try {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM vhault_auth WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            header('Location: signup.php?result=exists');
            exit();
        }
        $stmt->close();
        
        // Create new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO vhault_auth (username, password) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('ss', $username, $hashed_password);
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }
        
        $stmt->close();
        
        header('Location: login.php?success=registration');
        exit();
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: signup.php?result=false');
        exit();
    }
}

function getUserFiles($user_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, filename, filepath, upload_date FROM vhault_files WHERE user_id = ? ORDER BY upload_date DESC");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $files = [];
        while ($row = $result->fetch_assoc()) {
            $files[] = $row;
        }
        
        $stmt->close();
        return $files;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

function uploadFile($user_id, $file) {
    global $conn;
    
    try {
        // Create uploads directory if it doesn't exist
        $upload_dir = 'uploads/' . $user_id;
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $filename = $file['name'];
        $filepath = $upload_dir . '/' . uniqid() . '_' . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception("Failed to move uploaded file.");
        }
        
        // Save file info to database
        $stmt = $conn->prepare("INSERT INTO vhault_files (user_id, filename, filepath) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('iss', $user_id, $filename, $filepath);
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }
        
        $stmt->close();
        return ['success' => true];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function resetPasswordDB($username, $new_password) {
    global $conn;
    
    try {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM vhault_auth WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows !== 1) {
            $stmt->close();
            return ['success' => false, 'error' => 'username_not_found'];
        }
        $stmt->close();
        
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE vhault_auth SET password = ? WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('ss', $hashed_password, $username);
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }
        
        $stmt->close();
        return ['success' => true];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'error' => 'system'];
    }
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . 'GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . 'MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . 'KB';
    } else {
        return $bytes . 'B';
    }
}

function deleteFile($user_id, $file_id) {
    global $conn;
    
    try {
        // First get the file info to delete the actual file
        $stmt = $conn->prepare("SELECT filepath FROM vhault_files WHERE id = ? AND user_id = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('ii', $file_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows !== 1) {
            return ['success' => false, 'message' => 'File not found'];
        }
        
        $file = $result->fetch_assoc();
        $stmt->close();
        
        // Delete the file from storage
        if (file_exists($file['filepath'])) {
            unlink($file['filepath']);
        }
        
        // Delete the database record
        $stmt = $conn->prepare("DELETE FROM vhault_files WHERE id = ? AND user_id = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('ii', $file_id, $user_id);
        $stmt->execute();
        $stmt->close();
        
        return ['success' => true, 'message' => 'File deleted successfully'];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'Failed to delete file'];
    }
}

// Handle file download if file ID is provided
if (isset($_GET['file'])) {
    // Get file information
    $file_id = $_GET['file'];
    try {
        $stmt = $conn->prepare("SELECT filepath, filename FROM vhault_files WHERE id = ? AND user_id = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param('ii', $file_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows !== 1) {
            header('Location: index.php');
            exit();
        }
        
        $file = $result->fetch_assoc();
        $stmt->close();
        
        // Check if file exists
        if (!file_exists($file['filepath'])) {
            header('Location: index.php');
            exit();
        }
        
        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file['filename'] . '"');
        header('Content-Length: ' . filesize($file['filepath']));
        
        // Output file content
        readfile($file['filepath']);
        exit();
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: index.php');
        exit();
    }
}

// Get all files for the user
try {
    $stmt = $conn->prepare("SELECT id, filename, filepath, upload_date, filesize FROM vhault_files WHERE user_id = ? ORDER BY upload_date DESC");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $files = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    $files = [];
}