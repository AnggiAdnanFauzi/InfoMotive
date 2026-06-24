<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Public Dashboard - No Login Required
// We still start session in functions.php to check if an admin IS logged in

// Mock Products
$products = [
    ['name' => 'Synthetic Oil 5W-40', 'price' => 'Rp 150.000', 'cat' => 'Oils', 'img' => 'https://images.unsplash.com/photo-1596450650993-9c8a9466e30a?auto=format&fit=crop&w=500&q=60'],
    ['name' => 'Ceramic Brake Pads', 'price' => 'Rp 350.000', 'cat' => 'Parts', 'img' => 'https://images.unsplash.com/photo-1486262715619-38057a16d761?auto=format&fit=crop&w=500&q=60'],
    ['name' => 'High Perf. Air Filter', 'price' => 'Rp 75.000', 'cat' => 'Engine', 'img' => 'https://images.unsplash.com/photo-1549419163-fdf90e32230b?auto=format&fit=crop&w=500&q=60'],
    ['name' => 'Iridium Spark Plugs', 'price' => 'Rp 120.000', 'cat' => 'Ignition', 'img' => 'https://images.unsplash.com/photo-1626442686150-b8f44773c52a?auto=format&fit=crop&w=500&q=60'],
];

$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$user_name = $_SESSION['user_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - InfoMotive</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
</head>
<body class="dashboard-body">

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-car-burst" style="color: var(--primary-blue);"></i> InfoMotive
            </div>
            <nav>
                <a href="index.php" class="menu-item"><i class="fa-solid fa-home"></i> Home</a>
                <a href="#" class="menu-item active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="#" class="menu-item"><i class="fa-solid fa-wrench"></i> Services</a>
                <a href="#" class="menu-item"><i class="fa-solid fa-cart-shopping"></i> Spare Parts</a>
                <a href="#" class="menu-item"><i class="fa-solid fa-comments"></i> Chat</a>
                
                <div style="margin-top: auto;">
                    <?php if($is_admin): ?>
                         <a href="admin/index.php" class="menu-item" style="color: var(--primary-blue);"><i class="fa-solid fa-user-shield"></i> Admin Panel</a>
                         <a href="includes/logout.php" class="menu-item" style="color: #ff6b6b;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    <?php else: ?>
                        <a href="auth/login.php" class="menu-item"><i class="fa-solid fa-right-to-bracket"></i> Login Admin</a>
                    <?php endif; ?>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="dashboard-header">
                <div class="search-bar">
                    <i class="fa-solid fa-search" style="color: #aaa;"></i>
                    <input type="text" placeholder="Search parts..." style="border:none; outline:none; margin-left:10px; color:#555;">
                </div>
                <div class="user-profile">
                    <span><?= htmlspecialchars($user_name) ?></span>
                    <div class="user-avatar">
                        <?= $user_name === 'Guest' ? 'G' : strtoupper(substr($user_name, 0, 1)) ?>
                    </div>
                </div>
            </header>

            <!-- Map Section -->
            <section class="dashboard-section">
                <div class="section-flex">
                    <h3 class="section-title-dash">Workshop Location</h3>
                    <button class="btn btn-primary btn-sm"><i class="fa-solid fa-location-arrow"></i> Get Directions</button>
                </div>
                <div id="map"></div>
            </section>

            <!-- Products Grid -->
            <section>
                <div class="section-flex">
                    <h3 class="section-title-dash">Featured Products</h3>
                    <a href="#" style="color: var(--primary-blue); text-decoration: none; font-size: 0.9em;">View All</a>
                </div>
                
                <div class="products-grid">
                    <?php foreach($products as $p): ?>
                    <div class="product-card">
                        <div class="product-img">
                            <img src="<?= $p['img'] ?>" alt="<?= $p['name'] ?>">
                        </div>
                        <div class="product-details">
                            <div class="product-cat"><?= $p['cat'] ?></div>
                            <h4 class="product-name"><?= $p['name'] ?></h4>
                            <div class="product-price"><?= $p['price'] ?></div>
                            <button class="btn btn-primary btn-sm">Add to Cart</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Initialize Map
        var map = L.map('map').setView([-6.2088, 106.8456], 13); // Jakarta Coordinates

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        L.marker([-6.2088, 106.8456]).addTo(map)
            .bindPopup("<b>InfoMotive HQ</b><br>Main Workshop Center")
            .openPopup();
    </script>
</body>
</html>
