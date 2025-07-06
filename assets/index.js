document.addEventListener('DOMContentLoaded', function() {
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
});