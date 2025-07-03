<?php

// database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "Voltres";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a simple users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS vhault_auth (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}
