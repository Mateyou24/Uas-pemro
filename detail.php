<?php
include 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. CEK ID GAME
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM board_games WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = db_get_result($stmt);

    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
    } else {
        include 'includes/header.php';
        echo "<div style='text-align:center; padding:50px;'><h2>Game tidak ditemukan!</h2></div>";
        include 'includes/footer.php';
        exit;
    }
} else {
    header("Location: game.php");
    exit;
}

// 2. PROSES REVIEW
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $nama_reviewer = $_SESSION['username'];
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];

    $stmt_rev = $conn->prepare("
        INSERT INTO reviews
        (board_game_id, nama_reviewer, rating, komentar)
        VALUES (?, ?, ?, ?)
    ");

    $stmt_rev->bind_param("isis", $id, $nama_reviewer, $rating, $komentar);

    if ($stmt_rev->execute()) {
        header("Location: detail.php?id=" . $id);
        exit;
    }
}

// 3. AMBIL REVIEW
$reviews = $conn->query("
    SELECT r.*, u.avatar, u.role
    FROM reviews r
    LEFT JOIN users u ON r.nama_reviewer = u.username
    WHERE r.board_game_id = $id
    ORDER BY r.id DESC
");

// 4. SUMMARY RATING
$stmt_sum = $conn->prepare("
    SELECT COALESCE(AVG(rating), 0) AS avg_rating,
           COUNT(id) AS review_count,
           SUM(rating = 5) AS bintang5,
           SUM(rating = 4) AS bintang4,
           SUM(rating = 3) AS bintang3,
           SUM(rating = 2) AS bintang2,
           SUM(rating = 1) AS bintang1
    FROM reviews WHERE board_game_id = ?
");
$stmt_sum->bind_param("i", $id);
$stmt_sum->execute();
$summary = db_get_result($stmt_sum)->fetch_assoc();
$avg_rating   = round((float)$summary['avg_rating'], 1);
$review_count = (int)$summary['review_count'];

include 'includes/header.php';
?>

<!-- DETAIL GAME -->

<div class="detail-container">

    <div class="detail-img">
        <img
            src="assets/images/<?= htmlspecialchars($game['gambar']); ?>"
            alt="<?= htmlspecialchars($game['nama']); ?>"
        >
    </div>

    <div class="detail-info">

    <h2 class="game-title">
        <?= htmlspecialchars($game['nama']); ?>
    </h2>

    <div class="game-meta">

        <div class="meta-item">
            <span class="meta-label">Genre</span>
            <span class="meta-value">
                <?= htmlspecialchars($game['genre']); ?>
            </span>
        </div>

        <div class="meta-item">
            <span class="meta-label">Jumlah Pemain</span>
            <span class="meta-value">
                <?= htmlspecialchars($game['pemain']); ?>
            </span>
        </div>

        <div class="meta-item">
            <span class="meta-label">Durasi Bermain</span>
            <span class="meta-value">
                <?= htmlspecialchars($game['durasi']); ?>
            </span>
        </div>

    </div>

    <div class="detail-desc">

        <h3>Deskripsi & Cara Bermain</h3>

        <p>
            <?= nl2br(htmlspecialchars($game['deskripsi'])); ?>
        </p>

    </div>

    <a href="game.php" class="btn-back">
        Kembali ke Katalog
    </a>

</div>

</div>

<!-- REVIEW -->

<div class="review-section">

    <h3 class="review-title">
        Ulasan Pengguna (<?= $reviews->num_rows ?>)
    </h3>

    <!-- RATING SUMMARY -->
    <?php if($review_count > 0):
        $full  = floor($avg_rating);
        $half  = ($avg_rating - $full) >= 0.5 ? 1 : 0;
        $empty = 5 - $full - $half;
    ?>
    <div class="rating-summary">

        <div class="rating-summary-left">
            <div class="rating-big-score"><?= number_format($avg_rating, 1) ?></div>
            <div class="rating-big-stars">
                <?php for($i=0;$i<$full;$i++): ?><span class="star full">★</span><?php endfor; ?>
                <?php if($half): ?><span class="star half">★</span><?php endif; ?>
                <?php for($i=0;$i<$empty;$i++): ?><span class="star empty">★</span><?php endfor; ?>
            </div>
            <div class="rating-big-count"><?= $review_count ?> ulasan</div>
        </div>

        <div class="rating-summary-bars">
            <?php
            $bars = [5=>'bintang5',4=>'bintang4',3=>'bintang3',2=>'bintang2',1=>'bintang1'];
            foreach($bars as $num => $key):
                $val  = (int)($summary[$key] ?? 0);
                $pct  = $review_count > 0 ? round($val / $review_count * 100) : 0;
            ?>
            <div class="rating-bar-row">
                <span class="rating-bar-label"><?= $num ?> ★</span>
                <div class="rating-bar-track">
                    <div class="rating-bar-fill" style="width:<?= $pct ?>%"></div>
                </div>
                <span class="rating-bar-count"><?= $val ?></span>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id'])): ?>

<div class="form-container review-form">

    <h4>Tulis Ulasan Anda</h4>

    <p style="margin-bottom:15px; color:#22c55e;">
        Mengulas sebagai:
        <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
    </p>

    <form action="detail.php?id=<?= $id ?>" method="POST">

        <div class="form-group">

            <label>Rating</label>

            <select name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                <option value="3">⭐⭐⭐ (3 - Lumayan)</option>
                <option value="2">⭐⭐ (2 - Kurang Seru)</option>
                <option value="1">⭐ (1 - Kecewa)</option>
            </select>

        </div>

        <div class="form-group">

            <label>Komentar / Review</label>

            <textarea
                name="komentar"
                rows="4"
                required
                placeholder="Tulis pendapatmu tentang game ini..."
            ></textarea>

        </div>

        <button type="submit" name="submit_review" class="btn">
            Kirim Ulasan
        </button>

    </form>

</div>

<?php else: ?>

<div class="review-login-box">

    <h4>Login untuk Memberikan Ulasan</h4>

    <p>
        Anda harus login terlebih dahulu untuk memberikan
        rating dan ulasan pada board game ini.
    </p>

    <a href="login.php" class="btn-login-review">
        Login Sekarang
    </a>

</div>

<?php endif; ?>

    <div class="reviews-list">

    <?php if ($reviews->num_rows > 0): ?>

        <?php while($rev = $reviews->fetch_assoc()): ?>

            <div class="review-card">

                <div class="review-header">

                    <div class="reviewer-info">

                        <?php
    $avatar = $rev['avatar'] ?? '';
    $hasAvatar = !empty($avatar) && $avatar !== 'default-avatar.png';
    $initials = strtoupper(substr($rev['nama_reviewer'], 0, 1));
?>

<?php if ($hasAvatar): ?>
    <img
        src="assets/avatars/<?= htmlspecialchars($avatar) ?>"
        alt="Avatar <?= htmlspecialchars($rev['nama_reviewer']) ?>"
        class="reviewer-avatar"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
    >
    <div class="reviewer-avatar-fallback" style="display:none;">
        <?= $initials ?>
    </div>
<?php else: ?>
    <img
        src="assets/avatars/default-avatar.png"
        alt="Avatar <?= htmlspecialchars($rev['nama_reviewer']) ?>"
        class="reviewer-avatar"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
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
                        <?= str_repeat("⭐", $rev['rating']); ?>
                    </span>

                </div>

                <p class="review-comment">
                    <?= nl2br(htmlspecialchars($rev['komentar'])) ?>
                </p>

                <small class="review-date">
                    <?= $rev['created_at'] ?>
                </small>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p style="color:#94a3b8; text-align:center; padding:25px 0;">
            Belum ada ulasan untuk game ini. Jadilah yang pertama!
        </p>

    <?php endif; ?>

</div>

</div>

<?php include 'includes/footer.php'; ?>
