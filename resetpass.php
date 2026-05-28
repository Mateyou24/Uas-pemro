<?php
include 'includes/db.php';

// Kita hash password "admin123" menggunakan fungsi sistemmu
$password_baru = password_hash("admin123", PASSWORD_DEFAULT);

// Update database langsung
$update = $conn->query("UPDATE users SET password = '$password_baru' WHERE username = 'admin'");

if ($update) {
    echo "Password berhasil di-reset! Silakan coba login lagi.";
    echo "<br><br><b>JANGAN LUPA: Hapus file reset_pass.php ini setelah selesai!</b>";
} else {
    echo "Gagal: " . $conn->error;
}
?>