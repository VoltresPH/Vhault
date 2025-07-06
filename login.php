<?php
    require_once "inc/init.php";

    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit();
    }

    $error = '';
    $success = '';
    $is_reset_mode = isset($_POST['reset_mode']) || (isset($_GET['mode']) && $_GET['mode'] === 'reset');

    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'wrong_password':
                $error = 'Incorrect password. Please try again.';
                break;
            case 'wrong_username':
                $error = 'Username not found. Please check your username or sign up.';
                break;
        }
    }

    if (isset($_GET['success'])) {
        switch ($_GET['success']) {
            case 'registration':
                $success = 'Registration successful! Please log in with your new account.';
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reset_mode'])) {
            // Handle password reset
            $username = trim($_POST['username'] ?? '');
            $new_password = $_POST['new_password'] ?? '';
            
            if ($username === '' || $new_password === '') {
                $error = 'All fields are required.';
            } else {
                $result = resetPasswordDB($username, $new_password);
                if ($result['success']) {
                    $success = 'Password reset successful. Please login with your new password.';
                    $is_reset_mode = false;
                } else {
                    switch ($result['error']) {
                        case 'username_not_found':
                            $error = 'Username not found. Please check your username.';
                            break;
                        default:
                            $error = 'An error occurred. Please try again.';
                    }
                }
            }
        } else {
            // Handle normal login
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            if ($username === '' || $password === '') {
                $error = 'All fields are required.';
            } else {
                loginDB($username, $password);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vhault</title>

     <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/global.css">
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="brand-container">
                <div class="brand-name">VHAULT</div>
                <div class="brand-tagline">Haul your files. Vault your world.</div>
            </div>
        </div>
        <div class="right">
            <form class="form-box" method="post" autocomplete="off">
                <h2><?php echo $is_reset_mode ? 'RESET PASSWORD' : 'LOG IN HERE'; ?></h2>
                <?php if ($error): ?>
                    <div class="message error"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="message success"><?php echo $success; ?></div>
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="input-group no-padding">
                    <div class="password-container">
                        <?php if ($is_reset_mode): ?>
                            <input type="password" name="new_password" placeholder="New Password" required id="loginPassword" class="password-input">
                        <?php else: ?>
                            <input type="password" name="password" placeholder="Password" required id="loginPassword" class="password-input">
                        <?php endif; ?>
                        <span id="togglePassword" class="password-toggle">
                            <i class="bi bi-eye-fill" style="color: #fff; font-size: 24px;"></i>
                        </span>
                    </div>
                </div>
                <?php if ($is_reset_mode): ?>
                    <input type="hidden" name="reset_mode" value="1">
                <?php endif; ?>
                <div class="forgot">
                    <?php if ($is_reset_mode): ?>
                        <a href="login.php">Back to Login</a>
                    <?php else: ?>
                        <a href="login.php?mode=reset">Forgot Password?</a>
                    <?php endif; ?>
                </div>
                <button type="submit"><?php echo $is_reset_mode ? 'Reset Password' : 'Log In'; ?></button>
                <div class="register-link">
                    Not a member? <a href="signup.php">Register Now</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- custom js -->
    <script src="assets/login.js"></script>
</body>
</html>