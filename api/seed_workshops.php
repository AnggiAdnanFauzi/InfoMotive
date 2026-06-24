<?php
require_once '../config/database.php';

try {
    // Check count
    $stmt = $pdo->query("SELECT count(*) as count FROM workshops");
    $row = $stmt->fetch();
    echo "Current Count: " . $row['count'] . "\n";
    
    if ($row['count'] == 0) {
        $sql = "INSERT INTO workshops (name, address, lat, lng) VALUES ('Bengkel Maju Jaya', 'Jl. Merdeka No. 45, Jakarta Pusat', -6.175110, 106.865036)";
        $pdo->exec($sql);
        echo "Inserted 'Bengkel Maju Jaya' successfully.\n";
    } else {
        echo "Table already has data. Skipping insert.\n";
    }
    
    // Check again
    $stmt = $pdo->query("SELECT * FROM workshops LIMIT 1");
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
