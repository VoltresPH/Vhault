document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('loginPassword');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    let passwordVisible = false;
    
    togglePassword.addEventListener('click', function() {
        passwordVisible = !passwordVisible;
        passwordInput.type = passwordVisible ? 'text' : 'password';
        if(passwordVisible) {
            eyeIcon.innerHTML = '<circle cx="12" cy="12" r="3.5" stroke="#fff" stroke-width="2"/><path stroke="#fff" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><line x1="6" y1="18" x2="18" y2="6" stroke="#fff" stroke-width="2"/>';
        } else {
            eyeIcon.innerHTML = '<path stroke="#fff" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#fff" stroke-width="2"/>';
        }
    });
});