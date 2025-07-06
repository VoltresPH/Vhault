<?php

// database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "Voltres";

// Create connection without database first
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$conn->query($sql)) {
    error_log("Error creating database: " . $conn->error);
    die("Error creating database: " . $conn->error);
}

// Close the connection and reconnect with the database selected
$conn->close();
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// create a vhault_auth table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS vhault_auth (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if (!$conn->query($sql)) {
    error_log("Error creating table: " . $conn->error);
    die("Error creating table: " . $conn->error);
}

// create a vhault_files table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS vhault_files (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES vhault_auth(id) ON DELETE CASCADE
)";

if (!$conn->query($sql)) {
    error_log("Error creating table: " . $conn->error);
    die("Error creating table: " . $conn->error);
}

// Set charset to ensure proper handling of special characters
$conn->set_charset("utf8mb4");