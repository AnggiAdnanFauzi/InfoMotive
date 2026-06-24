<?php
if (php_sapi_name() !== 'cli' && !defined('RUNNING_FROM_MIGRATION')) {
    http_response_code(403);
    die("Forbidden: Direct web access is strictly prohibited.");
}

require_once __DIR__ . '/../../config/database.php';
// Utility to clear scraped articles (since they might be duplicates/incomplete)
// WARNING: This clears ALL articles. Use with caution.
$pdo->query("TRUNCATE TABLE articles");
echo "Articles table cleared. You can now run import_articles.php to re-fetch fresh content.";
echo "<br><a href='import_articles.php'>Run Importer</a>";
