<?php
session_start();
include '../includes/db.php';

// Proteksi halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data game dari database (diurutkan dari yang terbaru)
$result = $conn->query("SELECT * FROM board_games ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Game - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background-color: #ecf0f1; }
        .admin-nav { background-color: #2c3e50; padding: 15px 30px; display: flex; justify-content: space-between; }
        .admin-nav .logo { color: #fff; font-weight: bold; font-size: 20px; }
        .admin-links a { color: #ecf0f1; text-decoration: none; margin-left: 20px; }
        .table-admin { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        .table-admin th, .table-admin td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table-admin th { background-color: #3498db; color: white; }
        .btn-tambah { background-color: #27ae60; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px; }
        .btn-edit { background-color: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px;}
        .btn-hapus { background-color: #c0392b; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px;}
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="logo">Admin Panel</div>
       <div class="admin-links">
    <a href="index.php">Dashboard</a>
    <a href="games.php">Kelola Game</a>
    <a href="reviews.php">Kelola Ulasan</a> 
    <a href="../logout.php">Logout</a>
       </div>
    </nav>

    <div class="container" style="max-width: 1000px;">
        <h2>Kelola Board Game</h2>
        <br>
        <a href="tambah-game.php" class="btn-tambah">+ Tambah Game Baru</a>

        <table class="table-admin">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Game</th>
                    <th>Genre</th>
                    <th>Pemain</th>
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
                    <td>
                        <img src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>" alt="Gambar" width="50" style="border-radius: 5px; object-fit: cover;">
                    </td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['genre']); ?></td>
                    <td><?= htmlspecialchars($row['pemain']); ?></td>
                    <td>
                        <a href="edit-game.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus-game.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus game ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if($result->num_rows == 0): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada game yang ditambahkan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>