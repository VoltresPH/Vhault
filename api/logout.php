<?php
session_start();
session_unset();
session_destroy();

// Check if this is a direct redirect request
if (isset($_SERVER['HTTP_REFERER']) && !isset($_POST['ajax'])) {
    // Direct redirect for simple logout
    header('Location: ../auth.php');
    exit();
} else {
    // JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} 