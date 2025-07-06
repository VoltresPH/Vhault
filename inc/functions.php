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
        
        // Success - save session and redirect
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $stmt->close();
        
        header('Location: index.php');
        exit();
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: signup.php?result=false');
        exit();
    }
}

function uploadFile() {
    // function for handling file upload
    // true if success
    return false;
}