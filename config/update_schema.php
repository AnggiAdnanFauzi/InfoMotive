<?php
require_once 'database.php';

try {
    // 1. Add views column to Articles if not exists
    $pdo->exec("ALTER TABLE articles ADD COLUMN IF NOT EXISTS views INT DEFAULT 0");
    
    // 2. Add views column to Products if not exists
    $pdo->exec("ALTER TABLE products ADD COLUMN IF NOT EXISTS views INT DEFAULT 0");

    // 3. Seed Random Data for Demo Purposes
    $pdo->exec("UPDATE articles SET views = FLOOR(RAND() * 500) + 10 WHERE views = 0");
    $pdo->exec("UPDATE products SET views = FLOOR(RAND() * 1000) + 50 WHERE views = 0");

    echo "Schema Updated & Data Seeded Successfully!";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
