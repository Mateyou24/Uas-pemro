<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $genre = $_POST['genre'];
    $pemain = $_POST['pemain'];
    $durasi = $_POST['durasi'];
    $deskripsi = $_POST['deskripsi'];
    
    // Proses Upload Gambar
    $nama_gambar = 'default.jpg'; // Jika tidak ada gambar, gunakan default
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $folder_tujuan = "../assets/images/";
        // Bikin nama file unik supaya tidak menimpa file bernama sama
        $nama_gambar = time() . "_" . basename($_FILES["gambar"]["name"]);
        $file_tujuan = $folder_tujuan . $nama_gambar;
        
        // Pindahkan file dari tempat sementara (temp) ke folder images kita
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $file_tujuan);
    }

    // Masukkan ke database dengan prepared statement agar aman dari SQL Injection
    $stmt = $conn->prepare("INSERT INTO board_games (nama, genre, pemain, durasi, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $genre, $pemain, $durasi, $deskripsi, $nama_gambar);
    
    if ($stmt->execute()) {
        header("Location: games.php");
        exit;
    } else {
        echo "Gagal menambahkan game: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Add Game - Admin</title>

    <link rel="stylesheet" href="../assets/style-admin.css">
</head>
<body>

<nav class="admin-navbar">

    <div class="admin-logo">
        🎲 Tavern Of Meeple
    </div>

    <div class="admin-menu">
        <a href="index.php">Dashboard</a>
        <a href="games.php" class="active">Games</a>
        <a href="reviews.php">Reviews</a>
        <a href="../logout.php" class="logout">Logout</a>
    </div>

</nav>

<div class="admin-container">

    <div class="form-header">

        <span class="dashboard-tag">
            GAME MANAGEMENT
        </span>

        <h1>Add New Board Game</h1>

        <p>
            Tambahkan board game baru ke dalam koleksi website.
        </p>

    </div>

    <div class="admin-form-card">

        <form
            action="tambah-game.php"
            method="POST"
            enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama Game</label>

                <input
                    type="text"
                    name="nama"
                    placeholder="Contoh: Catan"
                    required>
            </div>

            <div class="form-group">
                <label>Genre</label>

                <input
                    type="text"
                    name="genre"
                    placeholder="Strategi, Party, Card Game"
                    required>
            </div>

            <div class="form-group">
                <label>Jumlah Pemain</label>

                <input
                    type="text"
                    name="pemain"
                    placeholder="2 - 4 Players"
                    required>
            </div>

            <div class="form-group">
                <label>Durasi Bermain</label>

                <input
                    type="text"
                    name="durasi"
                    placeholder="30 - 60 Minutes"
                    required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="6"
                    placeholder="Masukkan deskripsi singkat dan cara bermain..."
                    required></textarea>
            </div>

            <div class="form-group">

                <label>Upload Game Image</label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/png, image/jpeg, image/jpg">

                <small>
                    Format yang disarankan: JPG atau PNG.
                </small>

            </div>

            <div class="form-actions">

                <button type="submit" class="save-btn">
                    Save Game
                </button>

                <a href="games.php" class="cancel-btn">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>