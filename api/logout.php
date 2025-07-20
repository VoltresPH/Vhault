<?php
session_start();
session_unset();
session_destroy();

if (isset($_SERVER['HTTP_REFERER']) && !isset($_POST['ajax'])) {
    header('Location: ../auth.php');
    exit();
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} 