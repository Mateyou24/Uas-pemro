<?php
session_start();
include '../includes/db.php';

// Proteksi halaman
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Cek apakah ada ID game yang mau dihapus
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // [Opsional] Ambil nama gambar lama untuk dihapus dari folder biar ga menumpuk sampah file
    $stmt_img = $conn->prepare("SELECT gambar FROM board_games WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $res_img = db_get_result($stmt_img)->fetch_assoc();
    
    if ($res_img && $res_img['gambar'] != 'default.jpg') {
        $path_gambar = "../assets/images/" . $res_img['gambar'];
        if (file_exists($path_gambar)) {
            unlink($path_gambar); // Menghapus file gambar fisik dari server
        }
    }

    // Hapus data dari database
    $stmt_delete = $conn->prepare("DELETE FROM board_games WHERE id = ?");
    $stmt_delete->bind_param("i", $id);
    
    if ($stmt_delete->execute()) {
        header("Location: games.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . $conn->error;
    }
} else {
    header("Location: games.php");
    exit;
}
?>