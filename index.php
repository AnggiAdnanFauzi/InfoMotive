<?php
require_once 'config/database.php';

// Fetch Latest Articles
$stmtArt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 3");
$latest_articles = $stmtArt ? $stmtArt->fetchAll() : [];

// Fetch Top Products
$stmtProd = $pdo->query("SELECT * FROM products ORDER BY views DESC, id ASC LIMIT 4");
$top_products = $stmtProd ? $stmtProd->fetchAll() : [];

// Fetch Top Workshops
$stmtWork = $pdo->query("SELECT * FROM workshops LIMIT 3");
$top_workshops = $stmtWork ? $stmtWork->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfoMotive - Standar Baru Layanan Otomotif Digital</title>
    <!-- Cache Busting for Style.css -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #0a0a0a;
            color: #ffffff;
            margin: 0;
            font-family: 'Inter', sans-serif, -apple-system, BlinkMacSystemFont;
            overflow-x: hidden;
            border-top: 3px solid #00AEEF;
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
        .navbar-custom .container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
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

        /* Hero Section */
        .hero-section {
            padding: 160px 50px 100px 50px;
            min-height: 80vh;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at 80% 20%, rgba(0, 174, 239, 0.12) 0%, transparent 40%),
                        radial-gradient(circle at 20% 80%, rgba(0, 229, 255, 0.08) 0%, transparent 40%);
            position: relative;
        }
        .hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 100px;
            color: #00E5FF;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
            background: linear-gradient(180deg, #FFFFFF 0%, #a1a1aa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-sub {
            font-size: 1.8rem;
            color: #00E5FF;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
        .hero-desc {
            font-size: 1.1rem;
            color: #a1a1aa;
            line-height: 1.7;
            max-width: 750px;
            margin-bottom: 50px;
            padding-left: 20px;
            border-left: 3px solid #00E5FF;
        }

        /* Features Section */
        .section-padding {
            padding: 100px 50px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .section-tag {
            color: #00E5FF;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }
        .section-title-main {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 60px;
            letter-spacing: -1px;
        }

        .features-grid-custom {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .feature-card-custom {
            background: #121214;
            border: 1px solid #222;
            border-radius: 16px;
            padding: 40px 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .feature-card-custom:hover {
            border-color: #00E5FF;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 229, 255, 0.1);
        }
        .feature-icon-box {
            width: 65px;
            height: 65px;
            background: rgba(0, 229, 255, 0.1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: #00E5FF;
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }
        .feature-card-custom:hover .feature-icon-box {
            background: #00E5FF;
            color: #0a0a0a;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        }
        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
        }
        .feature-text {
            color: #a1a1aa;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Latest Articles Section */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }
        .art-card-landing {
            background: #121214;
            border: 1px solid #222;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .art-card-landing:hover {
            border-color: #00AEEF;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 174, 239, 0.15);
        }
        .art-img-box {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        .art-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .art-card-landing:hover .art-img-box img {
            transform: scale(1.05);
        }
        .art-tag {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(8px);
            color: #00E5FF;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid rgba(0, 229, 255, 0.2);
            text-transform: uppercase;
        }
        .art-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .art-date {
            color: #71717a;
            font-size: 0.85rem;
            margin-bottom: 12px;
        }
        .art-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
            line-height: 1.4;
            text-decoration: none;
        }
        .art-title:hover { color: #00E5FF; }
        .art-desc {
            color: #a1a1aa;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 25px;
            flex: 1;
        }
        .art-link {
            color: #00E5FF;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .art-link:hover { gap: 12px; }

        /* Top Products Preview */
        .products-showcase {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        .prod-card-landing {
            background: #121214;
            border: 1px solid #222;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .prod-card-landing:hover {
            border-color: #00E5FF;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 229, 255, 0.15);
        }
        .prod-img {
            height: 160px;
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .prod-cat {
            color: #71717a;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .prod-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        .prod-price {
            font-size: 1.2rem;
            font-weight: 800;
            color: #00E5FF;
            margin-bottom: 20px;
        }
        .btn-prod-check {
            display: inline-block;
            width: 100%;
            padding: 10px 0;
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 8px;
            color: #00E5FF;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .btn-prod-check:hover {
            background: #00E5FF;
            color: #0a0a0a;
            box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);
        }

        /* Workshops Section */
        .workshops-showcase {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .work-card-landing {
            background: #121214;
            border: 1px solid #222;
            border-radius: 16px;
            padding: 35px 30px;
            transition: all 0.3s ease;
            position: relative;
        }
        .work-card-landing:hover {
            border-color: #00E5FF;
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 229, 255, 0.1);
        }
        .work-icon {
            width: 50px;
            height: 50px;
            background: rgba(0, 229, 255, 0.1);
            color: #00E5FF;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }
        .work-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
        }
        .work-addr {
            color: #a1a1aa;
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .work-phone {
            color: #00E5FF;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* CTA Banner */
        .cta-container {
            padding: 60px 50px;
            max-width: 1200px;
            margin: 50px auto 100px auto;
            background: linear-gradient(135deg, #00AEEF 0%, #00E5FF 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 20px 40px rgba(0, 229, 255, 0.25);
        }
        .cta-left h2 {
            font-size: 2.5rem;
            font-weight: 900;
            color: #0a0a0a;
            margin: 0 0 15px 0;
            letter-spacing: -1px;
        }
        .cta-left p {
            color: #1c1c22;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 500;
        }
        .cta-btn {
            padding: 16px 36px;
            background: #0a0a0a;
            color: #00E5FF;
            text-decoration: none;
            font-size: 1.05rem;
            font-weight: 700;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }
        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
            color: white;
        }

        /* Robust Footer */
        .footer-custom {
            background: #0d0d0f;
            border-top: 1px solid #1a1a1f;
            padding: 80px 50px 40px 50px;
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

        @media (max-width: 992px) {
            .hero-title { font-size: 3.2rem; }
            .cta-container { flex-direction: column; text-align: center; gap: 30px; }
        }
    </style>
</head>
<body>

    <!-- Glassmorphism Navbar -->
    <nav class="navbar-custom">
        <div class="container">
            <a href="index.php" class="brand-logo">Info<span>Motive</span></a>
            <div class="nav-links-custom">
                <a href="index.php" class="active">Home</a>
                <a href="about.php">About</a>
                <a href="edukasi.php">Edukasi</a>
                <a href="harga.php">Harga</a>
                <a href="bengkel.php">Bengkel</a>
                <a href="auth/login.php" class="btn-login-custom">Login Admin</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-inner">
            <div class="hero-badge"><i class="fa-solid fa-car-burst"></i> PLATFORM OTOMOTIF #1 INDONESIA</div>
            <h1 class="hero-title">Info<span style="color: #00E5FF;">Motive</span></h1>
            <div class="hero-sub">Standar Baru Layanan Otomotif Digital.</div>
            <p class="hero-desc">
                Dari memahami istilah teknis hingga menemukan bengkel dengan rating terbaik, Infomotive menyediakan data terpusat untuk estimasi harga spare part dan layanan servis yang transparan. Ambil keputusan tepat untuk performa kendaraan yang optimal.
            </p>
            <div style="display: flex; gap: 20px;">
                <a href="edukasi.php" style="padding: 14px 32px; background: #00E5FF; color: #0a0a0a; font-weight: 700; border-radius: 8px; text-decoration: none; box-shadow: 0 4px 20px rgba(0, 229, 255, 0.3); transition: all 0.2s;">Mulai Eksplorasi</a>
                <a href="harga.php" style="padding: 14px 32px; background: rgba(255, 255, 255, 0.05); color: white; font-weight: 600; border-radius: 8px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.2s;">Cek Harga Barang</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="section-padding" id="features">
        <span class="section-tag">// MENGAPA INFOMOTIVE //</span>
        <h2 class="section-title-main">Why Choose Us.</h2>
        
        <div class="features-grid-custom">
            <div class="feature-card-custom">
                <div class="feature-icon-box"><i class="fa-solid fa-fan"></i></div>
                <div class="feature-title">Edukasi Terpadu</div>
                <div class="feature-text">Pahami kendaraan Anda dengan artikel teknis, tips perawatan harian, dan panduan keselamatan yang mudah dimengerti.</div>
            </div>
            <div class="feature-card-custom">
                <div class="feature-icon-box"><i class="fa-solid fa-tags"></i></div>
                <div class="feature-title">Harga Barang Transparan</div>
                <div class="feature-text">Estimasi harga suku cadang dan oli mesin terpercaya untuk menghindari kecurangan dan merencanakan budget servis yang tepat.</div>
            </div>
            <div class="feature-card-custom">
                <div class="feature-icon-box"><i class="fa-solid fa-wrench"></i></div>
                <div class="feature-title">Bengkel Pilihan</div>
                <div class="feature-text">Temukan bengkel terverifikasi dengan lokasi dan reputasi terbaik di sekitar Anda, lengkap dengan peta dan nomor darurat 24 jam.</div>
            </div>
        </div>
    </div>

    <!-- Latest Articles Section -->
    <?php if (!empty($latest_articles)): ?>
    <div class="section-padding" style="background: #0d0d0f; border-top: 1px solid #1a1a1f; border-bottom: 1px solid #1a1a1f;">
        <span class="section-tag">// UPDATE TERBARU //</span>
        <h2 class="section-title-main">Edukasi & Berita Terkini</h2>
        
        <div class="articles-grid">
            <?php foreach($latest_articles as $art): ?>
            <div class="art-card-landing">
                <div class="art-img-box">
                    <span class="art-tag"><?= htmlspecialchars($art['category']) ?></span>
                    <img src="<?= htmlspecialchars($art['image']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                </div>
                <div class="art-content">
                    <div class="art-date"><?= date('d F Y', strtotime($art['created_at'])) ?></div>
                    <a href="detail_artikel.php?id=<?= $art['id'] ?>" class="art-title"><?= htmlspecialchars($art['title']) ?></a>
                    <div class="art-desc"><?= mb_strimwidth(strip_tags($art['content']), 0, 110, "...") ?></div>
                    <a href="detail_artikel.php?id=<?= $art['id'] ?>" class="art-link">Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="edukasi.php" style="color: #00E5FF; text-decoration: none; font-weight: 700; border: 1px solid #00E5FF; padding: 12px 30px; border-radius: 8px; transition: all 0.2s;">Lihat Semua Artikel</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Top Products Showcase -->
    <?php if (!empty($top_products)): ?>
    <div class="section-padding">
        <span class="section-tag">// KATALOG POPULER //</span>
        <h2 class="section-title-main">Suku Cadang & Oli Terlaris</h2>
        
        <div class="products-showcase">
            <?php foreach($top_products as $prod): ?>
            <div class="prod-card-landing">
                <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="prod-img">
                <div class="prod-cat"><?= htmlspecialchars($prod['category']) ?> &bull; <?= htmlspecialchars($prod['vehicle_type']) ?></div>
                <div class="prod-name"><?= htmlspecialchars($prod['name']) ?></div>
                <div class="prod-price">Rp <?= number_format($prod['price_min'], 0, ',', '.') ?></div>
                <a href="harga.php?search=<?= urlencode($prod['name']) ?>" class="btn-prod-check">Bandingkan Harga</a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="harga.php" style="color: #00E5FF; text-decoration: none; font-weight: 700; border: 1px solid #00E5FF; padding: 12px 30px; border-radius: 8px; transition: all 0.2s;">Jelajahi Semua Barang</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Top Workshops Showcase -->
    <?php if (!empty($top_workshops)): ?>
    <div class="section-padding" style="background: #0d0d0f; border-top: 1px solid #1a1a1f; border-bottom: 1px solid #1a1a1f;">
        <span class="section-tag">// MITRA TERPERCAYA //</span>
        <h2 class="section-title-main">Bengkel Rekomendasi Kami</h2>
        
        <div class="workshops-showcase">
            <?php foreach($top_workshops as $w): ?>
            <div class="work-card-landing">
                <div class="work-icon"><i class="fa-solid fa-car-tools"></i></div>
                <div class="work-name"><?= htmlspecialchars($w['name']) ?></div>
                <div class="work-addr"><i class="fa-solid fa-location-dot" style="color: #71717a; margin-top: 3px;"></i> <?= htmlspecialchars($w['address']) ?></div>
                <div class="work-phone"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($w['phone']) ?></div>
                <a href="bengkel.php" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;">
            <a href="bengkel.php" style="color: #00E5FF; text-decoration: none; font-weight: 700; border: 1px solid #00E5FF; padding: 12px 30px; border-radius: 8px; transition: all 0.2s;">Temukan Lokasi Bengkel Lainnya</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- CTA Banner -->
    <div class="cta-container">
        <div class="cta-left">
            <h2>Kendarai Masa Depan Perawatan Otomotif.</h2>
            <p>Dapatkan estimasi biaya servis akurat dan panduan teknis cerdas sekarang juga.</p>
        </div>
        <a href="edukasi.php" class="cta-btn">Mulai Eksplorasi <i class="fa-solid fa-rocket"></i></a>
    </div>

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

    <!-- Chatbot Module -->
    <?php include 'includes/chatbot_modal.php'; ?>

    <script src="assets/js/script.js"></script>
</body>
</html>
