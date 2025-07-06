document.addEventListener('DOMContentLoaded', function() {
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
});