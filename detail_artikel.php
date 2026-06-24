<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) { redirect('edukasi.php'); }

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) { redirect('edukasi.php'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - InfoMotive</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white; /* Clean White Background */
            color: #333;
            font-family: 'Inter', sans-serif;
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

        body { padding-top: 80px; }

        /* --- CONTENT CONTAINER --- */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* --- HEADER & BACK BUTTON --- */
        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            color: #00E5FF; /* Cyan Title */
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: block;
        }

        .btn-back-arrow {
            color: #000;
            font-size: 2.5rem;
            text-decoration: none;
            transition: transform 0.2s;
            display: inline-block;
        }
        .btn-back-arrow:hover {
            transform: translateX(-5px);
            color: #333;
        }

        /* --- ARTICLE CARD --- */
        .article-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08); /* Soft Shadow */
            border: 1px solid #eee;
            padding: 50px;
        }

        .article-headline {
            font-size: 1.8rem;
            font-weight: 800;
            color: #222;
            margin-bottom: 5px;
        }

        .article-date {
            color: #00E5FF; /* Cyan date */
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: block;
        }

        .article-img {
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: block;
        }

        /* Raw HTML Content Styling */
        .article-body {
            color: #444;
            line-height: 1.8;
            font-size: 1.05rem;
        }
        
        .article-body p { margin-bottom: 20px; }
        .article-body strong { color: #222; font-weight: 700; }
        .article-body img { max-width: 100%; height: auto; margin: 20px 0; border-radius: 6px; }
        .article-body ul, .article-body ol { margin-left: 20px; margin-bottom: 20px; }
        .article-body li { margin-bottom: 10px; }

    </style>
</head>
<body>

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

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Edukasi</h1>
            <a href="edukasi.php" class="btn-back-arrow"><i class="fa-solid fa-arrow-left-long"></i></a> <!-- Long Arrow -->
        </div>

        <!-- Article Content Card -->
        <div class="article-card">
            <h2 class="article-headline"><?= htmlspecialchars($article['title']) ?></h2>
            <span class="article-date"><?= date('d F Y', strtotime($article['created_at'])) ?></span>

            <?php if($article['image']): ?>
            <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-img">
            <?php endif; ?>

            <div class="article-body">
                <!-- Raw HTML Content -->
                <?= $article['content'] ?>
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

</body>
</html>
