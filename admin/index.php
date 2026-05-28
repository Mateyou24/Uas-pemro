<?php
// Memulai session
session_start();

// Memanggil koneksi database (mundur 1 folder menggunakan ../)
include '../includes/db.php';

// PROTEKSI HALAMAN: Cek apakah yang masuk benar-benar admin
// Jika tidak ada session user_id ATAU role-nya bukan admin, tendang ke login!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Mengambil data statistik untuk ditampilkan di dashboard
$total_games = $conn->query("SELECT COUNT(*) as total FROM board_games")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$total_reviews = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Board Game Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* CSS tambahan khusus layout admin */
        body { background-color: #ecf0f1; }
        .admin-nav {
            background-color: #2c3e50;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-nav .logo { color: #fff; font-weight: bold; font-size: 20px; }
        .admin-links a { color: #ecf0f1; text-decoration: none; margin-left: 20px; }
        .admin-links a:hover { color: #f1c40f; }
        
        .stats-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        .stat-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            flex: 1;
            border-top: 4px solid #3498db;
        }
        .stat-box h3 { color: #7f8c8d; font-size: 18px; margin-bottom: 10px; }
        .stat-box p { font-size: 36px; font-weight: bold; color: #2c3e50; }
    </style>
</head>
<body>

    <nav class="admin-nav">
        <div class="logo">Admin Panel - Board Game Hub</div>
        <div class="admin-links">
    <a href="index.php">Dashboard</a>
    <a href="games.php">Kelola Game</a>
    <a href="reviews.php">Kelola Ulasan</a> 
    <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 30px;">
        <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
        <p style="margin-top: 10px; color: #7f8c8d;">Gunakan panel ini untuk mengelola konten website.</p>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Board Game</h3>
                <p><?= $total_games ?></p>
            </div>
            <div class="stat-box">
                <h3>Total User</h3>
                <p><?= $total_users ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Review</h3>
                <p><?= $total_reviews ?></p>
            </div>
        </div>
    </div>

</body>
</html>