<?php
session_start();
include '../includes/db.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua ulasan, digabung (JOIN) dengan tabel board_games untuk tahu ulasan ini milik game apa
$sql = "SELECT reviews.*, board_games.nama AS nama_game 
        FROM reviews 
        JOIN board_games ON reviews.board_game_id = board_games.id 
        ORDER BY reviews.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Ulasan - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background-color: #ecf0f1; }
        .admin-nav { background-color: #2c3e50; padding: 15px 30px; display: flex; justify-content: space-between; }
        .admin-nav .logo { color: #fff; font-weight: bold; font-size: 20px; }
        .admin-links a { color: #ecf0f1; text-decoration: none; margin-left: 20px; }
        .table-admin { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        .table-admin th, .table-admin td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table-admin th { background-color: #e74c3c; color: white; }
        .btn-hapus { background-color: #c0392b; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px;}
        .stars { color: #f1c40f; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="logo">Admin Panel - Ulasan</div>
        <div class="admin-links">
            <a href="index.php">Dashboard</a>
            <a href="games.php">Kelola Game</a>
            <a href="reviews.php">Kelola Ulasan</a>
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1100px;">
        <h2>Kelola Ulasan Pengguna</h2>
        <p style="color: #7f8c8d;">Berikut adalah daftar semua ulasan yang masuk dari pengunjung website.</p>

        <table class="table-admin">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Game</th>
                    <th>Reviewer</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = $result->fetch_assoc()): 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_game']); ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_reviewer']); ?></td>
                    <td class="stars"><?= str_repeat("⭐", $row['rating']); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['komentar'])); ?></td>
                    <td><small><?= $row['created_at']; ?></small></td>
                    <td>
                        <a href="hapus-review.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus ulasan ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if($result->num_rows == 0): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada ulasan dari user.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>