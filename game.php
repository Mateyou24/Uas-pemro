<?php 
include 'includes/db.php';
include 'includes/header.php'; 

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search != '') {
    $stmt = $conn->prepare("
        SELECT bg.*,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM board_games bg
        LEFT JOIN reviews r ON bg.id = r.board_game_id
        WHERE bg.nama LIKE ?
        GROUP BY bg.id
        ORDER BY bg.id DESC
    ");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = db_get_result($stmt);
} else {
    $result = $conn->query("
        SELECT bg.*,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM board_games bg
        LEFT JOIN reviews r ON bg.id = r.board_game_id
        GROUP BY bg.id
        ORDER BY bg.id DESC
    ");
}
?>

<h2 class="page-title">Koleksi Board Game</h2>

<p class="page-subtitle">
    Jelajahi koleksi board game terbaik untuk dimainkan bersama teman dan keluarga.
</p>

<form action="game.php" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Masukkan nama board game yang dicari..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Cari</button>
    <?php if($search != ''): ?>
        <a href="game.php" style="padding:10px; background:#bdc3c7; color:#333; text-decoration:none; border-radius:4px;">Reset</a>
    <?php endif; ?>
</form>

<div class="game-grid">
    <?php while($row = $result->fetch_assoc()):
        $avg    = round((float)$row['avg_rating'], 1);
        $count  = (int)$row['review_count'];
        $full   = floor($avg);
        $half   = ($avg - $full) >= 0.5 ? 1 : 0;
        $empty  = 5 - $full - $half;
    ?>
        <div class="game-card">
            <img src="assets/images/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>">
            <div class="game-card-body">
                <h3><?= htmlspecialchars($row['nama']); ?></h3>
                <div class="game-meta"><strong>Genre:</strong> <?= htmlspecialchars($row['genre']); ?></div>
                <div class="game-meta"><strong>Pemain:</strong> <?= htmlspecialchars($row['pemain']); ?></div>

                <!-- RATING SUMMARY -->
                <div class="card-rating">
                    <div class="card-stars">
                        <?php for($i = 0; $i < $full; $i++): ?>
                            <span class="star full">★</span>
                        <?php endfor; ?>
                        <?php if($half): ?>
                            <span class="star half">★</span>
                        <?php endif; ?>
                        <?php for($i = 0; $i < $empty; $i++): ?>
                            <span class="star empty">★</span>
                        <?php endfor; ?>
                    </div>
                    <span class="card-rating-score">
                        <?= $count > 0 ? number_format($avg, 1) : '—' ?>
                    </span>
                    <span class="card-rating-count">
                        (<?= $count ?> ulasan)
                    </span>
                </div>

                <a href="detail.php?id=<?= $row['id']; ?>" class="btn-detail">Lihat Detail</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php if($result->num_rows == 0): ?>
    <div style="text-align:center; margin-top:40px; color:#7f8c8d;">
        <p>Game dengan kata kunci "<strong><?= htmlspecialchars($search) ?></strong>" tidak ditemukan.</p>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
