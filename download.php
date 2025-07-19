<?php
    require_once "inc/init.php";

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $success_message = '';
    $error_message = '';

    // Handle file deletion
    if (isset($_POST['delete']) && isset($_POST['file_id'])) {
        $result = deleteFile($_SESSION['user_id'], $_POST['file_id']);
        if ($result['success']) {
            $success_message = $result['message'];
        } else {
            $error_message = $result['message'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Files - Vhault</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/global.css">
    <link rel="stylesheet" href="assets/download.css">
</head>
<body class="bg">
    <div class="content-wrapper">
        <div class="logo-section">
            <h1 class="logo">VHAULT</h1>
            <p class="tagline">Haul your files. Vault your world.</p>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="files-container">
            <a href="index.php" class="close-btn" title="Back to Upload">×</a>
            <div class="files-grid">
                <?php foreach ($files as $file): ?>
                <div class="file-card">
                    <div class="file-preview">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="file-name" title="<?php echo htmlspecialchars($file['filename']); ?>">
                        <?php echo htmlspecialchars($file['filename']); ?>
                    </div>
                    <div class="file-size"><?php echo formatFileSize($file['filesize']); ?></div>
                    <div class="file-date"><?php echo date('M j, Y', strtotime($file['upload_date'])); ?></div>
                    <div class="file-actions">
                        <a href="?file=<?php echo $file['id']; ?>" class="action-btn download-btn" title="Download file">
                            <i class="bi bi-download"></i>
                        </a>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this file?');">
                            <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                            <button type="submit" name="delete" class="action-btn delete-btn" title="Delete file">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if (empty($files)): ?>
                <div class="no-files">
                    <i class="bi bi-folder2-open"></i>
                    <p>No files found</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="user-section">
            <i class="bi bi-person-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <!-- custom js -->
    <script src="assets/download.js"></script>

    <?php if ($success_message || $error_message): ?>
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