<?php
// Panggil koneksi database
include 'includes/db.php';
// Panggil header (di dalamnya sudah ada session_start())
include 'includes/header.php';

// Jika user sudah login, larang akses ke halaman ini, lempar ke index
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

// Proses jika tombol login ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cari data user di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Cek kecocokan password (karena di Fase 2 kita pakai fungsi hash/enkripsi)
        if (password_verify($password, $user['password'])) {
            // Jika benar, simpan data ke Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan berdasarkan role
            if ($user['role'] == 'admin') {
                header("Location: admin/index.php"); // Halaman admin (belum dibuat, nanti error 404, tidak apa-apa)
            } else {
                header("Location: index.php"); // Halaman utama
            }
            exit;
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Username tidak terdaftar!";
    }
}
?>

<div class="form-container">
    <h2 style="text-align: center; margin-bottom: 20px;">Login</h2>
    
    <?php if ($error != ''): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">Masuk</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>