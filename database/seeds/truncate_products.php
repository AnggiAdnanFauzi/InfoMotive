<?php
if (php_sapi_name() !== 'cli' && !defined('RUNNING_FROM_MIGRATION')) {
    http_response_code(403);
    die("Forbidden: Direct web access is strictly prohibited.");
}

require_once __DIR__ . '/../../config/database.php';
// Utility to clear scraped products
$pdo->query("TRUNCATE TABLE products");
echo "Products table cleared. You can now run import_products.php to re-fetch fresh content.";
echo "<br><a href='import_products.php'>Run Importer</a>";
