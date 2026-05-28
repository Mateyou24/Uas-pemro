<?php
include 'includes/db.php';
include 'includes/header.php';

// Jika user sudah login, lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'member'; // Setiap orang yang daftar otomatis jadi member biasa

    // Validasi 1: Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Validasi 2: Cek apakah username sudah dipakai orang lain
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = "Username sudah terdaftar! Silakan cari nama lain.";
        } else {
            // Jika aman, hash/enkripsi password baru
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan user baru ke database
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $password_hashed, $role);

            if ($stmt_insert->execute()) {
                $success = "Registrasi BERHASIL! Silakan <a href='login.php'>Login di sini</a>.";
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
        }
    }
}
?>

<div class="form-container">
    <h2 style="text-align: center; margin-bottom: 20px;">Daftar Akun Baru</h2>
    
    <?php if ($error != ''): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success != ''): ?>
        <div class="alert-error" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb;"><?= $success ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username Baru</label>
            <input type="text" name="username" id="username" required autocomplete="off" minlength="4" placeholder="Minimal 4 karakter">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required minlength="6" placeholder="Minimal 6 karakter">
        </div>
        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Ulangi password Anda">
        </div>
        <button type="submit" class="btn" style="background-color: #34495e;">Daftar Sekarang</button>
        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Sudah punya akun? <a href="login.php">Login</a>
        </p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>