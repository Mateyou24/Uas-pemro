<?php 
include 'includes/db.php';
include 'includes/header.php'; 

// 1. CEK ID GAME
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil data game
    $stmt = $conn->prepare("SELECT * FROM board_games WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
    } else {
        echo "<div style='text-align:center; padding:50px;'><h2>Game tidak ditemukan!</h2></div>";
        include 'includes/footer.php';
        exit;
    }
} else {
    header("Location: game.php");
    exit;
}

// 2. PROSES INPUT REVIEW JIKA FORM DIKIRIM
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    // Jika user sudah login, pakai username-nya. Jika belum, pakai nama yang diinput di form.
    $nama_reviewer = isset($_SESSION['username']) ? $_SESSION['username'] : $_POST['nama_reviewer'];
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];

    $stmt_rev = $conn->prepare("INSERT INTO reviews (board_game_id, nama_reviewer, rating, komentar) VALUES (?, ?, ?, ?)");
    $stmt_rev->bind_param("isis", $id, $nama_reviewer, $rating, $komentar);
    
    if ($stmt_rev->execute()) {
        // Refresh halaman agar review langsung muncul tanpa double post
        header("Location: detail.php?id=" . $id);
        exit;
    }
}

// 3. AMBIL DATA REVIEW UNTUK GAME INI
$reviews = $conn->query("SELECT * FROM reviews WHERE board_game_id = $id ORDER BY id DESC");
?>

<div class="detail-container">
    <div class="detail-img">
        <img src="assets/images/<?= htmlspecialchars($game['gambar']); ?>" alt="<?= htmlspecialchars($game['nama']); ?>">
    </div>
    
    <div class="detail-info">
        <h2><?= htmlspecialchars($game['nama']); ?></h2>
        
        <div class="badge">Genre: <?= htmlspecialchars($game['genre']); ?></div>
        <div class="badge">Pemain: <?= htmlspecialchars($game['pemain']); ?></div>
        <div class="badge">Durasi: <?= htmlspecialchars($game['durasi']); ?></div>
        
        <div class="detail-desc">
            <h3 style="margin-bottom: 10px;">Deskripsi & Cara Main</h3>
            <p><?= nl2br(htmlspecialchars($game['deskripsi'])); ?></p>
        </div>
        
        <a href="game.php" class="btn" style="display: inline-block; width: auto; margin-top: 30px; background-color: #95a5a6;">Kembali ke Katalog</a>
    </div>
</div>

<div class="review-section">
    <h3>Ulasan Pengguna (<?= $reviews->num_rows ?>)</h3>
    <br>

    <div class="form-container" style="max-width: 100%; margin: 0 0 30px 0; background: #f9f9f9; padding: 20px;">
        <h4>Tulis Ulasan Anda</h4>
        <br>
        <form action="detail.php?id=<?= $id ?>" method="POST">
            
            <?php if (!isset($_SESSION['username'])): ?>
                <div class="form-group">
                    <label>Nama Anda</label>
                    <input type="text" name="nama_reviewer" required placeholder="Masukkan nama Anda">
                </div>
            <?php else: ?>
                <p style="margin-bottom: 15px; color: #27ae60;">Mengulas sebagai: <strong><?= $_SESSION['username'] ?></strong></p>
            <?php endif; ?>

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
                <textarea name="komentar" rows="3" required placeholder="Tulis pendapatmu tentang game ini..."></textarea>
            </div>

            <button type="submit" name="submit_review" class="btn" style="width: auto;">Kirim Ulasan</button>
        </form>
    </div>

    <div class="reviews-list">
        <?php if ($reviews->num_rows > 0): ?>
            <?php while($rev = $reviews->fetch_assoc()): ?>
                <div class="review-card">
                    <div class="review-header">
                        <span class="review-name"><?= htmlspecialchars($rev['nama_reviewer']) ?></span>
                        <span class="review-rating">
                            <?php 
                            // Mengubah angka rating menjadi simbol bintang
                            echo str_repeat("⭐", $rev['rating']); 
                            ?>
                        </span>
                    </div>
                    <p class="review-comment"><?= nl2br(htmlspecialchars($rev['komentar'])) ?></p>
                    <small style="color: #bbb; font-size: 11px;"><?= $rev['created_at'] ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #7f8c8d; text-align: center; padding: 20px 0;">Belum ada ulasan untuk game ini. Jadilah yang pertama!</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>