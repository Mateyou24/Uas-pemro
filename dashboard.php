<?php 
include 'includes/db.php';
include 'includes/header.php'; 

// Ambil 3 game terbaru untuk di-featured
$result = $conn->query("SELECT * FROM board_games ORDER BY id DESC LIMIT 3");
?>

<div style="text-align: center; padding: 40px 20px; background: #2c3e50; color: white; border-radius: 8px; margin-bottom: 30px;">
    <h1>Selamat Datang di Board Game Hub 🎲</h1>
    <p style="margin-top: 10px; font-size: 18px;">Temukan, pelajari, dan beri ulasan board game favoritmu di sini!</p>
</div>

<h2>Board Game Terbaru</h2>
<div class="game-grid">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="game-card">
            <img src="assets/images/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama']); ?>">
            <div class="game-card-body">
                <h3><?= htmlspecialchars($row['nama']); ?></h3>
                <div class="game-meta"><strong>Genre:</strong> <?= htmlspecialchars($row['genre']); ?></div>
                <div class="game-meta"><strong>Pemain:</strong> <?= htmlspecialchars($row['pemain']); ?></div>
                <div class="game-meta"><strong>Durasi:</strong> <?= htmlspecialchars($row['durasi']); ?></div>
                <a href="detail.php?id=<?= $row['id']; ?>" class="btn-detail" style="margin-top: 15px;">Lihat Detail</a>
            </div>
        </div>
    <?php endwhile; ?>

    <?php if($result->num_rows == 0): ?>
        <p>Belum ada board game yang tersedia.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>