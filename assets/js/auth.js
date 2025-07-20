// Authentication Module - Simple JavaScript for student project

// DOM Elements
let container, signUpButton, signInButton, signupForm, loginForm, forgotForm;
let showForgot, showLogin, signupMsgDiv, loginMsgDiv, forgotMsgDiv;

// Helper Functions
function showMessage(container, message, type = 'info') {
    if (!container) return;
    
    let messageElement = container.querySelector('.message');
    if (!messageElement) {
        messageElement = document.createElement('p');
        messageElement.className = 'message';
        container.appendChild(messageElement);
    }
    
    messageElement.textContent = message;
    messageElement.className = `message ${type}`;
    
    // Auto-hide success messages after 3 seconds
    if (type === 'success') {
        setTimeout(() => {
            if (messageElement.textContent === message) {
                messageElement.textContent = '';
            }
        }, 3000);
    }
}

function clearForm(form) {
    if (!form) return;
    
    const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.value = '';
    });
}


function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePassword(password) {
    if (password.length < 8) {
        return { isValid: false, message: 'Password must be at least 8 characters long' };
    }
    return { isValid: true, message: 'Password is strong' };
}

// Initialize DOM elements
function initElements() {
    container = document.getElementById('container');
    signUpButton = document.getElementById('signUp');
    signInButton = document.getElementById('signIn');
    signupForm = document.getElementById('signupForm');
    loginForm = document.getElementById('loginForm');
    forgotForm = document.getElementById('forgotForm');
    showForgot = document.getElementById('showForgot');
    showLogin = document.getElementById('showLogin');
    signupMsgDiv = document.getElementById('signup-message');
    loginMsgDiv = document.getElementById('login-message');
    forgotMsgDiv = document.getElementById('forgot-message');
}

// Bind all event listeners
function bindEvents() {
    // Panel toggle events
    if (signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', showSignUp);
        signInButton.addEventListener('click', showSignIn);
    }
    
    // Form navigation events
    if (showForgot) {
        showForgot.addEventListener('click', showForgotPassword);
    }
    
    if (showLogin) {
        showLogin.addEventListener('click', showLoginForm);
    }
    
    // Form submission events
    if (signupForm) {
        signupForm.addEventListener('submit', handleSignup);
    }
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    if (forgotForm) {
        forgotForm.addEventListener('submit', handleForgotPassword);
    }
}

// Show signup panel
function showSignUp() {
    container.classList.add('right-panel-active');
    clearSignupFields();
}

// Show signin panel
function showSignIn() {
    container.classList.remove('right-panel-active');
    clearLoginFields();
    if (forgotForm) forgotForm.classList.add('hidden');
    if (loginForm) loginForm.classList.remove('hidden');
    clearForgotFields();
}

// Show forgot password form
function showForgotPassword(e) {
    e.preventDefault();
    if (loginForm) loginForm.classList.add('hidden');
    if (forgotForm) forgotForm.classList.remove('hidden');
    clearForgotFields();
    clearLoginFields();
}

// Show login form
function showLoginForm(e) {
    e.preventDefault();
    if (forgotForm) forgotForm.classList.add('hidden');
    if (loginForm) loginForm.classList.remove('hidden');
    clearForgotFields();
    clearLoginFields();
}

// Clear signup form fields
function clearSignupFields() {
    if (signupForm) {
        clearForm(signupForm);
    }
    if (signupMsgDiv) {
        showMessage(signupMsgDiv, '', 'info');
    }
}

// Clear login form fields
function clearLoginFields() {
    if (loginForm) {
        clearForm(loginForm);
    }
    if (loginMsgDiv) {
        showMessage(loginMsgDiv, '', 'info');
    }
}

// Clear forgot password form fields
function clearForgotFields() {
    if (forgotForm) {
        clearForm(forgotForm);
    }
    if (forgotMsgDiv) {
        showMessage(forgotMsgDiv, '', 'info');
    }
}

// Validate signup form
function validateSignupForm(email, password) {
    if (!email || !password) {
        return { isValid: false, message: 'Please fill in all fields' };
    }
    
    if (!isValidEmail(email)) {
        return { isValid: false, message: 'Please enter a valid email address' };
    }
    
    const passwordValidation = validatePassword(password);
    if (!passwordValidation.isValid) {
        return passwordValidation;
    }
    
    return { isValid: true };
}

// Validate login form
function validateLoginForm(email, password) {
    if (!email || !password) {
        return { isValid: false, message: 'Please fill in all fields' };
    }
    
    if (!isValidEmail(email)) {
        return { isValid: false, message: 'Please enter a valid email address' };
    }
    
    return { isValid: true };
}

// Handle signup form submission
async function handleSignup(e) {
    e.preventDefault();
    
    const email = signupForm.querySelector('input[name="email"]').value.trim();
    const password = signupForm.querySelector('input[name="password"]').value;
    
    // Validate form
    const validation = validateSignupForm(email, password);
    if (!validation.isValid) {
        showMessage(signupMsgDiv, validation.message, 'error');
        return;
    }
    
    try {
        const response = await fetch('api/create.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        
        if (data.success) {
            showMessage(signupMsgDiv, 'Account created successfully! Please log in.', 'success');
            setTimeout(() => showSignIn(), 2000);
        } else {
            showMessage(signupMsgDiv, data.message || 'Signup failed', 'error');
        }
    } catch (error) {
        showMessage(signupMsgDiv, 'An error occurred. Please try again.', 'error');
        console.error('Signup error:', error);
    }
}

// Handle login form submission
async function handleLogin(e) {
    e.preventDefault();
    
    const email = loginForm.querySelector('input[name="email"]').value.trim();
    const password = loginForm.querySelector('input[name="password"]').value;
    
    // Validate form
    const validation = validateLoginForm(email, password);
    if (!validation.isValid) {
        showMessage(loginMsgDiv, validation.message, 'error');
        return;
    }
    
    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        
        if (data.success) {
            showMessage(loginMsgDiv, 'Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else {
            showMessage(loginMsgDiv, data.message || 'Login failed', 'error');
        }
    } catch (error) {
        showMessage(loginMsgDiv, 'An error occurred. Please try again.', 'error');
        console.error('Login error:', error);
    }
}

// Handle forgot password form submission
async function handleForgotPassword(e) {
    e.preventDefault();
    
    const email = forgotForm.querySelector('input[name="email"]').value.trim();
    const password = forgotForm.querySelector('input[name="password"]').value;
    
    if (!email) {
        showMessage(forgotMsgDiv, 'Please enter your email address', 'error');
        return;
    }
    
    if (!password) {
        showMessage(forgotMsgDiv, 'Please enter your new password', 'error');
        return;
    }
    
    if (!isValidEmail(email)) {
        showMessage(forgotMsgDiv, 'Please enter a valid email address', 'error');
        return;
    }
    
    const passwordValidation = validatePassword(password);
    if (!passwordValidation.isValid) {
        showMessage(forgotMsgDiv, passwordValidation.message, 'error');
        return;
    }
    
    try {
        const response = await fetch('api/forgot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        
        if (data.success) {
            showMessage(forgotMsgDiv, 'Password has been reset successfully!', 'success');
            setTimeout(() => showLoginForm({ preventDefault: () => {} }), 2000);
        } else {
            showMessage(forgotMsgDiv, data.message || 'Failed to reset password', 'error');
        }
    } catch (error) {
        showMessage(forgotMsgDiv, 'An error occurred. Please try again.', 'error');
        console.error('Forgot password error:', error);
    }
}

// Initialize authentication when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initElements();
    bindEvents();
});
