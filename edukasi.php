<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Build Query based on Filters
$where = "1=1";
$params = [];

$search = $_GET['search'] ?? '';
if ($search) {
    $where .= " AND (title LIKE ? OR content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$filter_cat = $_GET['category'] ?? '';
if ($filter_cat) {
    $where .= " AND category = ?";
    $params[] = $filter_cat;
}

$filter_type = $_GET['vehicle_type'] ?? '';
if ($filter_type) {
    $where .= " AND vehicle_type = ?";
    $params[] = $filter_type;
}

$stmt = $pdo->prepare("SELECT * FROM articles WHERE $where ORDER BY created_at DESC");
$stmt->execute($params);
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edukasi - InfoMotive</title>
    <title>Edukasi - InfoMotive</title>
    <!-- Cache Busting for Style.css -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
                <a href="edukasi.php" class="active">Edukasi</a>
                <a href="harga.php">Harga</a>
                <a href="bengkel.php">Bengkel</a>
                <a href="auth/login.php" class="btn-login-custom">Login Admin</a>
            </div>
        </div>
    </nav>

    <div class="container" style="padding-bottom: 80px;">
        
        <!-- Page Title -->
        <h1 class="page-title">Edukasi</h1>

        <!-- Filter & Search Bar -->
        <div class="filter-bar">
            <form method="GET" action="" style="display: contents;">
                <div class="filter-left">
                    <select name="category" class="custom-select" onchange="this.form.submit()">
                        <option value="">Pilih Kategori..</option>
                        <option value="Tips & Trik" <?= ($_GET['category'] ?? '') == 'Tips & Trik' ? 'selected' : '' ?>>Tips & Trik</option>
                        <option value="Maintenance" <?= ($_GET['category'] ?? '') == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="Safety" <?= ($_GET['category'] ?? '') == 'Safety' ? 'selected' : '' ?>>Safety</option>
                        <option value="News" <?= ($_GET['category'] ?? '') == 'News' ? 'selected' : '' ?>>News</option>
                    </select>

                    <select name="vehicle_type" class="custom-select" onchange="this.form.submit()">
                        <option value="">Pilih Kendaraan..</option>
                        <option value="mobil" <?= ($_GET['vehicle_type'] ?? '') == 'mobil' ? 'selected' : '' ?>>Mobil</option>
                        <option value="motor" <?= ($_GET['vehicle_type'] ?? '') == 'motor' ? 'selected' : '' ?>>Motor</option>
                        <option value="umum" <?= ($_GET['vehicle_type'] ?? '') == 'umum' ? 'selected' : '' ?>>Umum</option>
                    </select>
                </div>

                <div class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" class="search-icon-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>

        <!-- Article Grid -->
        <div class="article-grid">
            <?php foreach($articles as $a): ?>
            <div class="article-card" onclick="trackView('article', <?= $a['id'] ?>)">
                <!-- Image -->
                <?php if (!empty($a['image'])): ?>
                <img src="<?= htmlspecialchars($a['image']) ?>" alt="<?= htmlspecialchars($a['title']) ?>" class="article-img">
                <?php endif; ?>

                <!-- Date -->
                <span class="article-date"><?= date('d F Y', strtotime($a['created_at'])) ?></span>
                
                <!-- Title -->
                <h3 class="article-title"><?= htmlspecialchars($a['title']) ?></h3>
                
                <!-- Description -->
                <p class="article-desc">
                    <?= substr(strip_tags($a['content']), 0, 120) ?>...
                </p>
                
                <!-- Link -->
                <a href="detail_artikel.php?id=<?= $a['id'] ?>" class="read-more-link">Baca Selengkapnya</a>
            </div>
            <?php endforeach; ?>
            <?php if(empty($articles)) echo "<p style='grid-column: 1/-1; text-align: center;'>Belum ada artikel.</p>"; ?>
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

    <script>
    function trackView(type, id) {
        fetch('api/track_view.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({type: type, id: id})
        }).then(res => res.json()).then(data => console.log('View Tracked:', data));
    }
    </script>
</body>
</html>
