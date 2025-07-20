<?php
// Database configuration
$host = 'localhost';
$dbname = 'vhault_db';
$user = 'root';
$pass = '';

// Create connection
try {
    $db = new PDO("mysql:host=$host", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $db->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $db->exec("USE `$dbname`");

    // Create users table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    // Create file_uploads table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS file_uploads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        filename VARCHAR(255) NOT NULL,
        filepath VARCHAR(255) NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
// $db is now available for use in other scripts
