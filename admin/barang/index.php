<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// STRICT: Admin Only Access
if (!isLoggedIn()) {
    redirect('../../auth/login.php');
}

// Search & Filter Logic
$search = $_GET['search'] ?? '';
$cat_filter = $_GET['category'] ?? '';
$type_filter = $_GET['vehicle_type'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND name LIKE ?";
    $params[] = "%$search%";
}

if ($cat_filter) {
    $where .= " AND category = ?";
    $params[] = $cat_filter;
}

if ($type_filter) {
    $where .= " AND vehicle_type = ?";
    $params[] = $type_filter;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE $where ORDER BY created_at DESC");
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Barang - InfoMotive</title>
    <!-- Use main style -->
    <link rel="stylesheet" href="../../assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white; /* Clean White Background */
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- COPY OF ADMIN NAVBAR STYLES --- */
        .navbar-admin {
            width: 100%;
            height: 60px;
            background: #0f172a;
            display: flex;
            align-items: center;
            padding: 0 40px;
            flex-shrink: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-inner {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .navbar-admin a {
            color: #bdc3c7;
            text-decoration: none;
            margin-left: 25px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            transition: color 0.3s;
        }
        .navbar-admin a:hover, .navbar-admin a.active { color: #00E5FF; }
        .logout-btn { color: #ff6b6b !important; }
        .logout-btn:hover { color: #ff4757 !important; text-shadow: 0 0 5px rgba(255, 71, 87, 0.5); }

        /* --- PAGE CONTENT --- */
        .container {
            padding: 30px 40px; 
            max-width: 100%;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            color: #00E5FF;
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        /* Controls: Filters & Search */
        .controls-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .left-controls {
            display: flex;
            gap: 15px;
        }

        /* Dropdowns */
        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #e0e0e0;
            color: #555;
            font-size: 0.9rem;
            min-width: 150px;
            cursor: pointer;
            outline: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 1em;
            padding-right: 30px;
        }

        /* Search Bar */
        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            width: 300px;
            background: #e0e0e0;
        }

        .search-input {
            border: none;
            padding: 8px 15px;
            flex: 1;
            font-size: 0.9rem;
            outline: none;
            background: transparent;
        }
        
        .search-btn {
            background: transparent;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 1rem;
        }

        /* --- DATA TABLE CARD --- */
        .data-card {
            background: #f4f4f4;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            min-height: 500px;
        }

        .add-btn {
            background: #00AEEF;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .add-btn:hover { background: #0095cc; }

        /* Table Styles */
        .table-responsive {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .table th {
            text-align: left;
            padding: 15px 20px;
            font-weight: 700;
            color: #222;
            border-bottom: 1px solid #eee;
        }

        .table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f9f9f9;
            color: #555;
            vertical-align: middle;
        }

        .table tr:last-child td { border-bottom: none; }

        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .btn-view { background: #00AEEF; } /* Cyan */
        .btn-edit { background: #2ecc71; } /* Green */
        .btn-delete { background: #ff0000; } /* Red */
        
        .action-btn:hover { opacity: 0.9; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .modal-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            width: 400px;
            text-align: center;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-text {
            font-size: 1rem;
            color: #333;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-modal {
            padding: 8px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .btn-ok { background: #e0e0e0; color: #333; }
        .btn-ok:hover { background: #d0d0d0; }
        .btn-cancel { background: transparent; color: #333; font-weight: 400; }
        .btn-cancel:hover { text-decoration: underline; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Admin Navigation -->
    <nav class="navbar-admin">
        <div class="nav-inner">
            <a href="../index.php">Dashboard</a>
            <a href="../../about.php">About</a>
            <a href="../edukasi/index.php">Edukasi</a>
            <a href="index.php" class="active">Barang</a>
            <a href="../bengkel/index.php">Bengkel</a>
            <a href="../../includes/logout.php" class="logout-btn">Logout Admin</a>
        </div>
    </nav>

    <div class="container">
        <!-- Title -->
         <h1 class="page-title">Barang</h1>

        <!-- Controls Form -->
        <form method="GET" class="controls-row">
            <div class="left-controls">
                <!-- Category Select -->
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">Pilih Kategori...</option>
                    <option value="Oils" <?= $cat_filter == 'Oils' ? 'selected' : '' ?>>Oils (Oli)</option>
                    <option value="Parts" <?= $cat_filter == 'Parts' ? 'selected' : '' ?>>Parts (Suku Cadang)</option>
                    <option value="Tires" <?= $cat_filter == 'Tires' ? 'selected' : '' ?>>Tires (Ban)</option>
                    <option value="Electric" <?= $cat_filter == 'Electric' ? 'selected' : '' ?>>Electric (Aki & Kelistrikan)</option>
                    <option value="Engine" <?= $cat_filter == 'Engine' ? 'selected' : '' ?>>Engine (Komponen Mesin)</option>
                    <option value="Ignition" <?= $cat_filter == 'Ignition' ? 'selected' : '' ?>>Ignition (Busi)</option>
                </select>

                <!-- Vehicle Select -->
                <select name="vehicle_type" class="filter-select" onchange="this.form.submit()">
                    <option value="">Pilih Kendaraan...</option>
                    <option value="mobil" <?= $type_filter == 'mobil' ? 'selected' : '' ?>>Mobil</option>
                    <option value="motor" <?= $type_filter == 'motor' ? 'selected' : '' ?>>Motor</option>
                    <option value="umum" <?= $type_filter == 'umum' ? 'selected' : '' ?>>Umum</option>
                </select>
            </div>

            <div class="search-box">
                <input type="text" name="search" class="search-input" placeholder="" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>

        <!-- Data Card -->
        <div class="data-card">
            <!-- Add Button -->
            <a href="create.php" class="add-btn">Tambah</a>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jenis Kendaraan</th>
                            <th>Harga</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($products)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">Data tidak ditemukan.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($products as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td style="font-weight: 500; color: #333;"><?= htmlspecialchars($p['name']) ?></td>
                                <td><?= htmlspecialchars($p['category']) ?></td>
                                <td><?= htmlspecialchars($p['vehicle_type']) ?></td>
                                <td>Rp <?= number_format($p['price_min'], 0, ',', '.') ?></td>
                                <td>
                                    <!-- View (Blue) -->
                                    <a href="view.php?id=<?= $p['id'] ?>" class="action-btn btn-view"><i class="fa-solid fa-eye"></i></a>
                                    <!-- Edit (Green) -->
                                    <a href="edit.php?id=<?= $p['id'] ?>" class="action-btn btn-edit"><i class="fa-solid fa-pen"></i></a>
                                    <!-- Delete (Red) -->
                                    <button onclick="openDeleteModal(<?= $p['id'] ?>)" class="action-btn btn-delete" style="border:none; cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Custom Delete Modal -->
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <p class="modal-text">Apakah Anda Yakin Ingin Menghapus data Ini?</p>
            <div class="modal-buttons">
                <button onclick="confirmDelete()" class="btn-modal btn-ok">Ok</button>
                <button onclick="closeDeleteModal()" class="btn-modal btn-cancel">Cancle</button>
            </div>
        </div>
    </div>

    <script>
        let deleteId = null;

        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            deleteId = null;
            document.getElementById('deleteModal').style.display = 'none';
        }

        function confirmDelete() {
            if (deleteId) {
                window.location.href = 'delete.php?id=' + deleteId;
            }
        }

        window.onclick = function(event) {
            let modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>
</body>
</html>
