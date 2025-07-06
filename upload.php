<?php
    require_once "inc/init.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files - Vhault</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/global.css">
    <link rel="stylesheet" href="assets/upload.css">
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">VHAULT</a>
            <div class="user-profile">
                <i class="fas fa-user-circle"></i>
                <span>fwancis9</span>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="recent-files">
            <h2>
                <i class="fas fa-folder-open"></i>
                Recent Files
            </h2>
            <div class="file-list">
                <div class="file-item">
                    <div class="file-name">Filename1.png</div>
                    <button class="download-btn">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div class="file-item">
                    <div class="file-name">Filename1.png</div>
                    <button class="download-btn">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <div class="file-item">
                    <div class="file-name">Filename1.png</div>
                    <button class="download-btn">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="upload-area" onclick="document.getElementById('fileInput').click()">
            <input type="file" id="fileInput" style="display: none;" multiple>
            <i class="fas fa-cloud-upload-alt"></i>
            <div class="upload-text">Drop your files here</div>
            <div class="upload-subtext">or click to browse</div>
        </div>
    </div>

    <!-- custom js -->
    <script src="assets/upload.js"></script>
</body>
</html>