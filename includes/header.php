<?php
// Allow iframe embedding (Replit preview)
header('X-Frame-Options: ALLOWALL');
header('Content-Security-Policy: frame-ancestors *');

// Memulai session dengan pengaturan cookie untuk cross-site iframe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userAvatar = "default-avatar.png";

if(isset($_SESSION['user_id'])){

    include_once 'includes/db.php';

    $uid = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT avatar
        FROM users
        WHERE id = ?
    ");

    $stmt->bind_param("i",$uid);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $userData =
        $result->fetch_assoc();

        if(!empty($userData['avatar'])){

            $userAvatar =
            $userData['avatar'];
        }
    }
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board Game Hub</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav class="navbar">

    <div class="logo">
        🎲 Tavern Of Meeple
    </div>

    <div class="nav-right">

        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="game.php">Catalog</a></li>
            <li><a href="about.php">About Us</a></li>
     <!--   <li><a href="top-rated.php">Top Rated</a></li>  - Optional! kalau mau ada top ratenya--> 
        </ul>

    <?php if(isset($_SESSION['user_id'])): ?>

        <a href="profile.php" class="profile-nav">

            <img
                src="assets/avatars/<?= $userAvatar; ?>"
                class="nav-avatar"
                alt="Profile"
            >


        </a>

        <a href="logout.php" class="login-btn">
            Logout
        </a>

    <?php else: ?>

        <a href="login.php" class="login-btn">
            Masuk
        </a>

    <?php endif; ?>

</div>

</nav>
    