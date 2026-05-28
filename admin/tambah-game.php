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
    <title>Tambah Game - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-color: #ecf0f1;">
    <div class="container" style="max-width: 600px; margin-top: 40px;">
        <div class="form-container" style="max-width: 100%;">
            <h2>Tambah Board Game Baru</h2>
            <br>
            <form action="tambah-game.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Game</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Genre</label>
                    <input type="text" name="genre" placeholder="Cth: Strategi, Party, Kartu" required>
                </div>
                <div class="form-group">
                    <label>Jumlah Pemain</label>
                    <input type="text" name="pemain" placeholder="Cth: 2-4 Players" required>
                </div>
                <div class="form-group">
                    <label>Durasi Bermain</label>
                    <input type="text" name="durasi" placeholder="Cth: 30-60 Min" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi & Cara Main Singkat</label>
                    <textarea name="deskripsi" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Gambar Game</label>
                    <input type="file" name="gambar" accept="image/png, image/jpeg, image/jpg">
                </div>
                <button type="submit" class="btn" style="background-color: #27ae60;">Simpan Game</button>
                <a href="games.php" style="display: block; text-align: center; margin-top: 15px; color: #7f8c8d;">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>