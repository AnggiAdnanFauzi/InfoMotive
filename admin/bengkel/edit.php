<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) { redirect('../../auth/login.php'); }

$id = $_GET['id'] ?? null;
if(!$id) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $stmt = $pdo->prepare("UPDATE workshops SET name=?, address=?, phone=?, lat=?, lng=? WHERE id=?");
    if ($stmt->execute([$name, $address, $phone, $lat, $lng, $id])) {
        redirect('index.php');
    }
}

$stmt = $pdo->prepare("SELECT * FROM workshops WHERE id = ?");
$stmt->execute([$id]);
$workshop = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Bengkel</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; background: #222; }
        .sidebar { width: 250px; position: fixed; height: 100%; background: #1a1a1a; color: #fff; border-right: 1px solid #333; }
        .admin-content { margin-left: 250px; padding: 40px; width: 100%; color: white; }
        .form-card { background: white; padding: 30px; border-radius: 8px; color: #333; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-submit { background: #f39c12; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="admin-container">
        <main class="admin-content">
            <h2>Edit Bengkel</h2>
            <div class="form-card">
                <form method="POST">
                    <div class="form-group">
                        <label>Nama Bengkel</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($workshop['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control" required><?= htmlspecialchars($workshop['address']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($workshop['phone']) ?>" required>
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex:1;">
                            <label>Latitude</label>
                            <input type="text" name="lat" class="form-control" value="<?= $workshop['lat'] ?>" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Longitude</label>
                            <input type="text" name="lng" class="form-control" value="<?= $workshop['lng'] ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Update Bengkel</button>
                    <a href="index.php" style="margin-left: 10px; color: #666;">Batal</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
