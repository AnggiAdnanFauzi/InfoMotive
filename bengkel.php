<?php
require_once 'config/database.php';

// Build Query
$where = "1=1";
$params = [];

$search = $_GET['search'] ?? '';
if ($search) {
    $where .= " AND (name LIKE ? OR address LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare("SELECT * FROM workshops WHERE $where ORDER BY name ASC");
$stmt->execute($params);
$workshops = $stmt->fetchAll();

// FALLBACK: If DB is empty, use Mock Data
if (empty($workshops) && empty($search)) {
    $workshops = [
        ['name' => 'Seven Auto Service [Bengkel 24 Jam Bandung]', 'lat' => -6.953, 'lng' => 107.653, 'address' => 'Jl. Terusan Buah Batu, Bandung'],
        ['name' => 'Championship Motor', 'lat' => -6.917, 'lng' => 107.609, 'address' => 'Jl. Jendral Sudirman No. 45, Bandung'],
        ['name' => 'Bengkel Mobil Guna Lancar', 'lat' => -6.945, 'lng' => 107.650, 'address' => 'Jl. Soekarno Hatta No. 100, Bandung'],
        ['name' => 'Bengkel mobil abeng panggilan darurat', 'lat' => -6.930, 'lng' => 107.590, 'address' => 'Jl. Peta No. 20, Bandung'],
        ['name' => 'Prima Motor', 'lat' => -6.950, 'lng' => 107.595, 'address' => 'Jl. Cibaduyut Lama, Bandung'],
        ['name' => 'Bengkel Mobil AJM Auto Service -- Tau Beres Aja', 'lat' => -6.960, 'lng' => 107.580, 'address' => 'Jl. Kopo Sayati, Bandung'],
        ['name' => 'Bengkel Motor Saung Pajajaran dan press body motor', 'lat' => -6.905, 'lng' => 107.590, 'address' => 'Jl. Pajajaran No. 88, Bandung'],
        ['name' => 'Bengkel motor sadiq', 'lat' => -6.890, 'lng' => 107.615, 'address' => 'Jl. Dipati Ukur, Bandung']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel - InfoMotive</title>
    <!-- Cache Busting -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        /* Custom Bengkel Card */
        .bengkel-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; margin-top: 40px; }
        .bengkel-card { background: white; border-radius: 8px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid #f0f0f0; display: flex; flex-direction: column; height: 100%; }
        .bengkel-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .bengkel-title { font-size: 1.1rem; font-weight: 700; color: #222; margin-bottom: 8px; min-height: 50px; }
        .rating-stars { color: #FFC107; font-size: 0.85rem; margin-bottom: 10px; }
        .bengkel-type { font-size: 0.8rem; color: #888; margin-bottom: 20px; }
        .map-link { color: #00E5FF; font-weight: 600; text-decoration: none; font-size: 0.9rem; margin-top: auto; cursor: pointer; }

        /* Full Page Map View Styles */
        #map-view-container { display: none; margin-top: 20px; position: relative; height: 70vh; width: 100%; }
        #full-map { width: 100%; height: 100%; border-radius: 12px; z-index: 1; }
        .back-arrow { font-size: 40px; color: black; cursor: pointer; margin-bottom: 20px; transition: 0.2s; display: inline-block; }
        .back-arrow:hover { transform: translateX(-5px); color: #00AEEF; }
        
        /* Floating Card on Map */
        .map-overlay-card {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 1000;
            width: 300px;
            text-align: center;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px;
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            z-index: 1000;
        }
        .navbar-custom .container-nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            box-sizing: border-box;
        }
        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .brand-logo span { color: #00E5FF; }
        
        .nav-links-custom {
            display: flex;
            align-items: center;
            gap: 35px;
        }
        .nav-links-custom a {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-links-custom a:hover, .nav-links-custom a.active {
            color: #00E5FF;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.4);
        }
        .btn-login-custom {
            background: linear-gradient(135deg, #00AEEF 0%, #00E5FF 100%);
            color: #0a0a0a !important;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);
            transition: all 0.3s ease;
        }
        .btn-login-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 229, 255, 0.5);
        }

        /* Robust Footer */
        .footer-custom {
            background: #0d0d0f;
            border-top: 1px solid #1a1a1f;
            padding: 80px 50px 40px 50px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            text-align: left;
            margin-top: 80px;
        }
        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            margin-bottom: 60px;
        }
        .footer-brand h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin: 0 0 20px 0;
        }
        .footer-brand h3 span { color: #00E5FF; }
        .footer-brand p {
            color: #71717a;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }
        .footer-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin: 0 0 25px 0;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }
        .footer-links a:hover { color: #00E5FF; }
        
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid #1a1a1f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #71717a;
            font-size: 0.85rem;
        }
        .social-icons {
            display: flex;
            gap: 20px;
        }
        .social-icons a {
            color: #71717a;
            font-size: 1.2rem;
            transition: color 0.2s ease;
        }
        .social-icons a:hover { color: #00E5FF; }

        body.page-light { padding-top: 80px; }
    </style>
</head>
<body class="page-light">
    <!-- Glassmorphism Navbar -->
    <nav class="navbar-custom">
        <div class="container-nav">
            <a href="index.php" class="brand-logo">Info<span>Motive</span></a>
            <div class="nav-links-custom">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="edukasi.php">Edukasi</a>
                <a href="harga.php">Harga</a>
                <a href="bengkel.php" class="active">Bengkel</a>
                <a href="auth/login.php" class="btn-login-custom">Login Admin</a>
            </div>
        </div>
    </nav>

    <div class="container" style="padding-bottom: 80px;">
        
        <!-- Page Title -->
        <h1 class="page-title" style="color: #00E5FF;">Bengkel</h1>

        <!-- LIST VIEW CONTAINER -->
        <div id="list-view">
            <!-- Filter & Search Bar -->
            <div class="filter-bar">
                <form method="GET" action="" style="display: contents;">
                    <div class="filter-left">
                        <select class="custom-select" disabled style="opacity: 0.6; cursor: not-allowed;">
                            <option>Pilih Kendaraan..</option>
                        </select>
                    </div>

                    <div class="search-container" style="width: 400px; background: #e0e0e0; border-radius: 4px; border: 1px solid #ccc;">
                         <input type="text" name="search" class="search-input" style="background: transparent; border: none;" placeholder="Search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                         <button type="submit" class="search-icon-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>

            <!-- Workshops Grid -->
            <div class="bengkel-grid">
                <?php foreach($workshops as $w): 
                    $rating = number_format(rand(35, 50) / 10, 1);
                    $reviews = rand(10, 200);
                ?>
                <div class="bengkel-card">
                    <h3 class="bengkel-title"><?= htmlspecialchars($w['name']) ?></h3>
                    
                    <div class="rating-stars">
                        <?= $rating ?> 
                        <?php 
                        $stars = round($rating);
                        for($i=0; $i<$stars; $i++) echo '<i class="fa-solid fa-star"></i>';
                        for($i=$stars; $i<5; $i++) echo '<i class="fa-regular fa-star"></i>';
                        ?>
                        <span style="color: #ccc; font-size: 0.8rem;">(<?= $reviews ?>)</span>
                    </div>

                    <div class="bengkel-type">
                        Bengkel Umum <i class="fa-solid fa-wrench" style="color: #00AEEF; margin-left: 5px;"></i>
                    </div>

                    <a onclick="showFullMap(<?= $w['lat'] ?>, <?= $w['lng'] ?>, '<?= addslashes($w['name']) ?>', '<?= addslashes($w['address']) ?>')" class="map-link">Lihat Map</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- FULL PAGE MAP VIEW -->
        <div id="map-view-container">
            <i class="fa-solid fa-arrow-left back-arrow" onclick="showList()"></i>
            
            <div id="full-map"></div>

            <!-- Overlay Card -->
            <div class="map-overlay-card">
                <h3 id="overlay-title" style="margin-bottom: 10px;">Bengkel Name</h3>
                <p id="overlay-address" style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Address Here</p>
                <button class="btn btn-primary btn-sm" onclick="window.open('https://maps.google.com', '_blank')">Buka di Google Maps</button>
            </div>
        </div>

    </div>

    <!-- Chatbot Module -->
    <?php include 'includes/chatbot_modal.php'; ?>

    <!-- Robust Footer -->
    <footer class="footer-custom">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>Info<span>Motive</span></h3>
                <p>Platform otomotif digital terdepan di Indonesia yang menghadirkan transparansi harga suku cadang, edukasi perawatan mendalam, dan direktori bengkel terverifikasi.</p>
            </div>
            <div>
                <h4 class="footer-heading">Navigasi Utama</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="edukasi.php">Edukasi Otomotif</a></li>
                    <li><a href="harga.php">Harga Barang</a></li>
                    <li><a href="bengkel.php">Direktori Bengkel</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Layanan Pengguna</h4>
                <ul class="footer-links">
                    <li><a href="auth/login.php">Portal Admin</a></li>
                    <li><a href="edukasi.php?category=Tips+%26+Trik">Tips & Trik</a></li>
                    <li><a href="edukasi.php?category=Maintenance">Jadwal Perawatan</a></li>
                    <li><a href="edukasi.php?category=Safety">Panduan Safety</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Kontak & Dukungan</h4>
                <p style="color: #71717a; font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">Kami hadir 24/7 untuk menjawab kebutuhan teknis kendaraan Anda melalui asisten pintar kami.</p>
                <div style="color: #00E5FF; font-weight: 700; font-size: 1.1rem;"><i class="fa-solid fa-headset"></i> CS@infomotive.id</div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>&copy; 2026 InfoMotive. All rights reserved.</div>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = null;
        var marker = null;

        function showFullMap(lat, lng, name, address) {
            // Hide List, Show Map
            document.getElementById('list-view').style.display = 'none';
            document.getElementById('map-view-container').style.display = 'block';
            
            // Update Overlay Info
            document.getElementById('overlay-title').innerText = name;
            document.getElementById('overlay-address').innerText = address;

            // Initialize Map if needed
            if (!map) {
                map = L.map('full-map');
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
            }

            // Force Resize logic
            setTimeout(function() {
                map.invalidateSize();
                map.setView([lat, lng], 15);

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup("<b>" + name + "</b>").openPopup();
            }, 100);
        }

        function showList() {
            document.getElementById('map-view-container').style.display = 'none';
            document.getElementById('list-view').style.display = 'block';
        }
    </script>
</body>
</html>
