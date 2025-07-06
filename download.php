<?php
    require_once "inc/init.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Files - Vhault</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/global.css">
    <link rel="stylesheet" href="assets/download.css">
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

    <!-- custom js -->
    <script src="assets/download.js"></script>
</body>
</html> 