<?php
    require_once "inc/inc.php";
    session_start();
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vhault</title>
</head>
<body>
    
</body>
</html>