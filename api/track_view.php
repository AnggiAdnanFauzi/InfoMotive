<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'] ?? '';
$id = $data['id'] ?? 0;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'No ID']);
    exit;
}

try {
    if ($type === 'article') {
        $stmt = $pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    } 
    elseif ($type === 'product') {
        $stmt = $pdo->prepare("UPDATE products SET views = views + 1 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    } 
    else {
        echo json_encode(['success' => false, 'error' => 'Invalid Type']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
