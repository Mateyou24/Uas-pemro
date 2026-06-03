<?php
session_start();
include '../includes/db.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua ulasan, digabung (JOIN) dengan tabel board_games untuk tahu ulasan ini milik game apa
$sql = "SELECT reviews.*, board_games.nama AS nama_game 
        FROM reviews 
        JOIN board_games ON reviews.board_game_id = board_games.id 
        ORDER BY reviews.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews - Admin</title>

    <link rel="stylesheet" href="../assets/style-admin.css">
</head>
<body>

<nav class="admin-navbar">

    <div class="admin-logo">
        🎲 Tavern Of Meeple
    </div>

    <div class="admin-menu">
        <a href="index.php">Dashboard</a>
        <a href="games.php">Games</a>
        <a href="reviews.php" class="active">Reviews</a>
        <a href="../logout.php" class="logout">Logout</a>
    </div>

</nav>

<div class="admin-container">

    <div class="page-header">

        <div>

            <span class="dashboard-tag">
                REVIEW MANAGEMENT
            </span>

            <h1>User Reviews</h1>

            <p>
                Kelola seluruh ulasan dan rating yang diberikan pengguna.
            </p>

        </div>

    </div>

    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Game</th>
                    <th>Reviewer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php
                $no = 1;
                while($row = $result->fetch_assoc()):
                ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>
                        <strong>
                            <?= htmlspecialchars($row['nama_game']); ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_reviewer']); ?>
                    </td>

                    <td class="rating-stars">
                        <?= str_repeat("★", $row['rating']); ?>
                    </td>

                    <td class="review-comment">
                        <?= nl2br(htmlspecialchars($row['komentar'])); ?>
                    </td>

                    <td>
                        <?= date('d M Y', strtotime($row['created_at'])); ?>
                    </td>

                    <td>

                        <a
                            href="hapus-review.php?id=<?= $row['id']; ?>"
                            class="btn-delete"
                            onclick="return confirm('Yakin ingin menghapus ulasan ini?');">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endwhile; ?>

                <?php if($result->num_rows == 0): ?>

                <tr>
                    <td colspan="7" class="empty-state">
                        No reviews available.
                    </td>
                </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>