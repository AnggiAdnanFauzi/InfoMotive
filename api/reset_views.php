<?php
require_once '../config/database.php';

try {
    // Reset Articles
    $stmt = $pdo->prepare("UPDATE articles SET views = 0");
    $stmt->execute();
    echo "Reset Articles: Success<br>";

    // Reset Products
    $stmt = $pdo->prepare("UPDATE products SET views = 0");
    $stmt->execute();
    echo "Reset Products: Success<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
