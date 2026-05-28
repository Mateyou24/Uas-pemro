<?php
session_start();
session_destroy(); // Menghapus semua data session
header("Location: login.php"); // Arahkan kembali ke halaman login
exit;
?>