<?php
    require_once "inc/init.php";

    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit();
    }

    $error = '';
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($username === '' || $password === '') {
            $error = 'All fields are required.';
        } else {
            loginDB($username, $password);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vhault</title>

    <!-- font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
    </style>

     <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- custom css -->
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
                <h2>LOG IN HERE</h2>
                <?php if ($error): ?>
                    <div class="message error"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="input-group no-padding">
                    <div class="password-container">
                        <input type="password" name="password" placeholder="Password" required id="loginPassword" class="password-input">
                        <span id="togglePassword" class="password-toggle">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="#fff" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#fff" stroke-width="2"/></svg>
                        </span>
                    </div>
                </div>
                <div class="forgot">
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit">Log In</button>
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