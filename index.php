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
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #FFC20E;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .drop-zone:hover {
            background: rgba(34,40,49,0.8);
            border-color: #FFE066;
            color: #FFE066;
        }
        .drop-zone i {
            font-size: 3rem;
            margin-bottom: 1rem;
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
            position: relative;
        }
        .user-section:hover {
            color: #FFE066;
        }
        .user-section:hover .username,
        .user-section:hover .user-icon {
            display: none;
        }
        .user-section:hover .logout-text {
            display: inline;
        }
        .logout-text {
            display: none;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        .user-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        .logout-btn {
            background: none;
            border: none;
            color: #FFC20E;
            cursor: pointer;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            transition: color 0.2s;
            margin-left: 1rem;
        }
        .logout-btn:hover {
            color: #FFE066;
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

    <script>
    // Drag and drop functionality
    const dropZone = document.querySelector('.drop-zone');
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = 'rgba(34,40,49,0.8)';
        dropZone.style.borderColor = '#FFE066';
    });
    
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = 'rgba(34,40,49,0.95)';
        dropZone.style.borderColor = '#FFC20E';
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = 'rgba(34,40,49,0.95)';
        dropZone.style.borderColor = '#FFC20E';
        const files = e.dataTransfer.files;
        handleFiles(files);
    });
    
    dropZone.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.multiple = true;
        input.onchange = (e) => {
            handleFiles(e.target.files);
        };
        input.click();
    });
    
    function handleFiles(files) {
        // TODO: Implement file upload functionality
        console.log('Files to upload:', files);
        // You can implement the uploadFile() function from functions.php here
    }

    document.querySelector('.user-section').addEventListener('click', function() {
        window.location.href = 'logout.php';
    });
    </script>
</body>
</html>