<?php
// Dapat laging nasa pinakataas ito para gumana ang CSRF at Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TASSI PORTAL - HRMS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --lto-red: #c8102e;
            --lto-blue: #003087;
            --gray-dark: #333;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa !important;
            color: var(--gray-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── Navbar & Footer ─── */
        .navbar, .footer {
            background: #ed2b2b !important;
            color: white;
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-left a, .nav-right a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 0.9rem;
        }

        /* ─── Main Content ─── */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            border: 1px solid #eee;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .header-title { font-size: 1.9rem; color: var(--lto-blue); margin-bottom: 0.4rem; font-weight: 700; }
        .header-subtitle { font-size: 1rem; color: var(--lto-red); font-weight: 600; margin-bottom: 2rem; text-transform: uppercase; }

        .form-group { margin-bottom: 1.4rem; text-align: left; position: relative; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; color: #555; }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.85rem 1rem;
            padding-right: 2.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        input:focus {
            outline: none;
            border-color: var(--lto-blue);
            box-shadow: 0 0 0 3px rgba(0, 48, 135, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 40px; 
            cursor: pointer;
            color: #aaa;
            font-size: 1.1rem;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: var(--lto-blue); }

        .btn-login {
            background: var(--lto-red);
            color: white;
            border: none;
            width: 100%;
            padding: 0.9rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 0.5rem;
        }

        .btn-login:hover { background: #a00d26; transform: translateY(-1px); }

        .footer { justify-content: center; font-size: 0.85rem; margin-top: auto; text-align: center; }

        /* Bootstrap Alert Overrides */
        .alert { font-size: 0.9rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: left; }
    </style>
</head>
<body>

    <nav class="navbar shadow-sm">
        <div class="nav-left">
            <a href="https://www.tracealarm.com.ph/" target="_blank">TASSI Official Webpage</a>
            <a href="#">Contact</a>
        </div>
    </nav>

    <main class="main-content">
        <div class="login-container">
            <h1 class="header-title">TASSI PORTAL</h1>
            <div class="header-subtitle">Human Resource Management System</div>

            <?php if (isset($_GET['error']) && !in_array($_GET['error'], ['session_expired', 'account_disabled'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php
                        switch ($_GET['error']) {
                            case 'invalid_login': echo "Invalid username or password."; break;
                            case 'missing_fields': echo "Please fill in all required fields."; break;
                            default: echo "An error occurred. Please try again.";
                        }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/phphr-main/phphr-main/public/index.php?a=doLogin">
                <div class="form-group">
                    <label for="username">TASSI Username</label>
                    <input type="text" name="username" required placeholder="Enter your Username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your Password" required>
                    <i class="fas fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <button type="submit" class="btn-login">LOGIN</button>
            </form>
        </div>

        <div class="modal fade" id="securityModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-shield-lock-fill me-2"></i>Security Alert</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-5">
                        <i class="bi bi-exclamation-circle text-danger mb-3" style="font-size: 3.5rem;"></i>
                        <h3 class="fw-bold">Session Terminated</h3>
                        <p id="modalErrorMessage" class="text-muted fs-5 px-3"></p>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-danger btn-lg px-5 shadow-sm" data-bs-dismiss="modal">I Understand</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        © Trace Alarm & Security System, Inc. (TASSI) • Republic of the Philippines
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const errorType = urlParams.get('error');

        // Modal trigger para sa security-related errors lang
        if (errorType === 'session_expired' || errorType === 'account_disabled') {
            let message = errorType === 'session_expired' 
                ? "Someone else logged into your account or your session has expired for security reasons."
                : "Your account is currently inactive. Please contact the HR Administrator.";
            
            const messageElement = document.getElementById('modalErrorMessage');
            if (messageElement) {
                messageElement.innerText = message;
                const myModal = new bootstrap.Modal(document.getElementById('securityModal'));
                myModal.show();
            }
        }
    });
    </script>
</body>
</html>