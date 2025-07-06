document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('fileInput');
    const uploadForm = document.querySelector('.upload-form');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    uploadArea.addEventListener('drop', handleDrop, false);

    // Handle file input change
    fileInput.addEventListener('change', handleFiles, false);

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadArea.classList.add('highlight');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('highlight');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            uploadForm.submit();
        }
    }

    function handleFiles(e) {
        const files = e.target.files;
        if (files.length > 0) {
            uploadForm.submit();
        }
    }

    // Handle user profile hover effect
    const userProfile = document.querySelector('.user-profile');
    const logoutText = userProfile.querySelector('.logout-text');
    const username = userProfile.querySelector('span');
    const userIcon = userProfile.querySelector('i');

    userProfile.addEventListener('mouseenter', () => {
        username.style.opacity = '0';
        userIcon.style.opacity = '0';
        logoutText.style.opacity = '1';
    });

    userProfile.addEventListener('mouseleave', () => {
        username.style.opacity = '1';
        userIcon.style.opacity = '1';
        logoutText.style.opacity = '0';
    });
});