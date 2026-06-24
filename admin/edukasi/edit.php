<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) { redirect('../../auth/login.php'); }

$id = $_GET['id'] ?? null;
if (!$id) { redirect('index.php'); }

// Handle Form Submission (Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $vehicle_type = $_POST['vehicle_type']; // Added
    $content = $_POST['content'];
    $image = $_POST['image_url'];
    
    // Video URL (Optional)
    $video = $_POST['video_url'] ?? '';

    $stmt = $pdo->prepare("UPDATE articles SET title = ?, category = ?, vehicle_type = ?, content = ?, image = ?, video_url = ? WHERE id = ?");
    if ($stmt->execute([$title, $category, $vehicle_type, $content, $image, $video, $id])) {
        header("Location: index.php");
        exit;
    }
}

// Fetch Existing Data
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) { echo "Artikel tidak ditemukan."; exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Edukasi - InfoMotive</title>
    <!-- Use main style -->
    <link rel="stylesheet" href="../../assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
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

        /* --- PAGE LAYOUT --- */
        .container {
            padding: 40px; 
            max-width: 100%;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
        }
        .page-title span.blue { color: #00E5FF; }
        .page-title span.gray { color: #333; font-weight: 500; font-size: 1.5rem;}

        /* --- FORM CARD --- */
        .form-card {
            background: #f4f4f4; /* Light Gray Background */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 20px;
        }

        /* Form Controls Row */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-back {
            background: white;
            border: 1px solid #ccc;
            width: 40px;
            height: 40px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-back:hover { background: #eee; }

        .btn-save {
            background: #00AEEF;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn-save:hover { background: #0095cc; }

        /* Grid System for Inputs */
        .form-row {
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
        }
        .col-half {
            flex: 1;
        }

        .form-group {
             margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #222;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd; /* Light border */
            border-radius: 4px; /* Rounded corners */
            background: #dbdbdb; /* Darker gray input background matching image */
            color: #333;
            font-size: 0.9rem;
            outline: none;
        }

        /* Summernote Fixes */
        .note-editor.note-frame {
            border: 1px solid #ddd;
            background: white;
        }
        .note-editor .note-toolbar {
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <!-- Admin Navigation (Mirrored from Dashboard) -->
    <nav class="navbar-admin">
        <div class="nav-inner">
            <a href="../index.php">Dashboard</a>
            <a href="../index.php#about">About</a>
            <a href="index.php" class="active">Edukasi</a>
            <a href="../barang/index.php">Barang</a>
            <a href="../bengkel/index.php">Bengkel</a>
            <a href="../../includes/logout.php" class="logout-btn">Logout Admin</a>
        </div>
    </nav>

    <div class="container">
        <!-- Title: Edukasi / Tambah Artikel (Edit Mode) -->
        <h1 class="page-title"><span class="blue">Edukasi</span> <span class="gray">/ Edit Artikel</span></h1>

        <div class="form-card">
            <form method="POST">
                
                <!-- Top Actions: Back & Save -->
                <div class="form-actions">
                    <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i></a>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>

                <!-- 2 Column Layout -->
                <div class="form-row">
                    <!-- Left Column -->
                    <div class="col-half">
                        <!-- Title -->
                        <div class="form-group">
                            <label>Judul Artikel</label>
                            <input type="text" name="title" class="form-control" placeholder="Masukan Judul..." value="<?= htmlspecialchars($article['title']) ?>" required>
                        </div>
                        <!-- Category -->
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category" class="form-control">
                                <option disabled>Pilih Kategori</option>
                                <option value="Tips & Trik" <?= $article['category'] == 'Tips & Trik' ? 'selected' : '' ?>>Tips & Trik</option>
                                <option value="Maintenance" <?= $article['category'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                <option value="Safety" <?= $article['category'] == 'Safety' ? 'selected' : '' ?>>Safety</option>
                                <option value="News" <?= $article['category'] == 'News' ? 'selected' : '' ?>>News</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-half">
                        <!-- Vehicle Type -->
                        <div class="form-group">
                            <label>Jenis Kendaraan</label>
                            <select name="vehicle_type" class="form-control">
                                <option disabled>Pilih Kendaraan...</option>
                                <option value="mobil" <?= $article['vehicle_type'] == 'mobil' ? 'selected' : '' ?>>Mobil</option>
                                <option value="motor" <?= $article['vehicle_type'] == 'motor' ? 'selected' : '' ?>>Motor</option>
                                <option value="umum" <?= $article['vehicle_type'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                            </select>
                        </div>
                        <!-- Image URL (Text for now, mimics file input style) -->
                        <div class="form-group">
                            <label>Foto Thumbnail (URL)</label>
                            <input type="text" name="image_url" class="form-control" placeholder="Choose File (URL)" value="<?= htmlspecialchars($article['image']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Full Width Content -->
                <div class="form-group">
                    <label>Isi Artikel</label>
                    <textarea name="content" id="summernote" class="form-control" required><?= htmlspecialchars($article['content']) ?></textarea>
                </div>

                <!-- Optional Video (Hidden from main view based on design but kept for functionality) -->
                 <div class="form-group">
                    <label>Video URL (Optional)</label>
                    <input type="text" name="video_url" class="form-control" value="<?= htmlspecialchars($article['video_url'] ?? '') ?>">
                </div>

            </form>
        </div>
    </div>

    <!-- Summernote JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $('#summernote').summernote({
            placeholder: 'Masukan Isi...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
</body>
</html>
