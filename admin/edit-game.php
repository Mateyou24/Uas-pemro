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
    $result = db_get_result($stmt);
    
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

        <h1>Edit Board Game</h1>

        <p>
            Perbarui informasi board game yang tersedia.
        </p>

    </div>

    <div class="admin-form-card">

        <form
            action="edit-game.php?id=<?= $id ?>"
            method="POST"
            enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama Game</label>

                <input
                    type="text"
                    name="nama"
                    value="<?= htmlspecialchars($game['nama']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Genre</label>

                <input
                    type="text"
                    name="genre"
                    value="<?= htmlspecialchars($game['genre']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Jumlah Pemain</label>

                <input
                    type="text"
                    name="pemain"
                    value="<?= htmlspecialchars($game['pemain']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Durasi Bermain</label>

                <input
                    type="text"
                    name="durasi"
                    value="<?= htmlspecialchars($game['durasi']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="6"
                    required><?= htmlspecialchars($game['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">

                <label>Current Image</label>

                <div class="image-preview">

                    <img
                        src="../assets/images/<?= htmlspecialchars($game['gambar']) ?>"
                        alt="Game Image">

                </div>

            </div>

            <div class="form-group">

                <label>Upload New Image</label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/png, image/jpeg, image/jpg">

                <small>
                    Leave empty if you don't want to change the image.
                </small>

            </div>

            <div class="form-actions">

                <button type="submit" class="save-btn">
                    Update Game
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