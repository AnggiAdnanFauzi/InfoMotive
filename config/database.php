<?php
// Load configurations from .env if available
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
} else {
    $env = [];
}

$host = $env['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
$dbname = $env['DB_NAME'] ?? getenv('DB_NAME') ?: 'bengkel_db';
$username = $env['DB_USER'] ?? getenv('DB_USER') ?: 'root';
$password = $env['DB_PASS'] ?? getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- AUTO-UPDATE SCHEMA ---
    try {
        $pdo->exec("ALTER TABLE articles ADD COLUMN IF NOT EXISTS views INT DEFAULT 0");
        $pdo->exec("ALTER TABLE products ADD COLUMN IF NOT EXISTS views INT DEFAULT 0");
        $pdo->exec("ALTER TABLE workshops ADD COLUMN IF NOT EXISTS vehicle_type ENUM('motor', 'mobil', 'umum') DEFAULT 'motor'");
        $pdo->exec("UPDATE articles SET views = FLOOR(RAND() * 500) + 10 WHERE views = 0");
        $pdo->exec("UPDATE products SET views = FLOOR(RAND() * 1000) + 50 WHERE views = 0");
        $pdo->exec("UPDATE workshops SET vehicle_type = 'mobil' WHERE name LIKE '%Mobil%' OR name LIKE '%Auto%'");

        // --- AUTO-FIX REAL PRODUCT IMAGES ---
        $pdo->exec("UPDATE products SET image = 'https://images.pexels.com/photos/1031677/pexels-photo-1031677.jpeg?auto=compress&cs=tinysrgb&w=500' WHERE category = 'Oils' AND (image LIKE '%placehold%' OR image LIKE '%unsplash%')");
        $pdo->exec("UPDATE products SET image = 'https://images.pexels.com/photos/3752194/pexels-photo-3752194.jpeg?auto=compress&cs=tinysrgb&w=500' WHERE category = 'Tires' AND (image LIKE '%placehold%' OR image LIKE '%unsplash%')");
        $pdo->exec("UPDATE products SET image = 'https://images.pexels.com/photos/163140/battery-car-lead-acid-battery-acid-163140.jpeg?auto=compress&cs=tinysrgb&w=500' WHERE category = 'Electric' AND (image LIKE '%placehold%' OR image LIKE '%unsplash%')");
        $pdo->exec("UPDATE products SET image = 'https://images.pexels.com/photos/190539/pexels-photo-190539.jpeg?auto=compress&cs=tinysrgb&w=500' WHERE category = 'Engine' AND (image LIKE '%placehold%' OR image LIKE '%unsplash%')");
        $pdo->exec("UPDATE products SET image = 'https://images.pexels.com/photos/3807277/pexels-photo-3807277.jpeg?auto=compress&cs=tinysrgb&w=500' WHERE category = 'Parts' AND (image LIKE '%placehold%' OR image LIKE '%unsplash%')");
        $pdo->exec("UPDATE products SET image = 'https://images.pexels.com/photos/13065691/pexels-photo-13065691.jpeg?auto=compress&cs=tinysrgb&w=500' WHERE category = 'Ignition' AND (image LIKE '%placehold%' OR image LIKE '%unsplash%')");
        // ----------------------------------------

        // --- AUTO-IMPORT DATA IF EMPTY ---
        $stmtArt = $pdo->query("SELECT COUNT(*) FROM articles");
        if ($stmtArt && $stmtArt->fetchColumn() == 0) {
            ob_start();
            if (!defined('RUNNING_FROM_MIGRATION')) define('RUNNING_FROM_MIGRATION', true);
            include_once __DIR__ . '/../database/seeds/import_articles.php';
            include_once __DIR__ . '/../database/seeds/import_products.php';
            include_once __DIR__ . '/../database/seeds/seed_bengkel.php';
            ob_end_clean();
        }
        // ---------------------------------
    } catch (Exception $e) {
        // Silently continue if tables don't exist yet or other minor issues
    }
    // --------------------------

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
