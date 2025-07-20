<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vhault</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/file-explorer.css">
</head>
<body>
<header class="dashboard-header">
    <div class="header-content">
        <div class="logo">VHAULT</div>
        <div class="tagline">Haul your files. Vault your world.</div>
        <div id="profile-info" class="profile-info">
            <svg class="profile-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span id="profile-email" class="profile-email"></span>
        </div>
    </div>
</header>
<div class="main-flex-container">
    <div class="recent-files-column">
        <div class="recent-header-row">
            <h3>Recent Uploads</h3>
            <button id="file-manager-btn" class="file-manager-btn" aria-label="Open File Manager">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </button>
        </div>
        <ul id="recent-files-list" class="recent-files-list">
        </ul>
    </div>
    <div class="vertical-separator"></div>
    <div class="upload-container">
        <div id="drop-area" class="drop-area">
            <div class="upload-form">
                <p>Drag & drop files here, or <label for="fileElem" class="file-label">browse files</label>.</p>
                <input type="file" id="fileElem" class="file-input">
            </div>
            <div id="upload-status" class="upload-status"></div>
            <button id="upload-more-btn" class="upload-more-btn hidden">Upload More</button>
        </div>
    </div>
</div>
<script>
window.addEventListener('DOMContentLoaded', function() {
    const profileInfo = document.getElementById('profile-info');
    const profileEmail = document.getElementById('profile-email');
    let originalEmail = '';
    
    fetch('api/get_profile.php')
        .then(response => response.json())
        .then(data => {
            if (data && data.email && profileEmail) {
                originalEmail = data.email;
                profileEmail.textContent = originalEmail;
                
                setTimeout(() => {
                    if (profileInfo) {
                        profileInfo.style.minWidth = profileInfo.offsetWidth + 'px';
                    }
                }, 100);
                
                if (profileInfo) {
                    profileInfo.style.cursor = 'pointer';
                    
                    profileInfo.onmouseenter = function() {
                        profileEmail.textContent = 'Logout?';
                    };
                    
                    profileInfo.onmouseleave = function() {
                        profileEmail.textContent = originalEmail;
                    };
                    
                    profileInfo.onclick = function() {
                        if (profileEmail.textContent === 'Logout?') {
                            window.location.href = 'api/logout.php';
                        }
                    };
                }
            } else {
                if (profileEmail) {
                    profileEmail.textContent = 'Not logged in';
                }
            }
        })
        .catch(error => {
            console.error('Error loading profile:', error);
            if (profileEmail) {
                profileEmail.textContent = 'User';
            }
        });
});
</script>
<div id="file-explorer-modal" class="file-explorer-modal" aria-hidden="true">
    <div class="file-explorer-content">
        <div class="file-explorer-header">
            <div class="file-explorer-title">
                <h2>Your Files</h2>
                <p>Manage and view your uploaded files</p>
            </div>
            <button id="file-explorer-close-btn" class="close-modal" aria-label="Close file explorer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="file-explorer-toolbar">
            <div class="search-container">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" id="file-search" placeholder="Search files..." class="search-input">
            </div>

        </div>
        
        <div class="file-explorer-body" id="file-explorer-body">
            <div class="loading-container">
                <div class="spinner"></div>
                <p>Loading files...</p>
            </div>
        </div>

    </div>
</div>
<script src="assets/js/file-explorer.js"></script>
<script src="assets/js/file-upload.js"></script>
</body>
</html>