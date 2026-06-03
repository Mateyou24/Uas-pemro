<?php 
include 'includes/db.php';
include 'includes/header.php'; 

// Inisialisasi keyword pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Jika ada pencarian, gunakan query dengan WHERE LIKE
if ($search != '') {
    $stmt = $conn->prepare("SELECT * FROM board_games WHERE nama LIKE ? ORDER BY id DESC");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Jika tidak ada pencarian, ambil semua game
    $result = $conn->query("SELECT * FROM board_games ORDER BY id DESC");
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
        <a href="game.php" style="padding: 10px; background:#bdc3c7; color:#333; text-decoration:none; border-radius:4px;">Reset</a>
    <?php endif; ?>
</form>

<div class="game-grid">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="game-card">
            <img src="assets/images/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>">
            <div class="game-card-body">
                <h3><?= htmlspecialchars($row['nama']); ?></h3>
                <div class="game-meta"><strong>Genre:</strong> <?= htmlspecialchars($row['genre']); ?></div>
                <div class="game-meta"><strong>Pemain:</strong> <?= htmlspecialchars($row['pemain']); ?></div>
                <a href="detail.php?id=<?= $row['id']; ?>" class="btn-detail">Lihat Detail</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php if($result->num_rows == 0): ?>
    <div style="text-align: center; margin-top: 40px; color: #7f8c8d;">
        <p>Game dengan kata kunci "<strong><?= htmlspecialchars($search) ?></strong>" tidak ditemukan.</p>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>