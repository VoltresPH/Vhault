<?php
    require_once "inc/init.php";
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    // Handle file upload
    $upload_error = '';
    $upload_success = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
        $result = uploadFile($_SESSION['user_id'], $_FILES['file']);
        if ($result['success']) {
            $upload_success = 'File uploaded successfully!';
        } else {
            $upload_error = 'Failed to upload file. Please try again.';
        }
    }

    // Get user's files
    $files = getUserFiles($_SESSION['user_id']);
    $has_files = !empty($files);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vhault - Haul your files. Vault your world.</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/global.css">
    <?php if ($has_files): ?>
    <link rel="stylesheet" href="assets/upload.css">
    <?php else: ?>
    <link rel="stylesheet" href="assets/index.css">
    <?php endif; ?>
</head>

<body class="bg">
    <?php if ($has_files): ?>
        <!-- Upload Interface -->
        <nav class="navbar">
            <div class="container-fluid">
                <div class="brand-section">
                    <a class="navbar-brand" href="/">VHAULT</a>
                    <p class="tagline">Haul your files. Vault your world.</p>
                </div>
                <div class="user-profile">
                    <i class="bi bi-person-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="logout-text">Logout</a>
                </div>
            </div>
        </nav>

        <?php if ($upload_error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($upload_error); ?></div>
        <?php endif; ?>
        <?php if ($upload_success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($upload_success); ?></div>
        <?php endif; ?>

        <div class="main-content">
            <div class="sidebar">
                <div class="recent-files">
                    <h2>
                        <a href="download.php" class="folder-link" title="View all files">
                            <i class="bi bi-folder2-open"></i>
                        </a>
                        Recent Files
                    </h2>
                    <div class="file-list">
                        <?php foreach ($files as $file): ?>
                        <div class="file-item">
                            <div class="file-name" title="<?php echo htmlspecialchars($file['filename']); ?>">
                                <?php echo htmlspecialchars($file['filename']); ?>
                            </div>
                            <button class="download-btn" onclick="window.location.href='download.php?file=<?php echo $file['id']; ?>'">
                                <i class="bi bi-download"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="main-area">
                <form method="post" enctype="multipart/form-data" class="upload-form">
                    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                        <input type="file" name="file" id="fileInput" style="display: none;">
                        <i class="bi bi-cloud-upload"></i>
                        <div class="upload-text">Drop your files here</div>
                        <div class="upload-subtext">or click to browse</div>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- Initial Interface -->
        <div class="content-wrapper">
            <div>
                <h1 class="logo">VHAULT</h1>
                <p class="tagline">Haul your files. Vault your world.</p>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="drop-zone" onclick="document.getElementById('initialFileInput').click()">
                    <input type="file" name="file" id="initialFileInput" style="display: none;">
                    <i class="bi bi-cloud-upload"></i>
                    <p>Drag and drop files here or click to upload</p>
                </div>
            </form>
            <div class="user-section">
                <div class="user-content">
                    <i class="bi bi-person user-icon"></i>
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
                <a href="logout.php" class="logout-text">Log out</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- custom js -->
    <script src="assets/index.js"></script>
    <?php if ($has_files): ?>
    <script src="assets/upload.js"></script>
    <?php endif; ?>

    <?php if ($upload_success || $upload_error): ?>
    <script>
        // Auto-hide alerts after 3 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 3000);
    </script>
    <?php endif; ?>
</body>
</html>