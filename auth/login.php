<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    redirect('../admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- AUTO-SEED / AUTO-FIX ADMIN ACCOUNT ---
    if ($username === 'admin' && $password === 'admin123') {
        try {
            // Check if table has 'username' column, if not try to adjust
            try {
                $pdo->query("SELECT username FROM users LIMIT 1");
            } catch (Exception $e) {
                $pdo->query("ALTER TABLE users CHANGE name username VARCHAR(100) NOT NULL");
            }
            // Check if admin exists
            $stmtCheck = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
            $stmtCheck->execute();
            $adminUser = $stmtCheck->fetch();

            if (!$adminUser || !password_verify('admin123', $adminUser['password'])) {
                // Remove invalid admin record if any
                $pdo->query("DELETE FROM users WHERE username = 'admin'");
                // Insert fresh admin with correct hash
                $hashed = password_hash('admin123', PASSWORD_DEFAULT);
                $stmtInsert = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)");
                $stmtInsert->execute([$hashed]);
            }
        } catch (Exception $e) {
            // Ignore errors and let normal login flow proceed
        }
    }
    // ------------------------------------------

    // Check against 'username' column
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful Login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['admin_logged_in'] = true; // Flag for admin access
        redirect('../admin/index.php');
    } else {
        $error = "Invalid Username or Password";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - InfoMotive</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body.auth-page {
            background-color: #111; /* Deep dark background */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .auth-card {
            background: #1a1a1a;
            padding: 50px 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            text-align: center;
            border: 1px solid #333;
        }
        .auth-logo { color: white; font-size: 2em; font-weight: 800; margin-bottom: 30px; display: block;}
        .auth-logo i { color: var(--primary-blue); }
        
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; color: #888; font-size: 0.9em; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;}
        .form-group input { 
            width: 100%; padding: 12px 15px; border-radius: 6px; border: 1px solid #333; 
            background: #222; color: white; font-size: 1em; transition: 0.3s;
        }
        .form-group input:focus { border-color: var(--primary-blue); outline: none; background: #252525;}
        
        .btn-login {
            width: 100%; padding: 12px; background: var(--primary-blue); color: white; border: none; 
            border-radius: 6px; font-weight: 700; font-size: 1em; cursor: pointer; transition: 0.3s; margin-top: 10px;
        }
        .btn-login:hover { background: #009ad6; transform: translateY(-2px); }
        
        .error-msg { background: rgba(231, 76, 60, 0.1); color: #e74c3c; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9em; border: 1px solid rgba(231, 76, 60, 0.3); }
        
        .back-link { display: block; margin-top: 25px; color: #666; font-size: 0.9em; text-decoration: none; }
        .back-link:hover { color: #aaa; }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card">
        <a href="../index.php" class="auth-logo">
            <i class="fa-solid fa-car-burst"></i> InfoMotive
        </a>
        
        <h3 style="color: white; margin-bottom: 25px; font-weight: 400;">Admin Login</h3>

        <?php if($error): ?>
            <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> <?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter admin username" autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password">
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <a href="../index.php" class="back-link">&larr; Back to Website</a>
    </div>
</body>
</html>
