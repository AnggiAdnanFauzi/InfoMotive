<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Current Dir: " . __DIR__ . "\n";
$configPath = __DIR__ . '/../config/ai_config.php';
echo "Config Path: " . $configPath . "\n";

if (file_exists($configPath)) {
    echo "Config file FOUND.\n";
    require_once $configPath;
} else {
    echo "Config file NOT FOUND.\n";
}

if (defined('AI_API_KEY')) {
    echo "AI_API_KEY is DEFINED.\n";
    echo "Value length: " . strlen(AI_API_KEY) . "\n";
    echo "Value: " . AI_API_KEY . "\n";
} else {
    echo "AI_API_KEY is NOT DEFINED.\n";
}
?>
