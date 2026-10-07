<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $loginResult = smartwills_login_validate((string) ($_POST['email'] ?? ''), (string) ($_POST['password'] ?? ''));

    if ($loginResult['ok']) {
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            $name = $loginResult['name'] ?? 'User';
        }

        $_SESSION['user'] = [
            'email' => $loginResult['email'],
            'name' => $name,
        ];

        header('Location: index.php');
        exit;
    }

    $loginError = $loginResult['message'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartWill | Plan Your Legacy with Confidence</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-red: #de0303;
            --primary-red-hover: #c00202;
            --primary-red-dark: #9e0000;
            --red-gradient: linear-gradient(135deg, #ff2a4b 0%, #de0303 50%, #9e0000 100%);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-page: #fbfbfd;
            --card-bg: #ffffff;
            --input-bg: #f8fafc;
            --input-border: #e2e8f0;
            --border-focus: #de0303;
            --shadow-card: 0 20px 45px -12px rgba(0, 0, 0, 0.12), 0 8px 24px -8px rgba(222, 3, 3, 0.08);
            --transition-smooth: all 0.6s cubic-bezier(0.68, -0.05, 0.265, 1.3);
            --transition-overlay: transform 0.65s cubic-bezier(0.77, 0, 0.175, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(at 0% 0%, rgba(222, 3, 3, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(222, 3, 3, 0.08) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(241, 245, 249, 0.8) 0px, transparent 100%);
            color: var(--text-main);
            min-height: 100vh;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            overflow: hidden;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 860px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Top Brand Header */
        .top-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .top-brand:hover {
            transform: translateY(-1px);
        }

        .sw-circle {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #ffffff;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: -0.5px;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .brand-name .dark { color: #1e293b; }
        .brand-name .orange { color: #de0303; }

        /* Double Slider Card Container */
        .auth-container {
            background-color: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--shadow-card);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 860px;
            min-height: 500px;
            border: 1px solid rgba(226, 232, 240, 0.85);
        }

        /* Mobile Segmented Toggle */
        .mobile-auth-toggle {
            display: none;
            width: calc(100% - 32px);
            margin: 14px auto 0 auto;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 12px;
            position: relative;
            z-index: 10;
        }

        .mobile-toggle-btn {
            flex: 1;
            padding: 8px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .mobile-toggle-btn.active {
            background: #ffffff;
            color: var(--primary-red);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        /* Form Containers */
        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: var(--transition-overlay);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 34px;
        }

        .form-container form {
            width: 100%;
            max-width: 330px;
            display: flex;
            flex-direction: column;
        }

        /* Sign In Container (Default Left) */
        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
            opacity: 1;
            transform: translateX(0);
        }

        /* Sign Up Container (Default Right Underneath) */
        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            transform: translateX(0);
            pointer-events: none;
        }

        /* Transition when Active State (Sign Up Mode) */
        .auth-container.right-panel-active .sign-in-container {
            transform: translateX(100%);
            opacity: 0;
            pointer-events: none;
            z-index: 1;
        }

        .auth-container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            pointer-events: all;
            animation: showPanel 0.65s cubic-bezier(0.77, 0, 0.175, 1);
        }

        @keyframes showPanel {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        /* Form Typography & Header */
        .form-header {
            margin-bottom: 12px;
            text-align: left;
        }

        .form-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 8px;
            background: rgba(222, 3, 3, 0.08);
            color: var(--primary-red);
            font-size: 10px;
            font-weight: 700;
            border-radius: 14px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .form-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
            margin-bottom: 3px;
        }

        .form-header p {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.35;
        }

        /* Form Groups & Inputs */
        .form-group {
            margin-bottom: 10px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .input-wrapper input {
            width: 100%;
            height: 38px;
            border: 1.5px solid var(--input-border);
            border-radius: 10px;
            padding: 0 36px 0 34px;
            font-size: 13px;
            color: var(--text-main);
            background: var(--input-bg);
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-wrapper input:focus {
            border-color: var(--border-focus);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(222, 3, 3, 0.12);
        }

        .input-wrapper input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--primary-red);
        }

        .password-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            font-size: 13px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--text-main);
        }

        /* Form Options (Remember & Forgot) */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 2px;
            margin-bottom: 12px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .remember input {
            accent-color: var(--primary-red);
            width: 15px;
            height: 15px;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-options a {
            color: var(--primary-red);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .form-options a:hover {
            text-decoration: underline;
            opacity: 0.85;
        }

        /* Action Buttons */
        .login-button {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 10px;
            background: var(--red-gradient);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(222, 3, 3, 0.25);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(222, 3, 3, 0.35);
            filter: brightness(1.03);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Alert / Message Boxes */
        .message-box {
            display: none;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.4;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-box.show {
            display: block;
        }

        .message-box.error,
        .message-box.show.error {
            display: block;
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
        }

        .message-box.success,
        .message-box.show.success {
            display: block;
            background-color: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #166534;
        }

        .message-box.info,
        .message-box.show.info {
            display: block;
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            color: #1e40af;
        }

        /* =========================================
           SLIDING OVERLAY PANEL
           ========================================= */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: var(--transition-overlay);
            z-index: 100;
        }

        .auth-container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: var(--red-gradient);
            color: #ffffff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: var(--transition-overlay);
        }

        .auth-container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        /* Decorative background patterns on overlay */
        .overlay::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .overlay::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: 10%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 32px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: var(--transition-overlay);
            z-index: 2;
        }

        .overlay-panel h1 {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .overlay-panel p {
            font-size: 13px;
            line-height: 1.45;
            margin-bottom: 18px;
            color: rgba(255, 255, 255, 0.9);
            max-width: 290px;
        }

        .overlay-features {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
            text-align: left;
            width: 100%;
            max-width: 280px;
        }

        .overlay-feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.95);
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .overlay-feature-item i {
            color: #ffffff;
            font-size: 13px;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .auth-container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .auth-container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        /* Ghost/Outline Button on Overlay */
        .ghost-button {
            border-radius: 10px;
            border: 1.5px solid #ffffff;
            background-color: transparent;
            color: #ffffff;
            font-size: 12.5px;
            font-weight: 700;
            padding: 9px 28px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
        }

        .ghost-button:hover {
            background-color: #ffffff;
            color: var(--primary-red);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .ghost-button:active {
            transform: translateY(0);
        }

        /* Fallback / Hidden original tabs for backward compatibility with scripts */
        #authTabs {
            display: none !important;
        }

        /* Responsive Breakpoints */
        @media (max-width: 860px) {
            body {
                height: auto;
                min-height: 100vh;
                overflow-y: auto;
                padding: 18px 12px;
            }

            .auth-container {
                min-height: auto;
                border-radius: 18px;
            }

            .overlay-container {
                display: none;
            }

            .mobile-auth-toggle {
                display: flex;
            }

            .form-container {
                position: relative;
                width: 100%;
                left: 0 !important;
                transform: none !important;
                padding: 24px 20px;
            }

            .sign-in-container {
                display: flex;
            }

            .sign-up-container {
                display: none;
                opacity: 1;
                pointer-events: all;
            }

            .auth-container.right-panel-active .sign-in-container {
                display: none;
            }

            .auth-container.right-panel-active .sign-up-container {
                display: flex;
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>

<body>

<div class="auth-wrapper">
    
    <!-- Top Branding -->
    <a href="index.php" class="top-brand">
        <div class="sw-circle">SW</div>
        <div class="brand-name">
            <span class="dark">Smart</span><span class="orange">Will</span>
        </div>
    </a>

    <!-- Double Slider Card -->
    <div class="auth-container" id="authCardContainer">
        
        <!-- Mobile Segmented Toggle -->
        <div class="mobile-auth-toggle">
            <button type="button" class="mobile-toggle-btn active" id="mobileSignInBtn">Sign In</button>
            <button type="button" class="mobile-toggle-btn" id="mobileSignUpBtn">Create Account</button>
        </div>

        <!-- Hidden auth tabs for script compatibility -->
        <div class="auth-tabs" id="authTabs" style="display: none;">
            <button type="button" class="active" data-tab="login">Sign In</button>
            <button type="button" data-tab="register">Register</button>
        </div>

        <!-- SIGN UP (CREATE ACCOUNT) FORM PANEL -->
        <div class="form-container sign-up-container" id="registerFormContainer">
            <form id="registerForm" action="login.php" method="POST">
                <div class="form-header">
                    <span class="form-badge"><i class="fa-solid fa-sparkles"></i> Get Started</span>
                    <h2>Create Account</h2>
                    <p>Start planning your legacy with SmartWill today</p>
                </div>

                <div id="registerMessageBox" class="message-box"></div>

                <div class="form-group">
                    <label for="regName">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-user input-icon"></i>
                        <input
                            type="text"
                            id="regName"
                            name="regName"
                            placeholder="e.g. John Doe"
                            value=""
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="regEmail">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input
                            type="email"
                            id="regEmail"
                            name="regEmail"
                            placeholder="you@example.com"
                            value=""
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="regPassword"
                            name="regPassword"
                            placeholder="Create strong password"
                            value=""
                            required
                        >
                        <button type="button" class="password-toggle" id="toggleRegPasswordBtn" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="regConfirm">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-shield-check input-icon"></i>
                        <input
                            type="password"
                            id="regConfirm"
                            name="regConfirm"
                            placeholder="Repeat password"
                            value=""
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="login-button" style="margin-top: 8px;">
                    <span>Create Account</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <!-- SIGN IN FORM PANEL -->
        <div class="form-container sign-in-container" id="loginFormContainer">
            <form id="loginForm" action="login.php" method="POST">
                <input type="hidden" id="userNameInput" name="name" value="">

                <div class="form-header">
                    <span class="form-badge"><i class="fa-solid fa-shield-halved"></i> Secure Access</span>
                    <h2>Welcome Back</h2>
                    <p>Enter your credentials to access your estate planner</p>
                </div>

                <div id="messageBox" class="message-box <?php echo $loginError ? 'show error' : ''; ?>">
                    <?php echo htmlspecialchars($loginError); ?>
                </div>

                <div class="form-group">
                    <label for="email">Email or Username</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            value=""
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            value=""
                            required
                        >
                        <button type="button" class="password-toggle" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" checked>
                        <span>Remember Me</span>
                    </label>
                    <a href="#" id="forgotLink">Forgot Password?</a>
                </div>

                <button type="submit" class="login-button">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <!-- SLIDING OVERLAY CONTAINER -->
        <div class="overlay-container">
            <div class="overlay">
                
                <!-- Overlay Left (Shown in Sign Up Mode -> leads back to Sign In) -->
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>Already have an account? Sign in to keep managing your legacy and protected plans.</p>
                    <div class="overlay-features">
                        <div class="overlay-feature-item">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Bank-grade 256-bit encryption</span>
                        </div>
                        <div class="overlay-feature-item">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Legally sound verified templates</span>
                        </div>
                    </div>
                    <button class="ghost-button" id="signInBtn">Sign In</button>
                </div>

                <!-- Overlay Right (Shown in Sign In Mode -> leads to Sign Up) -->
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your details and start your journey towards effortless estate planning peace of mind.</p>
                    <div class="overlay-features">
                        <div class="overlay-feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Step-by-step guided Will generation</span>
                        </div>
                        <div class="overlay-feature-item">
                            <i class="fa-solid fa-users"></i>
                            <span>Seamless family sharing access</span>
                        </div>
                    </div>
                    <button class="ghost-button" id="signUpBtn">Create Account</button>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="assets/js/login.js"></script>


</body>
</html>
