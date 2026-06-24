<?php
if (php_sapi_name() !== 'cli' && !defined('RUNNING_FROM_MIGRATION')) {
    http_response_code(403);
    die("Forbidden: Direct web access is strictly prohibited.");
}

require_once __DIR__ . '/../../config/database.php';

echo "<h1>Resetting Admin Account...</h1>";

try {
    // 1. Force Alter Table if 'name' exists but 'username' does not
    try {
        $pdo->query("SELECT username FROM users LIMIT 1");
    } catch (Exception $e) {
        // 'username' column likely doesn't exist, try to rename 'name'
        echo "Updating table structure (name -> username)...<br>";
        try {
            $pdo->query("ALTER TABLE users CHANGE name username VARCHAR(100) NOT NULL");
            echo "Table structure updated.<br>";
        } catch (Exception $ex) {
            echo "Note: Could not rename column (might already be correct or table missing).<br>";
        }
    }

    // 2. Delete existing 'admin' to avoid conflicts
    $pdo->query("DELETE FROM users WHERE username = 'admin'");

    // 3. Insert fresh Admin with CORRECT HASH
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)");
    
    if ($stmt->execute([$hashed_password])) {
        echo "<h2 style='color: green;'>Success!</h2>";
        echo "<p>Admin user created/reset.</p>";
        echo "<ul>";
        echo "<li>Username: <strong>admin</strong></li>";
        echo "<li>Password: <strong>admin123</strong></li>";
        echo "</ul>";
        echo "<a href='../../auth/login.php'>Go to Login</a>";
    } else {
        echo "<h2 style='color: red;'>Failed to insert user.</h2>";
        print_r($stmt->errorInfo());
    }

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Database Error</h2>";
    echo $e->getMessage();
}
