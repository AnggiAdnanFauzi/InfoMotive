<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// STRICT: Admin Only Access
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// 1. Fetch Counts
$count_articles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$count_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$count_workshops = $pdo->query("SELECT COUNT(*) FROM workshops")->fetchColumn();

// 2. Fetch Top Stats for Charts (Truncated Labels)
$top_articles = $pdo->query("SELECT title, views FROM articles ORDER BY views DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$art_labels_raw = array_column($top_articles, 'title');
$art_labels = json_encode(array_map(function($t) { return mb_strimwidth($t, 0, 20, "..."); }, $art_labels_raw));
$art_views = json_encode(array_column($top_articles, 'views'));

$top_products = $pdo->query("SELECT name, views FROM products ORDER BY views DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$prod_labels_raw = array_column($top_products, 'name');
$prod_labels = json_encode(array_map(function($n) { return mb_strimwidth($n, 0, 20, "..."); }, $prod_labels_raw));
$prod_views = json_encode(array_column($top_products, 'views'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - InfoMotive</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: white;
            color: #333;
            overflow: hidden; /* Prevent Scroll */
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Robust Navbar */
        .navbar-admin {
            width: 100%;
            height: 60px; /* Reduced Height */
            background: #0f172a; /* Dark Blue */
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

        /* Dashboard CSS Grid Layout */
        .dashboard-container {
            flex: 1;
            padding: 15px 40px; /* Reduced Top Padding */
            display: flex;
            flex-direction: column;
            gap: 15px; /* Reduced Gap */
            height: 100%;
            overflow: hidden;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            margin-bottom: 0px; /* Tighten up */
        }
        .dashboard-title {
            color: #00E5FF;
            font-size: 1.8rem; /* Slightly smaller */
            font-weight: 800;
            margin: 0;
        }

        /* Stats Row - Compact */
        .stats-row {
            display: flex;
            gap: 20px;
            flex-shrink: 0;
        }
        .stat-card {
            flex: 1;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 15px 20px; /* Reduced vertical padding */
            text-align: center;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); border-color: #00E5FF; }
        
        .stat-info { text-align: left; }
        .stat-label { font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 5px; text-transform: uppercase; }
        .stat-number { font-size: 2rem; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-link { font-size: 0.8rem; color: #00E5FF; font-weight: 600; text-decoration: none; display: block; margin-top: 5px;}
        .stat-icon { font-size: 2.2rem; color: #e2e8f0; }

        /* Charts Row - Fills remaining height */
        .charts-row {
            flex: 1;
            display: flex;
            gap: 20px;
            min-height: 0; /* Critical for chartjs resizing */
            padding-bottom: 15px; /* Space for bottom */
            margin-bottom: 20px;
        }
        .chart-container {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }
        .chart-title {
            text-align: center;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }
        .canvas-wrapper {
            flex: 1;
            position: relative;
            min-height: 0;
        }
    </style>
</head>
<body>

    <!-- Admin Navigation -->
    <nav class="navbar-admin">
        <div class="nav-inner">
            <a href="index.php" class="active">Dashboard</a>
            <a href="../about.php">About</a>
            <a href="edukasi/index.php">Edukasi</a>
            <a href="barang/index.php">Barang</a>
            <a href="bengkel/index.php">Bengkel</a>
            <a href="../includes/logout.php" class="logout-btn">Logout Admin</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <div style="font-size: 0.9rem; color: #666;">Welcome, Admin</div>
        </div>

        <!-- 3 Stats Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">Jumlah Artikel</div>
                    <div class="stat-number"><?= $count_articles ?></div>
                    <a href="edukasi/index.php" class="stat-link">Lihat Detail &rarr;</a>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">Jumlah Barang</div>
                    <div class="stat-number"><?= $count_products ?></div>
                    <a href="barang/index.php" class="stat-link">Lihat Detail &rarr;</a>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-label">Jumlah Bengkel</div>
                    <div class="stat-number"><?= $count_workshops ?></div>
                    <a href="bengkel/index.php" class="stat-link">Lihat Detail &rarr;</a>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-store"></i></div>
            </div>
        </div>

        <!-- 2 Charts -->
        <div class="charts-row">
            <div class="chart-container">
                <h3 class="chart-title">Artikel Terpopuler</h3>
                <div class="canvas-wrapper">
                    <canvas id="articleChart"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <h3 class="chart-title">Barang Tren</h3>
                <div class="canvas-wrapper">
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Init Charts -->
    <script>
        // Article Chart
        new Chart(document.getElementById('articleChart'), {
            type: 'bar',
            data: {
                labels: <?= $art_labels ?>,
                datasets: [{
                    label: 'Jumlah Views',
                    data: <?= $art_views ?>,
                    backgroundColor: '#00AEEF',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { bottom: 20 }
                },
                scales: { 
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { 
                        grid: { display: false },
                        ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 }
                    }
                }
            }
        });

        // Product Chart
        new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: <?= $prod_labels ?>,
                datasets: [{
                    label: 'Jumlah Views',
                    data: <?= $prod_views ?>,
                    backgroundColor: '#00E5FF',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { bottom: 20 }
                },
                scales: { 
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { 
                        grid: { display: false },
                        ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 }
                    }
                }
            }
        });
    </script>
</body>
</html>
