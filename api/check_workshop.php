<?php
require_once '../config/database.php';

try {
    $stmt = $pdo->query("SELECT count(*) as count FROM workshops");
    $row = $stmt->fetch();
    echo "Workshops Count: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $stmt = $pdo->query("SELECT * FROM workshops LIMIT 1");
        print_r($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo "Table is empty.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
