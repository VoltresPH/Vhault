<?php

require_once 'init.php';

function loginDB($username, $password) {
    global $conn;
    // check for account in mysql
    $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    if ($result->num_rows > 0) {
        if ($result->fetch_assoc()['password'] == $password && $result->fetch_assoc()['username'] == $username) {
            // success, save user id to session
            $_SESSION['user_id'] = $result->fetch_assoc()['id'];
            header('Location: /');
            return;
        }
    }

    // redirect back to login with result false
    header('Location: login.php?result=false');
}

function createAccountDB($username, $password) {
    global $conn;
    $exists = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($exists->num_rows == 0) {
        $result = $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($result) {
            $new_user_id = $conn->insert_id;
            // success, save user id to session
            $_SESSION['user_id'] = $new_user_id;
            header('Location: /');
            return;
        }
    }

    // redirect back to signup with result false
    header('Location: signup.php?result=false');
}

function uploadFile() {
    // function for handling file upload
    // true if success
    return false;
}