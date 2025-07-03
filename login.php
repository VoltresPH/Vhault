<?php
    require_once "inc/init.php";

    // if (isset($_SESSION['user_id'])) {
    //     header('Location: /');
    //     exit();
    // }

    $error = '';
    
    // Check for error parameter
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
            background: #23272f;
            color: #ffc107;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .right {
            background: #ffc107;
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
        .form-box .forgot {
            width: 100%;
            text-align: left;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #23272f;
            font-weight: 500;
        }
        .form-box .forgot a {
            color: #23272f;
            text-decoration: underline;
        }
        .form-box .forgot a:hover {
            color: #000;
        }
        .form-box .register-link {
            margin-top: 10px;
            font-size: 1.05rem;
            color: #23272f;
        }
        .form-box .register-link a {
            color: #23272f;
            text-decoration: underline;
            font-weight: 600;
        }
        .form-box .register-link a:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <div style="text-align:center;">
                <div style="font-size:4.2rem;font-weight:700;letter-spacing:2.5px; color:#ffc107; text-shadow: 1px 1px 4px #111;">VHAULT</div>
                <div style="font-size:1.5rem;font-weight:500;color:#fff;margin-top:16px; text-shadow: 1px 1px 4px #111;">Haul your files. Vault your world.</div>
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
                <div class="input-group" style="padding:0;">
                    <div style="position:relative; overflow:hidden; border-radius:24px; background:#000; width:100%;">
                        <input type="password" name="password" placeholder="Password" required id="loginPassword" style="padding-right:48px; background:#000; border-radius:24px;">
                        <span id="togglePassword" style="position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;display:flex;align-items:center;justify-content:center;">
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
    <script>
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
    </script>
</body>
</html>