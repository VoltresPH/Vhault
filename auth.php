<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vhault - Authentication</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
    <div class="logo-container">
        <div class="logo">VHAULT</div>
        <div class="tagline">Haul your files. Vault your world.</div>
    </div>
    <div class="auth-container" id="container">
    <div class="form-container sign-up-container">
        <form id="signupForm" class="auth-form" method="POST" action="#">
            <h1>Create Account</h1>
            <div id="signup-message" class="auth-message"></div>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form id="loginForm" class="auth-form" method="POST" action="#">
            <h1>Sign in</h1>
            <div id="login-message" class="auth-message"></div>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <a href="#" id="showForgot" class="auth-link">Forgot your password?</a>
            <button type="submit">Sign In</button>
        </form>
        <form id="forgotForm" class="auth-form hidden" method="POST" action="#">
            <h1>Reset Password</h1>
            <div id="forgot-message" class="auth-message"></div>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="New Password" required />
            <a href="#" id="showLogin" class="auth-link">Back to login</a>
            <button type="submit">Reset Password</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Hello!</h1>
                <p>Already have an account? Log In Now.</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Welcome Back!</h1>
                <p>Not a member? Register Now.</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/auth.js"></script>
</body>
</html>