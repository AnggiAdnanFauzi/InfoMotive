<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn()) { redirect('../../auth/login.php'); }
$id = $_GET['id'] ?? null;
if ($id) {
    $pdo->prepare("DELETE FROM workshops WHERE id = ?")->execute([$id]);
}
redirect('index.php');
