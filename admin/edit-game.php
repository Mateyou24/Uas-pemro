<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

// 1. AMBIL DATA LAMA UNTUK DITAMPILKAN DI FORM
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM board_games WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
    } else {
        echo "Data game tidak ditemukan!";
        exit;
    }
} else {
    header("Location: games.php");
    exit;
}

// 2. PROSES UPDATE SAAT TOMBOL DIKLIK
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $genre = $_POST['genre'];
    $pemain = $_POST['pemain'];
    $durasi = $_POST['durasi'];
    $deskripsi = $_POST['deskripsi'];
    $nama_gambar = $game['gambar']; // Pakai gambar lama secara default

    // Cek jika admin meng-upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $folder_tujuan = "../assets/images/";
        $nama_gambar = time() . "_" . basename($_FILES["gambar"]["name"]);
        $file_tujuan = $folder_tujuan . $nama_gambar;
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $file_tujuan)) {
            // Hapus gambar lama dari server jika bukan default.jpg
            if ($game['gambar'] != 'default.jpg' && file_exists($folder_tujuan . $game['gambar'])) {
                unlink($folder_tujuan . $game['gambar']);
            }
        }
    }

    // Jalankan query UPDATE
    $stmt_update = $conn->prepare("UPDATE board_games SET nama=?, genre=?, pemain=?, durasi=?, deskripsi=?, gambar=? WHERE id=?");
    $stmt_update->bind_param("ssssssi", $nama, $genre, $pemain, $durasi, $deskripsi, $nama_gambar, $id);
    
    if ($stmt_update->execute()) {
        header("Location: games.php");
        exit;
    } else {
        echo "Gagal mengupdate game: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Game - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-color: #ecf0f1;">
    <div class="container" style="max-width: 600px; margin-top: 40px;">
        <div class="form-container" style="max-width: 100%;">
            <h2>Edit Data Board Game</h2>
            <br>
            <form action="edit-game.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Game</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($game['nama']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Genre</label>
                    <input type="text" name="genre" value="<?= htmlspecialchars($game['genre']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Jumlah Pemain</label>
                    <input type="text" name="pemain" value="<?= htmlspecialchars($game['pemain']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Durasi Bermain</label>
                    <input type="text" name="durasi" value="<?= htmlspecialchars($game['durasi']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi & Cara Main Singkat</label>
                    <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($game['deskripsi']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Gambar Sekarang:</label><br>
                    <img src="../assets/images/<?= $game['gambar'] ?>" width="100" style="margin-bottom: 10px; border-radius: 5px;"><br>
                    <label>Ganti Gambar Baru *(Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" name="gambar" accept="image/png, image/jpeg, image/jpg">
                </div>
                <button type="submit" class="btn" style="background-color: #f39c12;">Update Data</button>
                <a href="games.php" style="display: block; text-align: center; margin-top: 15px; color: #7f8c8d;">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>