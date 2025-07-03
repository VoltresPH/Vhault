<?php
    // require_once "inc/inc.php";
    // session_start();
    // if (!isset($_SESSION['user_id'])) {
        // header('Location: login.php');
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vhault - Haul your files. Vault your world.</title>

    <!-- font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
    </style>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .bg {
            background: #222831;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
        }
        .logo {
            color: #FFC20E;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 2px;
        }
        .tagline {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 3rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
        }
        .drop-zone {
            border: 2px dashed #FFC20E;
            border-radius: 20px;
            padding: 8rem;
            background: rgba(34,40,49,0.95);
            width: 80%;
            max-width: 800px;
            flex: 1;
            margin: 4rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .user-section {
            color: #FFC20E;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            font-size: 1.2rem;
            margin: 2rem 0;
            cursor: pointer;
            transition: color 0.2s;
        }
        .user-section:hover {
            color: #FFE066;
            transform: scale(1.05);
            transition: color 0.2s, transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .user-icon {
            font-size: 1.2rem;
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            min-height: 100vh;
            padding: 2rem 0;
        }
    </style>
</head>
<body class="bg">
    <div class="content-wrapper">
        <div class="d-flex flex-column align-items-center">
            <h1 class="logo mt-4">VHAULT</h1>
            <p class="tagline">Haul your files. Vault your world.</p>
        </div>
        <div class="drop-zone">
        </div>
        <div class="d-flex justify-content-center">
            <div class="user-section position-static">
                <i class="fas fa-user user-icon"></i>
                <span>fwancis9</span>
            </div>
        </div>
    </div>

    <script>
    document.querySelector('.user-section').addEventListener('click', function() {
        alert('User section clicked');
        // window.location.href = 'login.php';
    });
    </script>
</body>
</html>