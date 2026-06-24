<?php
if (php_sapi_name() !== 'cli' && !defined('RUNNING_FROM_MIGRATION')) {
    http_response_code(403);
    die("Forbidden: Direct web access is strictly prohibited.");
}

require_once __DIR__ . '/../../config/database.php';

try {
    // Check if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM workshops");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $sql = "INSERT INTO workshops (name, address, phone, lat, lng, created_at) VALUES 
        ('Seven Auto Service [Bengkel 24 Jam Bandung]', 'Jl. Terusan Buah Batu, Bandung', '0812-3456-7890', -6.953, 107.653, NOW()),
        ('Championship Motor', 'Jl. Jendral Sudirman No. 45, Bandung', '0857-9999-8888', -6.917, 107.609, NOW()),
        ('Bengkel Mobil Guna Lancar', 'Jl. Soekarno Hatta No. 100, Bandung', '022-7561234', -6.945, 107.650, NOW()),
        ('Bengkel Mobil Abeng', 'Jl. Peta No. 20, Bandung', '0813-2222-1111', -6.930, 107.590, NOW()),
        ('Prima Motor', 'Jl. Cibaduyut Lama, Bandung', '0821-3333-4444', -6.950, 107.595, NOW()),
        ('Bengkel Mobil AJM Auto', 'Jl. Kopo Sayati, Bandung', '0877-5555-6666', -6.960, 107.580, NOW()),
        ('Bengkel Motor Saung Pajajaran', 'Jl. Pajajaran No. 88, Bandung', '022-6012345', -6.905, 107.590, NOW()),
        ('Bengkel Motor Sadiq', 'Jl. Dipati Ukur, Bandung', '0856-7777-8888', -6.890, 107.615, NOW())";
        
        $pdo->exec($sql);
        echo "Successfully seeded " . $pdo->query("SELECT COUNT(*) FROM workshops")->fetchColumn() . " workshops.";
    } else {
        echo "Table already has $count workshops.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
