<?php
session_start();
include '../includes/db.php';

// Proteksi halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data game dari database (diurutkan dari yang terbaru)
$result = $conn->query("SELECT * FROM board_games ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Games - Admin</title>

    <link rel="stylesheet" href="../assets/style-admin.css">
</head>
<body>

<nav class="admin-navbar">

    <div class="admin-logo">
        🎲 Tavern Of Meeple
    </div>

    <div class="admin-menu">
        <a href="index.php">Dashboard</a>
        <a href="games.php" class="active">Games</a>
        <a href="reviews.php">Reviews</a>
        <a href="../logout.php" class="logout">Logout</a>
    </div>

</nav>

<div class="admin-container">

    <div class="page-header">

        <div>
            <span class="dashboard-tag">
                GAME MANAGEMENT
            </span>

            <h1>Manage Board Games</h1>

            <p>
                Kelola seluruh koleksi board game yang tersedia.
            </p>
        </div>

        <a href="tambah-game.php" class="add-btn">
            + Add New Game
        </a>

    </div>

    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Game Name</th>
                    <th>Genre</th>
                    <th>Players</th>
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
                        <img
                            src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                            alt="Game"
                            class="game-thumb">
                    </td>

                    <td><?= htmlspecialchars($row['nama']); ?></td>

                    <td><?= htmlspecialchars($row['genre']); ?></td>

                    <td><?= htmlspecialchars($row['pemain']); ?></td>

                    <td class="action-cell">

                        <a
                            href="edit-game.php?id=<?= $row['id']; ?>"
                            class="btn-edit">
                            Edit
                        </a>

                        <a
                            href="hapus-game.php?id=<?= $row['id']; ?>"
                            class="btn-delete"
                            onclick="return confirm('Yakin ingin menghapus game ini?');">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endwhile; ?>

                <?php if($result->num_rows == 0): ?>

                <tr>
                    <td colspan="6" class="empty-state">
                        No games available.
                    </td>
                </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>