document.addEventListener('DOMContentLoaded', function() {
    // Drag and drop functionality
    const dropZone = document.querySelector('.drop-zone');
    const uploadForm = document.querySelector('.upload-form');
    const fileInput = document.getElementById('initialFileInput');
        
    if (dropZone) {
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
            if (files.length > 0) {
                fileInput.files = files;
                uploadForm.submit();
            }
        });
    }

    // Handle file input change
    if (fileInput) {
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                uploadForm.submit();
            }
        });
    }

    // Handle logout
    const userSection = document.querySelector('.user-section');
    if (userSection) {
        const logoutLink = userSection.querySelector('.logout-text');
        if (logoutLink) {
            // Remove the click event from the entire user section
            userSection.style.cursor = 'default';
            userSection.style.pointerEvents = 'auto';
        }
    }
});