<?php

require_once 'init.php';

function loginDB($username, $password) {
    global $conn;
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM vhault_auth WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password hash
        if (password_verify($password, $user['password'])) {
            // Success, save user data to session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            $stmt->close();
            header('Location: index.php');
            exit();
        } else {
            // Wrong password
            $stmt->close();
            header('Location: login.php?error=wrong_password');
            exit();
        }
    }
    
    $stmt->close();
    // Username not found
    header('Location: login.php?error=wrong_username');
    exit();
}

function createAccountDB($username, $password) {
    global $conn;
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id FROM vhault_auth WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param('s', $username);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        // Username already exists
        header('Location: signup.php?result=exists');
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO vhault_auth (username, password) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param('ss', $username, $hashed_password);
    
    if ($stmt->execute()) {
        $new_user_id = $stmt->insert_id;
        // Success, save user data to session
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['username'] = $username;
        
        $stmt->close();
        header('Location: index.php');
        exit();
    } else {
        die("Insert failed: " . $stmt->error);
    }
    
    $stmt->close();
    // Registration failed
    header('Location: signup.php?result=false');
    exit();
}

function uploadFile() {
    // function for handling file upload
    // true if success
    return false;
}