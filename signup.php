<?php
    require_once "inc/init.php";
  
    // if (isset($_SESSION['user_id'])) {
    //     header('Location: /');
    //     exit();
    // }

    $error = '';
    $success = '';

    // Check for result parameter
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
  
    <!-- font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
    </style>

     <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            min-width: 100vw;
            min-height: 100vh;
            overflow-x: hidden;
            background: #fff;
        }
    
        body {
            width: 100vw;
            height: 100vh;
        }
        .container {
            display: flex;
            height: 100vh;
            width: 100vw;
            min-width: 100vw;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .left, .right {
            flex: 1 1 0;
            min-width: 0;
            min-height: 0;
            height: 100vh;
        }
        .left {
            background: #ffc107;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .right {
            background: #23272f;
            color: #ffc107;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .form-box {
            background: transparent;
            border: 3px dashed #23272f;
            border-radius: 36px;
            padding: 56px 40px 40px 40px;
            width: 600px;
            max-width: 99vw;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 6px 32px rgba(0,0,0,0.07);
        }
        .form-box h2 {
            margin-bottom: 36px;
            font-size: 2.3rem;
            font-weight: 800;
            color: #1a1a1a;
            letter-spacing: 1px;
        }
        .input-group {
            width: 100%;
            margin-bottom: 24px;
        }
        .input-group input {
            width: 100%;
            padding: 16px 24px;
            border: none;
            border-radius: 24px;
            background: #000;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 600;
            outline: none;
        }
        .input-group input::placeholder {
            color: #fff;
            opacity: 1;
            font-weight: 600;
            font-size: 1.05rem;
        }
        .form-box button {
            width: 70%;
            padding: 14px 0;
            border: none;
            border-radius: 24px;
            background: #888;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 24px;
            transition: background 0.2s;
            letter-spacing: 0.5px;
        }
        .form-box button:hover {
            background: #23272f;
        }
        .form-box .login-link {
            margin-top: 10px;
            font-size: 1.05rem;
            color: #23272f;
        }
        .form-box .login-link a {
            color: #23272f;
            text-decoration: underline;
            font-weight: 600;
        }
        .form-box .login-link a:hover {
            color: #000;
        }
        .message {
            width: 100%;
            margin-bottom: 18px;
            text-align: center;
            font-size: 1.08rem;
        }
        .error {
            color: #d32f2f;
            font-weight: 600;
        }
        .success {
            color: #388e3c;
            font-weight: 600;
        }
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                width: 100vw;
                height: 100vh;
                min-width: 100vw;
                min-height: 100vh;
            }
            .left, .right {
                width: 100vw;
                height: 50vh;
                min-width: 100vw;
                min-height: 0;
            }
            .form-box {
                width: 98vw;
                max-width: 98vw;
                padding: 32px 8vw 24px 8vw;
            }
        }
        @media (max-width: 600px) {
            .form-box {
                padding: 18px 2vw 12px 2vw;
                font-size: 1rem;
            }
            .form-box h2 {
                font-size: 1.3rem;
            }
        }
        /* Slide overlay for transition */
        .slide-overlay {
            position: fixed;
            top: 0; left: 100vw;
            width: 100vw;
            height: 100vh;
            background: #23272f;
            z-index: 9999;
            transition: left 0.7s cubic-bezier(.77,0,.18,1);
        }
        .slide-overlay.active {
            left: 0;
        }
    </style>
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
            <div style="text-align:center;">
                <div style="font-size:4.2rem;font-weight:700;letter-spacing:2.5px; color:#ffc107; text-shadow: 1px 1px 4px #111;">VHAULT</div>
                <div style="font-size:1.5rem;font-weight:500;color:#fff;margin-top:16px; text-shadow: 1px 1px 4px #111;">Haul your files. Vault your world.</div>
            </div>
        </div>
    </div>
    <div class="slide-overlay" id="slideOverlay"></div>
    <script>
    document.getElementById('slideToLogin').addEventListener('click', function(e) {
        e.preventDefault();
        var overlay = document.getElementById('slideOverlay');
        overlay.classList.add('active');
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 700); // match transition duration
    });
    </script>
</body>
</html>