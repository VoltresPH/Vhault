document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('slideToLogin').addEventListener('click', function(e) {
        e.preventDefault();
        var overlay = document.getElementById('slideOverlay');
        overlay.classList.add('active');
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 700);
    });
});