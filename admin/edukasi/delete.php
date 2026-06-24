<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) { redirect('../../auth/login.php'); }

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Handle error (optional)
    }
}

redirect('index.php');
