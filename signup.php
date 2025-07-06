<?php
    require_once "inc/init.php";
  
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit();
    }

    $error = '';
    $success = '';
    if (isset($_GET['result'])) {
        if ($_GET['result'] === 'exists') {
            $error = 'Username already taken.';
        } elseif ($_GET['result'] === 'false') {
            $error = 'Registration failed. Please try again.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($username === '' || $password === '' || $confirm_password === '') {
            $error = 'All fields are required.';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            createAccountDB($username, $password);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Vhault</title>
  
     <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- custom css -->
    <link rel="stylesheet" href="assets/signup.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <form class="form-box" method="post" autocomplete="off">
                <h2>CREATE ACCOUNT</h2>
                <?php if ($error): ?>
                    <div class="message error"><?php echo $error; ?></div>
                <?php elseif ($success): ?>
                    <div class="message success"><?php echo $success; ?></div>
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit">Register</button>
                <div class="login-link">
                    Already Have an Account? ? <a href="#" id="slideToLogin">Log In</a>
                </div>
            </form>
        </div>
        <div class="right">
            <div class="brand-container">
                <div class="brand-name">VHAULT</div>
                <div class="brand-tagline">Haul your files. Vault your world.</div>
            </div>
        </div>
    </div>
    <div class="slide-overlay" id="slideOverlay"></div>
    
    <!-- custom js -->
    <script src="assets/signup.js"></script>
</body>
</html>