<?php
    require_once "inc/init.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Files - Vhault</title>

    <!-- font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            font-family: 'Montserrat', Arial, sans-serif;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            background: #23272f;
            color: #fff;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .logo-section {
            margin-bottom: 3rem;
        }

        .logo-text {
            color: #ffc107;
            font-size: 3.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .logo-subtext {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .files-container {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #ffc107;
            border: none;
            color: #23272f;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: #fff;
        }

        .files-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .file-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-card:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .file-card.download {
            background: #ffc107;
        }

        .file-card.download:hover {
            background: #ffcd38;
        }

        .file-image {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin: 0 auto 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-image i {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .download .file-image i {
            color: #23272f;
        }

        .file-name {
            font-size: 0.9rem;
            color: #fff;
            margin-bottom: 0.2rem;
            font-weight: 500;
        }

        .file-size {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .file-date {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .download .file-name,
        .download .file-size,
        .download .file-date {
            color: #23272f;
        }

        .user-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #ffc107;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .user-section i {
            font-size: 1.4rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .logo-text {
                font-size: 2.5rem;
            }

            .logo-subtext {
                font-size: 1rem;
            }

            .files-grid {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div class="logo-text">VHAULT</div>
            <div class="logo-subtext">Haul your files. Vault your world.</div>
        </div>

        <div class="files-container">
            <button class="close-btn">×</button>
            <div class="files-grid">
                <div class="file-card">
                    <div class="file-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="file-name">Filename1.png</div>
                    <div class="file-size">4.6mb</div>
                    <div class="file-date">Feb 6, 2025</div>
                </div>

                <div class="file-card download">
                    <div class="file-image">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="file-name">Download</div>
                </div>

                <div class="file-card">
                    <div class="file-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="file-name">Filename1.png</div>
                    <div class="file-size">4.6mb</div>
                    <div class="file-date">Feb 6, 2025</div>
                </div>

                <div class="file-card">
                    <div class="file-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="file-name">Filename1.png</div>
                    <div class="file-size">4.6mb</div>
                    <div class="file-date">Feb 6, 2025</div>
                </div>

                <div class="file-card">
                    <div class="file-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="file-name">Filename1.png</div>
                    <div class="file-size">4.6mb</div>
                    <div class="file-date">Feb 6, 2025</div>
                </div>

                <div class="file-card">
                    <div class="file-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="file-name">Filename1.png</div>
                    <div class="file-size">4.6mb</div>
                    <div class="file-date">Feb 6, 2025</div>
                </div>
            </div>
        </div>

        <div class="user-section">
            <i class="fas fa-user-circle"></i>
            <span>fwancis9</span>
        </div>
    </div>

    <script>
        // Handle close button
        document.querySelector('.close-btn').addEventListener('click', function() {
            // Here you would typically handle the close action
            console.log('Close clicked');
        });

        // Handle download button
        document.querySelector('.file-card.download').addEventListener('click', function() {
            // Here you would typically handle the download action
            console.log('Download clicked');
        });
    </script>
</body>
</html> 