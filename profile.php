<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = db_get_result($stmt)->fetch_assoc();

$reviewQuery = $conn->prepare("
    SELECT reviews.*, board_games.nama
    FROM reviews
    JOIN board_games ON reviews.board_game_id = board_games.id
    WHERE reviews.nama_reviewer = ?
    ORDER BY reviews.created_at DESC
");
$reviewQuery->bind_param("s", $username);
$reviewQuery->execute();
$reviews = db_get_result($reviewQuery);
$totalReviews = $reviews->num_rows;

include 'includes/header.php';
?>

<div class="profile-container">

    <div class="profile-hero">

    <div class="profile-hero-overlay">

        <img
            src="assets/avatars/<?=
            !empty($user['avatar'])
            ? htmlspecialchars($user['avatar'])
            : 'default-avatar.png';
            ?>"
            class="profile-avatar-large"
            id="profileAvatar"
            alt="Avatar"
        >

        <div class="profile-info-large">

            <span class="profile-label">
                PROFILE
            </span>

            <h1>
                <?= htmlspecialchars($user['username']); ?>
            </h1>

            <p class="profile-bio-large">
                <?= !empty($user['bio'])
                    ? nl2br(htmlspecialchars($user['bio']))
                    : 'Board Game Enthusiast';
                ?>
            </p>

            <div class="profile-stats">

                <div class="stat">
                    🎲 <?= $totalReviews ?> Review
                </div>

                <div class="stat">
                    👤 Member
                </div>

            </div>

            <a
                href="edit-profile.php"
                class="btn-edit-profile"
            >
                Edit Profil
            </a>

        </div>

    </div>

</div>

    <!-- REVIEW SAYA -->
    <div class="review-list-card">

        <h3>🎲 Review Saya</h3>

        <?php if ($totalReviews > 0): ?>

            <?php while ($review = $reviews->fetch_assoc()): ?>

                <div class="profile-review-item">

                    <div class="review-stars">
                        <?= str_repeat("⭐", $review['rating']); ?>
                    </div>

                    <h4>
                        <?= htmlspecialchars($review['nama']); ?>
                    </h4>

                    <p>
                        <?= htmlspecialchars($review['komentar']); ?>
                    </p>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="review-empty">
                Belum ada review yang ditulis.
            </div>

        <?php endif; ?>

    </div>

</div>

<div id="avatarModal" class="avatar-modal">

    <span class="close-avatar">
        &times;
    </span>

    <img
        id="avatarModalImg"
        class="avatar-modal-content"
    >

</div>

<?php include 'includes/footer.php'; ?>
