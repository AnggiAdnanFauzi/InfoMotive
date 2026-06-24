<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../../auth/login.php');
}

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) redirect('index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Artikel - Admin InfoMotive</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- ADMIN NAVBAR --- */
        .navbar-admin {
            width: 100%;
            height: 60px;
            background: #0f172a;
            display: flex;
            align-items: center;
            padding: 0 40px;
            flex-shrink: 0;
            z-index: 999;
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
        }
        .navbar-admin a:hover, .navbar-admin a.active { color: #00E5FF; }
        .logout-btn { color: #ff6b6b !important; }

        /* --- CONTENT --- */
        .container {
            padding: 40px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .breadcrumb {
            color: #00E5FF; /* Cyan breadcrumb parent (Edukasi) */
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
        }
        .breadcrumb span { color: #333; font-weight: 400; }

        .view-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 40px;
            position: relative;
            border: 1px solid #eee;
        }

        /* Top Controls */
        .top-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-back {
            width: 40px;
            height: 40px;
            border: 1px solid #333;
            border-radius: 4px; /* Boxy with slight radius */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            text-decoration: none;
            font-size: 1.2rem;
            transition: 0.2s;
        }
        .btn-back:hover { background: #f0f0f0; }

        .btn-edit-blue {
            background: #00AEEF;
            color: white;
            padding: 8px 25px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-edit-blue:hover { background: #0095cc; }

        /* Article Content */
        .article-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #222;
            margin-bottom: 5px;
        }

        .article-date {
            color: #00E5FF; /* Cyan date */
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: block;
        }

        .article-img {
            max-width: 400px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: block;
        }

        .article-body {
            color: #444;
            line-height: 1.6;
            font-size: 1rem;
        }
        
    </style>
</head>
<body>

    <!-- Admin Navigation -->
    <nav class="navbar-admin">
        <div class="nav-inner">
            <a href="../index.php">Dashboard</a>
            <a href="../../about.php">About</a>
            <a href="index.php" class="active">Edukasi</a>
            <a href="../barang/index.php">Barang</a>
            <a href="../bengkel/index.php">Bengkel</a>
            <a href="../../includes/logout.php" class="logout-btn">Logout Admin</a>
        </div>
    </nav>

    <div class="container">
        <!-- Breadcrumb Title -->
        <div class="breadcrumb">
            Edukasi <span style="font-weight: 400; color: #333;">/ Lihat Artikel</span>
        </div>

        <div class="view-card">
            <!-- Top Buttons -->
            <div class="top-controls">
                <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i></a> <!-- Back Arrow -->
                <a href="edit.php?id=<?= $article['id'] ?>" class="btn-edit-blue">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
            </div>

            <!-- Content -->
            <h2 class="article-title"><?= htmlspecialchars($article['title']) ?></h2>
            <span class="article-date"><?= date('d F Y', strtotime($article['created_at'])) ?></span>

            <?php if($article['image']): ?>
            <img src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image" class="article-img">
            <?php endif; ?>

            <div class="article-body">
                <?= $article['content'] ?>
            </div>

        </div>
    </div>

</body>
</html>
