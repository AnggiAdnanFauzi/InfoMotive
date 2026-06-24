<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) { redirect('../../auth/login.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price_min = $_POST['price_min'];
    $price_max = $_POST['price_max'];
    $vehicle_type = $_POST['vehicle_type'];
    $description = $_POST['description'];
    
    // Simple Image Upload 
    $image = 'https://via.placeholder.com/150'; 
    if (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
        $image = $_POST['image_url'];
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, category, price_min, price_max, vehicle_type, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $category, $price_min, $price_max, $vehicle_type, $description, $image])) {
        // Redirect to List
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; background: #222; }
        .sidebar { width: 250px; position: fixed; height: 100%; background: #1a1a1a; color: #fff; border-right: 1px solid #333; }
        .admin-content { margin-left: 250px; padding: 40px; width: 100%; color: white; }
        .form-card { background: white; padding: 30px; border-radius: 8px; color: #333; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-submit { background: var(--primary-blue); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar"></* Sidebar Code Simplified */></aside>
        <main class="admin-content">
            <h2>Tambah Barang</h2>
            <div class="form-card">
                <form method="POST">
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category" class="form-control">
                            <option>Oils</option>
                            <option>Parts</option>
                            <option>Tires</option>
                            <option>Engine</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Range Harga (Rp)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="number" name="price_min" class="form-control" placeholder="Min (Contoh: 150000)" required>
                            <span style="align-self: center;">-</span>
                            <input type="number" name="price_max" class="form-control" placeholder="Max (Contoh: 300000)" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kendaraan</label>
                        <select name="vehicle_type" class="form-control">
                            <option value="umum">Umum</option>
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                    </div>
                     <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi & Kegunaan</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan kegunaan barang ini..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Simpan Barang</button>
                    <a href="index.php" style="margin-left: 10px; color: #666;">Batal</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
