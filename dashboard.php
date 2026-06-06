<?php
include 'includes/db.php';

$result = $conn->query("
    SELECT * FROM board_games
    ORDER BY id DESC
    LIMIT 10
");

$reviews = $conn->query("
    SELECT r.*, b.nama AS nama_game, b.id AS game_id,
           u.avatar, u.role
    FROM reviews r
    JOIN board_games b ON r.board_game_id = b.id
    LEFT JOIN users u ON r.nama_reviewer = u.username
    ORDER BY r.created_at DESC
    LIMIT 8
");

include 'includes/header.php';
?>

<!-- HERO -->
<section class="hero">

    <div class="hero-content">

        <span class="hero-tag">
            PLATFORM KOMUNITAS MODERN
        </span>

        <h1>
            Temukan Board Game
            Favoritmu
        </h1>

        <p>
            Platform komunitas board game modern untuk eksplorasi, review, dan strategi.
            Temukan koleksi terbaik dan bergabung dengan ribuan pemain lainnya di seluruh
            dunia.
        </p>

        <div class="hero-buttons">

            <a href="game.php" class="btn-primary">
                Jelajahi Game
            </a>

            <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn-secondary">
                Masuk
            </a>
            <?php endif; ?>

        </div>

    </div>

</section>

<!-- FEATURED GAMES -->
<section class="featured-games">

    <div class="section-header">

        <h2>Permainan</h2>

        <a href="game.php">
            Board Game Lainnya →
        </a>

    </div>

    <div class="games-slider">

        <?php while($row = $result->fetch_assoc()): ?>

        <div class="game-card">

            <img
                src="assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                alt="<?= htmlspecialchars($row['nama']); ?>"
            >

            <div class="game-card-body">

                <span class="game-meta">
                    <?= htmlspecialchars($row['genre']); ?>
                </span>

                <h3>
                    <?= htmlspecialchars($row['nama']); ?>
                </h3>

                <a href="detail.php?id=<?= $row['id']; ?>" class="btn-detail">
                    Lihat Detail
                </a>

            </div>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<!-- CATEGORIES -->
<section class="categories">

    <h2>Jelajahi Berbagai Genre</h2>

    <div class="category-list">
        <a href="game.php">BoardGame</a>
    </div>

</section>

<!-- RIWAYAT ULASAN -->
<section class="review-section dashboard-reviews">

    <div class="section-header" style="margin-bottom:25px;">
        <h3 style="margin-bottom:0;">Riwayat Ulasan Terbaru</h3>
    </div>

    <div class="reviews-list">

    <?php if ($reviews->num_rows > 0): ?>

        <?php while($rev = $reviews->fetch_assoc()): ?>

        <div class="review-card">

            <div class="review-header">

                <div class="reviewer-info">

                    <?php
                        $avatar   = $rev['avatar'] ?? '';
                        $hasAvatar = !empty($avatar) && $avatar !== 'default-avatar.png';
                        $initials  = strtoupper(substr($rev['nama_reviewer'], 0, 1));
                    ?>

                    <?php if ($hasAvatar): ?>
                        <img
                            src="assets/avatars/<?= htmlspecialchars($avatar) ?>"
                            alt="Avatar <?= htmlspecialchars($rev['nama_reviewer']) ?>"
                            class="reviewer-avatar"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                        >
                        <div class="reviewer-avatar-fallback" style="display:none;">
                            <?= $initials ?>
                        </div>
                    <?php else: ?>
                        <img
                            src="assets/avatars/default-avatar.png"
                            alt="Avatar <?= htmlspecialchars($rev['nama_reviewer']) ?>"
                            class="reviewer-avatar"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                        >
                        <div class="reviewer-avatar-fallback" style="display:none;">
                            <?= $initials ?>
                        </div>
                    <?php endif; ?>

                    <div class="reviewer-meta">
                        <span class="review-name">
                            <?= htmlspecialchars($rev['nama_reviewer']) ?>
                        </span>
                        <span class="review-role">
                            <?= htmlspecialchars($rev['role'] ?? 'member') ?>
                        </span>
                    </div>

                </div>

                <span class="review-rating">
                    <?= str_repeat("⭐", $rev['rating']) ?>
                </span>

            </div>

            <a href="detail.php?id=<?= $rev['game_id'] ?>" class="review-game-link">
                📋 <?= htmlspecialchars($rev['nama_game']) ?>
            </a>

            <p class="review-comment">
                <?= nl2br(htmlspecialchars($rev['komentar'])) ?>
            </p>

            <small class="review-date">
                <?= htmlspecialchars($rev['created_at']) ?>
            </small>

        </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p style="color:var(--text-soft); text-align:center; padding:30px 0;">
            Belum ada ulasan. Jadilah yang pertama mengulas!
        </p>

    <?php endif; ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>
