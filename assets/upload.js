document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const files = e.target.files;
        // Here you would typically handle the file upload
        console.log('Files selected:', files);
    });

    // Handle drag and drop
    const uploadArea = document.querySelector('.upload-area');

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'rgba(255, 193, 7, 0.15)';
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'rgba(255, 193, 7, 0.05)';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.background = 'rgba(255, 193, 7, 0.05)';
        const files = e.dataTransfer.files;
        console.log('Files dropped:', files);
    });
});