<?php
// Memulai session
session_start();

// Koneksi database
include '../includes/db.php';

// Proteksi halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Statistik Dashboard
$total_games = $conn->query("SELECT COUNT(*) as total FROM board_games")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$total_reviews = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tavern Of Meeple</title>

    <link rel="stylesheet" href="../assets/style-admin.css">
</head>
<body>

<nav class="admin-navbar">

    <div class="admin-logo">
        🎲 Tavern Of Meeple
    </div>

    <div class="admin-menu">

        <a href="index.php" class="active">
            Dashboard
        </a>

        <a href="games.php">
            Games
        </a>

        <a href="reviews.php">
            Reviews
        </a>

        <a href="../logout.php" class="logout">
            Logout
        </a>

    </div>

</nav>

<div class="admin-container">

    <div class="hero-section">

    <span class="dashboard-tag">
        ADMIN DASHBOARD
    </span>

    <h1>
        Welcome Back,
        <?= htmlspecialchars($_SESSION['username']); ?> 👋
    </h1>

    <p>
        Pantau Board Game, Pengguna, dan Ulasan dari satu tempat.
    </p>

</div>

    </div>

    <div class="stats-grid">

<div class="stat-card">

    <div class="stat-top">🎲</div>

    <span><?= $total_games ?></span>

    <h3>Board Games</h3>

</div>

<div class="stat-card">

    <div class="stat-top">👥</div>

    <span><?= $total_users ?></span>

    <h3>Users</h3>


</div>


    <div class="stat-card">
        <div class="stat-top">
            ⭐
        </div>
        <span><?= $total_reviews ?></span>
        <h3>Reviews</h3>
    </div>

</div>



</body>
</html>