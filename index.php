<?php
    require_once "inc/init.php";
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vhault - Haul your files. Vault your world.</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/global.css">
    <link rel="stylesheet" href="assets/index.css">
</head>

<body class="bg">
    <div class="content-wrapper">
        <div class="d-flex flex-column align-items-center">
            <h1 class="logo mt-4">VHAULT</h1>
            <p class="tagline">Haul your files. Vault your world.</p>
        </div>
        <div class="drop-zone">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag and drop files here or click to upload</p>
        </div>
        <div class="d-flex justify-content-center">
            <div class="user-section position-static">
                <div class="user-content">
                    <i class="fas fa-user user-icon"></i>
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
                <span class="logout-text">Log out</span>
            </div>
        </div>
    </div>

    <!-- custom js -->
    <script src="assets/index.js"></script>
</body>

</html>