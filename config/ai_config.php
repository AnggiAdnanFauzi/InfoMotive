<?php
// AI Configuration
// Securely load from .env if available

if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
} else {
    $env = [];
}

$provider = $env['AI_PROVIDER'] ?? getenv('AI_PROVIDER') ?: 'gemini';
$apiKey = $env['AI_API_KEY'] ?? getenv('AI_API_KEY') ?: '';

if (!defined('AI_PROVIDER')) define('AI_PROVIDER', $provider);
if (!defined('AI_API_KEY')) define('AI_API_KEY', $apiKey);
?>
