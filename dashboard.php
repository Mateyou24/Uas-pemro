<?php
include 'includes/db.php';
include 'includes/header.php';

$result = $conn->query("
SELECT *
FROM board_games
ORDER BY id DESC
LIMIT 10
");
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

                <a
                    href="detail.php?id=<?= $row['id']; ?>"
                    class="btn-detail"
                >
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

<?php include 'includes/footer.php'; ?>