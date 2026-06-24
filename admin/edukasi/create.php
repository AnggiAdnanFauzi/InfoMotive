<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) { redirect('../../auth/login.php'); }

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content']; 
    $video_url = $_POST['video_url'];
    $vehicle_type = $_POST['vehicle_type'];
    
    // Simple Image Upload 
    $image = 'https://via.placeholder.com/500'; 
    if (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
        $image = $_POST['image_url'];
    }

    $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, image, video_url, vehicle_type) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $category, $content, $image, $video_url, $vehicle_type])) {
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
    <title>Tambah Edukasi</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        /* ... existing styles ... */
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
        <aside class="sidebar">
            <div class="sidebar-header" style="padding: 20px;"><h2>Admin Panel</h2></div>
            <nav style="padding: 0 10px;">
                <a href="../index.php" class="menu-item"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="index.php" class="menu-item active"><i class="fa-solid fa-graduation-cap"></i> Admin Edukasi</a>
            </nav>
        </aside>

        <main class="admin-content">
            <h2>Tambah Artikel</h2>
            <div class="form-card">
                <form method="POST">
                    <div class="form-group">
                        <label>Judul Artikel</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category" class="form-control">
                            <option>Tips & Trik</option>
                            <option>Maintenance</option>
                            <option>Safety</option>
                            <option>News</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image URL (Thumbnail)</label>
                        <input type="text" name="image_url" class="form-control" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label>Jenis Kendaraan</label>
                        <select name="vehicle_type" class="form-control">
                            <option value="umum">Umum (General)</option>
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Video URL (Youtube Optional)</label>
                        <input type="text" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                    </div>
                    <div class="form-group">
                        <label>Isi Artikel (Tutorial/Langkah-langkah)</label>
                        <textarea name="content" id="summernote" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Simpan Artikel</button>
                    <a href="index.php" style="margin-left: 10px; color: #666;">Batal</a>
                </form>
            </div>
        </main>
    </div>

    <!-- Summernote JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $('#summernote').summernote({
            placeholder: 'Tulis tutorial anda di sini... (Bisa drag & drop gambar)',
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
